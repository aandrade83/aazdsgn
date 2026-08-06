

//// MODIFY

window.onload = (event) => {
  
   console.log('The page has fully loaded');
   
   //   load_main();


}



function getDeviceInfo() {
        const parser = new UAParser();
        const result = parser.getResult();
        return {
            device: result.device.type || "desktop",
            browser: result.browser.name + " " + result.browser.version
        };
    }

    function getIp() {
        return fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => data.ip);
    }

    function getCountry(ip, apiKey) {
        return fetch(`https://api.ipgeolocation.io/ipgeo?apiKey=${apiKey}&ip=${ip}`)
            .then(response => response.json())
            .then(data => data.country_name);
    }

    function sendVisitData() {
        const apiKey = window.IPGEOLOCATION_API_KEY;
        getIp().then(ip => {
            const deviceInfo = getDeviceInfo();
            getCountry(ip, apiKey).then(country => {
                const data = {
                    ip: ip,
                    pais: country,
                    dispositivo: deviceInfo.device,
                    navegador: deviceInfo.browser
                };

                fetch('https://aazdsgn.com/process/actions/action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', sendVisitData);

