<?php

namespace xPaw;

use CMW\Entity\Minecraft\MinecraftPingEntity;
use CMW\Entity\Minecraft\MinecraftPingPlayersEntity;
use PHPMailer\PHPMailer\Exception;
use Throwable;

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

    private $Socket;
    private $ServerAddress;
    private $ServerPort;
    private $Timeout;

    public function __construct($Address, $Port = 25565, $Timeout = 2, $ResolveSRV = true)
    {
        $this->ServerAddress = $Address;
        $this->ServerPort = (int)$Port;
        $this->Timeout = (int)$Timeout;

        if ($ResolveSRV) {
            $this->ResolveSRV();
        }

        $this->Connect();
    }

    private function ResolveSRV()
    {
        if (\ip2long($this->ServerAddress) !== false) {
            return;
        }

        $Record = @\dns_get_record('_minecraft._tcp.' . $this->ServerAddress, DNS_SRV);

        if (empty($Record)) {
            return;
        }

        if (isset($Record[0]['target'])) {
            $this->ServerAddress = $Record[0]['target'];
        }

        if (isset($Record[0]['port'])) {
            $this->ServerPort = $Record[0]['port'];
        }
    }

    public function Connect()
    {

        $this->Socket = @fsockopen($this->ServerAddress, $this->ServerPort, $errno, $errstr, (float)$this->Timeout);

        if (!$this->Socket) {
            $this->Socket = null;

            throw new MinecraftPingException("Failed to connect or create a socket: $errno ($errstr)");
        }

        // Set Read/Write timeout
        \stream_set_timeout($this->Socket, $this->Timeout);
    }

    public function __destruct()
    {
        $this->Close();
    }

    public function Close()
    {
        if ($this->Socket !== null) {
            \fclose($this->Socket);

            $this->Socket = null;
        }
    }

    public function Query(): ?MinecraftPingEntity
    {
        $TimeStart = \microtime(true); // for read timeout purposes

        // See http://wiki.vg/Protocol (Status Ping)
        $data = "\x00"; // packet ID = 0 (varint)

        $data .= "\x04"; // Protocol version (varint)
        $data .= \pack('c', \strlen($this->ServerAddress)) . $this->ServerAddress; // Server (varint len + UTF-8 addr)
        $data .= \pack('n', $this->ServerPort); // Server port (unsigned short)
        $data .= "\x01"; // Next state: status (varint)

        $data = \pack('c', \strlen($data)) . $data; // prepend length of packet ID + data

        fwrite($this->Socket, $data . "\x01\x00"); // handshake followed by status ping

        $Length = $this->ReadVarInt(); // full packet length

        if ($Length < 10) {
            return null;
        }

        $this->ReadVarInt(); // packet type, in server ping it's 0

        $Length = $this->ReadVarInt(); // string length

        $data = "";
        while (\strlen($data) < $Length) {
            if (\microtime(true) - $TimeStart > $this->Timeout) {
                throw new MinecraftPingException('Server read timed out');
            }

            $Remainder = $Length - \strlen($data);
            $block = \fread($this->Socket, $Remainder); // and finally the json string
            // abort if there is no progress
            if (!$block) {
                throw new MinecraftPingException('Server returned too few data');
            }

            $data .= $block;
        }

        $data = \json_decode($data, true);

        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new MinecraftPingException('JSON parsing failed: ' . \json_last_error_msg());
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

    private function ReadVarInt()
    {
        $i = 0;
        $j = 0;

        while (true) {
            $k = @\fgetc($this->Socket);

            if ($k === FALSE) {
                return 0;
            }

            $k = \ord($k);

            $i |= ($k & 0x7F) << $j++ * 7;

            if ($j > 5) {
                throw new MinecraftPingException('VarInt too big');
            }

            if (($k & 0x80) != 128) {
                break;
            }
        }

        return $i;
    }

    public function QueryOldPre17()
    {
        \fwrite($this->Socket, "\xFE\x01");
        $Data = \fread($this->Socket, 512);
        $Len = \strlen($Data);

        if ($Len < 4 || $Data[0] !== "\xFF") {
            return FALSE;
        }

        $Data = \substr($Data, 3); // Strip packet header (kick message packet and short length)
        $Data = \iconv('UTF-16BE', 'UTF-8', $Data);

        // Are we dealing with Minecraft 1.4+ server?
        if ($Data[1] === "\xA7" && $Data[2] === "\x31") {
            $Data = \explode("\x00", $Data);

            return array(
                'HostName' => $Data[3],
                'Players' => (int)$Data[4],
                'MaxPlayers' => (int)$Data[5],
                'Protocol' => (int)$Data[1],
                'Version' => $Data[2]
            );
        }

        $Data = \explode("\xA7", $Data);

        return array(
            'HostName' => \substr($Data[0], 0, -1),
            'Players' => isset($Data[1]) ? (int)$Data[1] : 0,
            'MaxPlayers' => isset($Data[2]) ? (int)$Data[2] : 0,
            'Protocol' => 0,
            'Version' => '1.3'
        );
    }
}
