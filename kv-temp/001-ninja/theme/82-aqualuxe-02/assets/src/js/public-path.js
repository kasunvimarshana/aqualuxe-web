// Dynamically set webpack public path based on the script src of theme.js
// Ensures dynamic imports (e.g., hero chunk) load from the correct /assets/dist/js/ URL
try {
  var s = document.currentScript;
  if (!s) {
    var list = document.getElementsByTagName('script');
    s = list && list.length ? list[list.length - 1] : null;
  }
  if (s && s.src) {
    var src = s.src;
    // Point to dist root, not /js/, so chunkFilename 'js/...' doesn't double the segment
    // Handles optional query/hash after theme.js
    var base = src.replace(/\/js\/theme\.js(?:[?#].*)?$/, '/');
    // Ensure trailing slash
    if (base[base.length - 1] !== '/') base += '/';
    // eslint-disable-next-line no-undef
    __webpack_public_path__ = base;
  }
} catch (e) {
  // no-op
}
