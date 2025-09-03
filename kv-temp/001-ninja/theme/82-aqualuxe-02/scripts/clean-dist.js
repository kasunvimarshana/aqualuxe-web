/* Remove stale hashed hero chunks to avoid legacy files accumulating in dist */
const fs = require('fs');
const path = require('path');

const distJs = path.resolve(__dirname, '..', 'assets', 'dist', 'js');
try {
  if (!fs.existsSync(distJs)) process.exit(0);
  const files = fs.readdirSync(distJs);
  const targets = files.filter(f => /^hero\.[a-f0-9]{8,}.*\.js(\.LICENSE\.txt)?$/.test(f));
  if (targets.length > 8) {
    // Keep the newest 4 hero chunks by mtime; remove older ones
    const withTime = targets.map(f => ({
      name: f,
      time: fs.statSync(path.join(distJs, f)).mtimeMs
    })).sort((a,b) => b.time - a.time);
    const toRemove = withTime.slice(4).map(x => x.name);
    for (const f of toRemove) {
      try { fs.unlinkSync(path.join(distJs, f)); } catch (e) {}
    }
  }
} catch (e) {
  // ignore
}
