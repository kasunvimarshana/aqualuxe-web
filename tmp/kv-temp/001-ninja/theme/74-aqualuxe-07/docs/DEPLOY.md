# Build & Deploy

## Requirements
- Node.js 18+ (20 recommended)
- PHP 8.0+
- WordPress 6.6+
- WooCommerce (optional)

## Build assets
```cmd
npm ci
npm run build
```
This generates `assets/dist/*` and `mix-manifest.json`. The theme enqueues only these built files.

## Install theme
1. Copy the `aqualuxe` directory into `wp-content/themes/`.
2. Activate in wp-admin → Appearance → Themes.
3. Optional: Install WooCommerce.

## Demo content
- Go to Appearance → AquaLuxe Setup and click "Import Demo". This sets a homepage and basic menus.

## Production deployment
- Commit the built `assets/dist/` to your release branch or build in CI and package as a zip.
- Ensure file permissions: 644 files, 755 directories.
- Disable debug display on production.

## CI
- GitHub Actions workflow runs Node build and PHP lint. Extend for PHPUnit or WP integration tests as desired.

## Toggle modules
Add to a site-specific plugin or `functions.php`:
```php
add_filter('aqualuxe_modules_enabled', function($mods){
  $mods['auctions'] = false;
  $mods['tradeins'] = true;
  return $mods;
});
```

## Create distributable zip (Windows PowerShell)
```powershell
# From the theme parent folder containing aqualuxe/
Compress-Archive -Path aqualuxe -DestinationPath aqualuxe-1.0.0.zip -Force
```
