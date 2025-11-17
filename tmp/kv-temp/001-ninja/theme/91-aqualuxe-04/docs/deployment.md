# Deployment Guide

This guide outlines the steps for deploying the AquaLuxe theme to a live server.

## Pre-deployment Checklist

1.  **Production Build:** Ensure you have created a production build of your assets by running `npm run prod` in the theme directory. This will minify and optimize your CSS and JavaScript files.
2.  **Remove Development Files:** Make sure that development files and directories like `node_modules/`, `assets/src/`, `webpack.mix.js`, `package.json`, etc., are not uploaded to the production server. You can use the `.gitignore` file as a reference for which files to exclude.
3.  **Database Backup:** Always back up your production database before deploying major changes.

## Deployment Methods

### 1. FTP/SFTP

This is the most straightforward method for deploying a theme.

1.  Connect to your server using an FTP/SFTP client (e.g., FileZilla).
2.  Navigate to the `wp-content/themes/` directory on your server.
3.  Upload the entire `aqualuxe` theme directory (excluding development files).
4.  If you are updating the theme, overwrite the existing files.

### 2. Git-based Deployment

If you are using Git for version control, you can set up a more automated deployment workflow.

1.  **Set up a Git remote** on your server.
2.  **Push your changes** to the production remote.
3.  Use a **post-receive hook** on the server to automatically pull the latest changes into your `wp-content/themes/aqualuxe` directory.

There are many services that can help automate this process, such as DeployHQ, Beanstalk, or custom CI/CD pipelines with GitHub Actions or GitLab CI.

## Post-deployment Steps

1.  **Check File Permissions:** Ensure that the file and directory permissions on your server are set correctly for security. Directories should typically be `755` and files `644`.
2.  **Clear Caching:** If you are using a caching plugin or a server-side cache, clear the cache to ensure that your changes are visible.
3.  **Test:** Thoroughly test your live site to make sure everything is working as expected.
