# Deployment

CI builds the theme and publishes a zip artifact.

GitHub Actions workflow: `.github/workflows/build.yml`.
- Installs Node
- Runs `npm ci && npm run build`
- Zips the theme folder excluding dev files

Manual:
1. Build: `npm run build`
2. Zip the theme directory (exclude node_modules, assets/src)
3. Upload to your WordPress server or marketplace.
