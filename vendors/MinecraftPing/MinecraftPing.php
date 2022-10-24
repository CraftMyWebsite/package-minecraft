<?php

namespace xPaw;

use CMW\Entity\Minecraft\MinecraftPingEntity;
use CMW\Entity\Minecraft\MinecraftPingPlayersEntity;
use Exception;
use function dns_get_record;
use function explode;
use function fclose;
use function fgetc;
use function fread;
use function fwrite;
use function iconv;
use function ip2long;
use function json_last_error;
use function json_last_error_msg;
use function microtime;
use function ord;
use function pack;
use function strlen;
use function substr;

class MinecraftPing
{
    /*
     * Queries Minecraft server
     * Returns MinecraftPingEntity on success, null on failure.
     *
     * WARNING: This method was added in snapshot 13w41a (Minecraft 1.7)
     *
     * Written by xPaw and improve by Teyir
     *
     * Website: http://xpaw.me
     * GitHub: https://github.com/xPaw/PHP-Minecraft-Query
     *
     */

    private $socket;
    private string $serverAddress;
    private int $serverPort;
    private int $timeout;

    public function __construct(string $address, int $port = 25565, int $timeout = 2, bool $ResolveSRV = true)
    {
        $this->serverAddress = $address;
        $this->serverPort = $port;
        $this->timeout = $timeout;

        if ($ResolveSRV) {
            $this->resolveSRV();
        }

    }

    private function resolveSRV(): void
    {
        if (ip2long($this->serverAddress) !== false) {
            return;
        }

        $Record = @dns_get_record('_minecraft._tcp.' . $this->serverAddress, DNS_SRV);

        if (empty($Record)) {
            return;
        }

        if (isset($Record[0]['target'])) {
            $this->serverAddress = $Record[0]['target'];
        }

        if (isset($Record[0]['port'])) {
            $this->serverPort = $Record[0]['port'];
        }
    }

    /**
     * @return bool
     * @throws \xPaw\MinecraftPingException
     * @Todo When we can't connect the socket, return 0
     */
    public function connect(): bool
    {
        $this->socket = (@fsockopen($this->serverAddress, $this->serverPort, $errno, $errstr, (float)$this->timeout));

        if($this->socket === false){
            return false;
        }

        if (!$this->socket) {
            $this->socket = null;

            throw new MinecraftPingException("Failed to connect or create a socket: $errno ($errstr)");
        }

        // Set Read/Write timeout
        stream_set_timeout($this->socket, $this->timeout);
        return true;
    }

    public function __destruct()
    {
        $this->close();
    }

    public function close(): void
    {
        if ($this->socket !== null) {
            fclose($this->socket);

            $this->socket = null;
        }
    }

    public function query(): ?MinecraftPingEntity
    {
        $TimeStart = microtime(true); // for read timeout purposes

        $data = "\x00"; // packet ID = 0 (varint)

        $data .= "\x04"; // Protocol version (varint)
        $data .= pack('c', strlen($this->serverAddress)) . $this->serverAddress; // Server (varint len + UTF-8 addr)
        $data .= pack('n', $this->serverPort); // Server port (unsigned short)
        $data .= "\x01"; // Next state: status (varint)

        $data = pack('c', strlen($data)) . $data; // prepend length of packet ID + data

        fwrite($this->socket, $data . "\x01\x00"); // handshake followed by status ping

        $Length = $this->readVarInt(); // full packet length

        if ($Length < 10) {
            return null;
        }

        $this->readVarInt(); // packet type, in server ping it's 0

        $Length = $this->readVarInt(); // string length

        $data = "";
        while (strlen($data) < $Length) {
            if (microtime(true) - $TimeStart > $this->timeout) {
                throw new MinecraftPingException('Server read timed out');
            }

            $Remainder = $Length - strlen($data);
            $block = fread($this->socket, $Remainder); // and finally the json string
            // abort if there is no progress
            if (!$block) {
                throw new MinecraftPingException('Server returned too few data');
            }

            $data .= $block;
        }

        $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new MinecraftPingException('JSON parsing failed: ' . json_last_error_msg());
        }
        $playersEntity = array();

        if(array_key_exists("sampler", $data['players'])) {
            foreach ($data['players']['sample'] as $iValue) {
                $playersEntity[] = new MinecraftPingPlayersEntity($iValue['name'], $iValue['id']);
            }
        }

        return new MinecraftPingEntity(
            $data["version"]['protocol'],
            $data["version"]['name'],
            $data["players"]['online'],
            $data["players"]['max'],
            $playersEntity,
            $data["favicon"] ?? ""
        );
    }

    private function readVarInt(): int
    {
        $i = 0;
        $j = 0;

        while (true) {
            $k = @fgetc($this->socket);

            if ($k === FALSE) {
                return 0;
            }

            $k = ord($k);

            $i |= ($k & 0x7F) << $j++ * 7;

            if ($j > 5) {
                throw new MinecraftPingException('VarInt too big');
            }

            if (($k & 0x80) !== 128) {
                break;
            }
        }

        return $i;
    }

    public function queryOldPre17(): bool|array
    {

        if (!$this->socket){
            return array(
                'HostName' => "",
                'Players' => 0,
                'MaxPlayers' => 0,
                'Protocol' => 0,
                'Version' => ""
            );
        }

        fwrite($this->socket, "\xFE\x01");
        $Data = fread($this->socket, 512);
        $Len = strlen($Data);

        if ($Len < 4 || $Data[0] !== "\xFF") {
            return FALSE;
        }

        $Data = substr($Data, 3); // Strip packet header (kick message packet and short length)
        $Data = iconv('UTF-16BE', 'UTF-8', $Data);

        // Are we dealing with Minecraft 1.4+ server?
        if ($Data[1] === "\xA7" && $Data[2] === "\x31") {
            $Data = explode("\x00", $Data);

            return array(
                'HostName' => $Data[3],
                'Players' => (int)$Data[4],
                'MaxPlayers' => (int)$Data[5],
                'Protocol' => (int)$Data[1],
                'Version' => $Data[2]
            );
        }

        $Data = explode("\xA7", $Data);

        return array(
            'HostName' => substr($Data[0], 0, -1),
            'Players' => isset($Data[1]) ? (int)$Data[1] : 0,
            'MaxPlayers' => isset($Data[2]) ? (int)$Data[2] : 0,
            'Protocol' => 0,
            'Version' => '1.3'
        );
    }
}
