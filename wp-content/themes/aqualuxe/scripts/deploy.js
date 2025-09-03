#!/usr/bin/env node

/**
 * AquaLuxe Theme Deployment Script
 * 
 * Automates the deployment process for the theme to various environments.
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

class ThemeDeployer {
    constructor() {
        this.themeDir = path.resolve(__dirname, '..');
        this.config = this.loadConfig();
    }

    loadConfig() {
        const configPath = path.join(this.themeDir, 'deploy.config.json');
        
        if (fs.existsSync(configPath)) {
            return JSON.parse(fs.readFileSync(configPath, 'utf8'));
        }
        
        // Default configuration
        return {
            environments: {
                staging: {
                    host: 'staging.example.com',
                    path: '/var/www/html/wp-content/themes/aqualuxe',
                    excludes: ['node_modules', '.git', 'scripts']
                },
                production: {
                    host: 'production.example.com',
                    path: '/var/www/html/wp-content/themes/aqualuxe',
                    excludes: ['node_modules', '.git', 'scripts', 'assets/src']
                }
            },
            backup: {
                enabled: true,
                path: './backups'
            }
        };
    }

    async deploy(environment = 'staging') {
        console.log(`🚀 Starting deployment to ${environment}...`);
        
        try {
            // Pre-deployment checks
            await this.preDeploymentChecks();
            
            // Build assets
            await this.buildAssets();
            
            // Create backup
            if (this.config.backup.enabled) {
                await this.createBackup(environment);
            }
            
            // Deploy files
            await this.deployFiles(environment);
            
            // Post-deployment tasks
            await this.postDeploymentTasks(environment);
            
            console.log(`✅ Deployment to ${environment} completed successfully!`);
            
        } catch (error) {
            console.error(`❌ Deployment failed:`, error);
            process.exit(1);
        }
    }

    async preDeploymentChecks() {
        console.log('🔍 Running pre-deployment checks...');
        
        // Check if assets are built
        const distDir = path.join(this.themeDir, 'assets/dist');
        if (!fs.existsSync(distDir)) {
            throw new Error('Assets not built. Run "npm run build" first.');
        }
        
        // Check for required files
        const requiredFiles = [
            'style.css',
            'index.php',
            'functions.php'
        ];
        
        for (const file of requiredFiles) {
            const filePath = path.join(this.themeDir, file);
            if (!fs.existsSync(filePath)) {
                throw new Error(`Required file missing: ${file}`);
            }
        }
        
        console.log('✅ Pre-deployment checks passed');
    }

    async buildAssets() {
        console.log('🔨 Building production assets...');
        
        try {
            process.chdir(this.themeDir);
            execSync('npm run build', { stdio: 'inherit' });
            console.log('✅ Assets built successfully');
        } catch (error) {
            throw new Error('Asset build failed');
        }
    }

    async createBackup(environment) {
        console.log('💾 Creating backup...');
        
        const backupDir = path.join(this.themeDir, this.config.backup.path);
        if (!fs.existsSync(backupDir)) {
            fs.mkdirSync(backupDir, { recursive: true });
        }
        
        const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
        const backupName = `aqualuxe-${environment}-${timestamp}.tar.gz`;
        const backupPath = path.join(backupDir, backupName);
        
        try {
            execSync(`tar -czf "${backupPath}" --exclude-from=.deployignore .`, {
                cwd: this.themeDir,
                stdio: 'inherit'
            });
            console.log(`✅ Backup created: ${backupName}`);
        } catch (error) {
            console.warn('⚠️ Backup creation failed, continuing deployment...');
        }
    }

    async deployFiles(environment) {
        console.log(`📤 Deploying files to ${environment}...`);
        
        const config = this.config.environments[environment];
        if (!config) {
            throw new Error(`Environment "${environment}" not configured`);
        }
        
        // Create rsync command
        const excludes = config.excludes.map(exclude => `--exclude="${exclude}"`).join(' ');
        const rsyncCommand = `rsync -avz --delete ${excludes} ./ ${config.host}:${config.path}/`;
        
        try {
            execSync(rsyncCommand, {
                cwd: this.themeDir,
                stdio: 'inherit'
            });
            console.log('✅ Files deployed successfully');
        } catch (error) {
            throw new Error('File deployment failed');
        }
    }

    async postDeploymentTasks(environment) {
        console.log('🔧 Running post-deployment tasks...');
        
        const config = this.config.environments[environment];
        
        // Clear caches (if configured)
        if (config.clearCache) {
            try {
                const cacheCommand = `ssh ${config.host} "cd ${config.path} && wp cache flush"`;
                execSync(cacheCommand, { stdio: 'inherit' });
                console.log('✅ Cache cleared');
            } catch (error) {
                console.warn('⚠️ Cache clear failed, continuing...');
            }
        }
        
        // Update database (if configured)
        if (config.updateDatabase) {
            try {
                const dbCommand = `ssh ${config.host} "cd ${config.path} && wp core update-db"`;
                execSync(dbCommand, { stdio: 'inherit' });
                console.log('✅ Database updated');
            } catch (error) {
                console.warn('⚠️ Database update failed, continuing...');
            }
        }
        
        console.log('✅ Post-deployment tasks completed');
    }

    generateDeployIgnore() {
        const deployIgnoreContent = `# Deployment ignore file
# Add patterns of files/folders to exclude from deployment

# Development files
node_modules/
.git/
.gitignore
.env
.env.*

# Source files (keep for staging, exclude for production)
assets/src/
webpack.config.js
tailwind.config.js
package.json
package-lock.json

# Scripts and documentation
scripts/
README.md
ARCHITECTURE_SUMMARY.md
ENTERPRISE_THEME_SUMMARY.md

# Logs and temporary files
*.log
.DS_Store
Thumbs.db
*.tmp
*.temp

# Backup files
backups/
`;
        
        const deployIgnorePath = path.join(this.themeDir, '.deployignore');
        fs.writeFileSync(deployIgnorePath, deployIgnoreContent);
        console.log('✅ Created .deployignore file');
    }

    generateDeployConfig() {
        const configContent = {
            environments: {
                staging: {
                    host: 'user@staging.example.com',
                    path: '/var/www/html/wp-content/themes/aqualuxe',
                    excludes: ['node_modules', '.git', 'scripts', 'backups'],
                    clearCache: true,
                    updateDatabase: false
                },
                production: {
                    host: 'user@production.example.com',
                    path: '/var/www/html/wp-content/themes/aqualuxe',
                    excludes: ['node_modules', '.git', 'scripts', 'assets/src', 'backups'],
                    clearCache: true,
                    updateDatabase: true
                }
            },
            backup: {
                enabled: true,
                path: './backups',
                keepDays: 30
            },
            notifications: {
                slack: {
                    enabled: false,
                    webhook: 'https://hooks.slack.com/services/YOUR/SLACK/WEBHOOK'
                },
                email: {
                    enabled: false,
                    recipients: ['admin@example.com']
                }
            }
        };
        
        const configPath = path.join(this.themeDir, 'deploy.config.json');
        fs.writeFileSync(configPath, JSON.stringify(configContent, null, 2));
        console.log('✅ Created deploy.config.json file');
    }

    async rollback(environment, backupFile) {
        console.log(`🔄 Rolling back ${environment} to ${backupFile}...`);
        
        const config = this.config.environments[environment];
        if (!config) {
            throw new Error(`Environment "${environment}" not configured`);
        }
        
        const backupPath = path.join(this.themeDir, this.config.backup.path, backupFile);
        if (!fs.existsSync(backupPath)) {
            throw new Error(`Backup file not found: ${backupFile}`);
        }
        
        try {
            // Extract backup to temporary directory
            const tempDir = `/tmp/aqualuxe-rollback-${Date.now()}`;
            execSync(`mkdir -p ${tempDir} && tar -xzf "${backupPath}" -C ${tempDir}`);
            
            // Deploy the backup
            const rsyncCommand = `rsync -avz --delete ${tempDir}/ ${config.host}:${config.path}/`;
            execSync(rsyncCommand, { stdio: 'inherit' });
            
            // Clean up
            execSync(`rm -rf ${tempDir}`);
            
            console.log('✅ Rollback completed successfully');
        } catch (error) {
            throw new Error('Rollback failed');
        }
    }
}

// Command line interface
if (require.main === module) {
    const args = process.argv.slice(2);
    const command = args[0];
    const deployer = new ThemeDeployer();
    
    switch (command) {
        case 'deploy':
            const environment = args[1] || 'staging';
            deployer.deploy(environment);
            break;
            
        case 'rollback':
            const rollbackEnv = args[1];
            const backupFile = args[2];
            if (!rollbackEnv || !backupFile) {
                console.error('Usage: node deploy.js rollback <environment> <backup-file>');
                process.exit(1);
            }
            deployer.rollback(rollbackEnv, backupFile);
            break;
            
        case 'init':
            deployer.generateDeployConfig();
            deployer.generateDeployIgnore();
            console.log('🎉 Deployment configuration initialized!');
            console.log('Edit deploy.config.json to configure your environments.');
            break;
            
        default:
            console.log(`
AquaLuxe Theme Deployment Script

Usage:
  node deploy.js init                           Initialize deployment configuration
  node deploy.js deploy [environment]          Deploy to environment (default: staging)
  node deploy.js rollback <env> <backup-file>  Rollback to previous backup

Examples:
  node deploy.js init
  node deploy.js deploy staging
  node deploy.js deploy production
  node deploy.js rollback production aqualuxe-production-2024-01-01.tar.gz
            `);
            break;
    }
}

module.exports = ThemeDeployer;
