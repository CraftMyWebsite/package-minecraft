const checkCMWLConfig = async (serverId) => {
    let url = await fetch(`servers/cmwl/test/${serverId}`)
    let jsonData = await url.json()

    if (jsonData === "true") {
        console.log(`Good.`)

    } else {
        console.log("Nop")
    }
}