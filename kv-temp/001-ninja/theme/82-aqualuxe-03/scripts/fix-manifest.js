/*
 Ensure mix-manifest.json is not empty in CI or flaky environments.
 If empty, populate with expected entries for theme.js and CSS.
*/
const fs = require('fs');
const path = require('path');

const dist = path.resolve(__dirname, '..', 'assets', 'dist');
const manifestPath = path.join(dist, 'mix-manifest.json');

try {
  if (!fs.existsSync(manifestPath)) process.exit(0);
  const raw = fs.readFileSync(manifestPath, 'utf8').trim();
  let data = {};
  if (raw && raw !== '{}' && raw !== '') {
    try { data = JSON.parse(raw); } catch(e) {}
  }
  const ensure = (src, out) => {
    if (data[src]) return;
    const candidate = path.join(dist, out);
    if (fs.existsSync(candidate)) {
      data[src] = `/${out}`;
    }
  };
  ensure('/js/theme.js', 'js/theme.js');
  ensure('/css/theme.css', 'css/theme.css');
  ensure('/css/screen.css', 'css/screen.css');

  fs.writeFileSync(manifestPath, JSON.stringify(data, null, 2));
  process.exit(0);
} catch (e) {
  console.error('fix-manifest error:', e);
  process.exit(0);
}
