function getDeviceInfo() {
  const parser = new UAParser();
  const result = parser.getResult();

  return {
    device: result.device.type || 'desktop',
    browser: [result.browser.name, result.browser.version].filter(Boolean).join(' ')
  };
}

function getIp() {
  return fetch('https://api.ipify.org?format=json')
    .then(response => {
      if (!response.ok) {
        throw new Error(`ipify HTTP ${response.status}`);
      }
      return response.json();
    })
    .then(data => data.ip);
}

function getCountry(ip, apiKey) {
  if (!apiKey) {
    console.warn('IPGEOLOCATION_API_KEY is empty');
    return Promise.resolve('');
  }

  return fetch(`https://api.ipgeolocation.io/ipgeo?apiKey=${encodeURIComponent(apiKey)}&ip=${encodeURIComponent(ip)}`)
    .then(response => {
      if (!response.ok) {
        throw new Error(`ipgeolocation HTTP ${response.status}`);
      }
      return response.json();
    })
    .then(data => data.country_name || data.location?.country_name || '');
}

function sendVisitData() {
  const apiKey = window.IPGEOLOCATION_API_KEY;
  const url = window.location.href;

  getIp()
    .then(ip => {
      const deviceInfo = getDeviceInfo();

      return getCountry(ip, apiKey)
        .catch(error => {
          console.error('Country lookup error:', error);
          return '';
        })
        .then(country => ({ ip, country, deviceInfo }));
    })
    .then(({ ip, country, deviceInfo }) => {
      const data = {
        ip: ip,
        pais: country,
        dispositivo: deviceInfo.device,
        navegador: deviceInfo.browser,
        url: url
      };

      console.log('Visit data:', data);

      const formData = new FormData();
      formData.append('ip', data.ip);
      formData.append('pais', data.pais);
      formData.append('dispositivo', data.dispositivo);
      formData.append('navegador', data.navegador);
      formData.append('url', data.url);

      // Relative URL works in both localhost and production.
      return fetch('/process/actions/action.php', {
        method: 'POST',
        body: formData
      });
    })
    .then(async response => {
      const raw = await response.text();

      if (!response.ok) {
        throw new Error(`action.php HTTP ${response.status}: ${raw}`);
      }

      try {
        const parsed = JSON.parse(raw);
        console.log('Response from server:', parsed);
      } catch (error) {
        // action.php may emit a PHP warning/notices before or instead of JSON.
        console.warn('action.php returned non-JSON output:', raw);
      }
    })
    .catch(error => {
      console.error('Visit tracking error:', error);
    });
}

document.addEventListener('DOMContentLoaded', sendVisitData);
