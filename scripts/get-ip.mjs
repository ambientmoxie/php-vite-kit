import os from 'os';

// Dynamically find IP adress
export function getLocalIp() {
  const interfaces = os.networkInterfaces();
  for (const name in interfaces) {
    for (const iface of interfaces[name]) {
      if (iface.family === 'IPv4' && !iface.internal) {
        return iface.address;
      }
    }
  }
  return 'localhost';
}

// Runs only if called from package.json
if (import.meta.url === `file://${process.argv[1]}`) {
  console.log(getLocalIp());
}
