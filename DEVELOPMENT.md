# AquaLuxe Development Environment Setup

## Quick Start with Docker

1. **Clone the repository:**
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git
   cd aqualuxe-web
   ```

2. **Start the development environment:**
   ```bash
   docker-compose up -d
   ```

3. **Install WordPress:**
   ```bash
   docker-compose exec wp-cli wp core install \
     --url=http://localhost \
     --title="AquaLuxe" \
     --admin_user=admin \
     --admin_password=admin \
     --admin_email=admin@aqualuxe.local
   ```

4. **Activate the theme:**
   ```bash
   docker-compose exec wp-cli wp theme activate aqualuxe
   ```

## Development Commands

### Asset Building
```bash
# Install dependencies
npm install

# Development build
npm run development

# Watch for changes
npm run watch

# Production build
npm run production
```

### Docker Commands
```bash
# Start all services
docker-compose up -d

# Stop all services
docker-compose down

# View logs
docker-compose logs -f

# Access WordPress CLI
docker-compose exec wp-cli wp --info

# Access database
docker-compose exec mysql mysql -u aqualuxe_user -p aqualuxe_db
```

## Services & Ports

- **WordPress:** http://localhost
- **phpMyAdmin:** http://localhost:8080 (development profile)
- **MailHog:** http://localhost:8025 (development profile)
- **MySQL:** localhost:3306
- **Redis:** localhost:6379

## Default Credentials

- **WordPress Admin:** admin / admin
- **Database:** aqualuxe_user / aqualuxe_pass
- **Root MySQL:** root / rootpassword

## Environment Profiles

### Development
```bash
docker-compose --profile development up -d
```
Includes: phpMyAdmin, MailHog for email testing

### Production
```bash
docker-compose up -d
```
Minimal services for production deployment

## File Structure

```
aqualuxe-web/
├── docker/                 # Docker configuration
│   ├── nginx/              # Nginx configuration
│   ├── php/                # PHP configuration
│   └── mysql/              # MySQL initialization
├── assets/
│   ├── src/                # Source assets
│   └── dist/               # Compiled assets
├── core/                   # Core theme classes
├── modules/                # Feature modules
├── inc/                    # Theme includes
├── templates/              # Template files
└── docker-compose.yml      # Docker orchestration
```

## Troubleshooting

### Port Conflicts
If you have services running on ports 80, 3306, or 6379, either stop them or modify the ports in `docker-compose.yml`.

### Permission Issues
```bash
# Fix file permissions
sudo chown -R $USER:$USER .
chmod -R 755 .
```

### Reset Everything
```bash
# Remove all containers and volumes
docker-compose down -v
docker system prune -f

# Start fresh
docker-compose up -d
```

## Module Development

Each module is self-contained in `modules/{module-name}/`:
- `class-module.php` - Main module class
- `assets/` - Module-specific assets
- `templates/` - Module templates

Enable/disable modules via WordPress Customizer → Module Settings.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test with `npm run lint` and `npm run test`
5. Submit a pull request

## Support

For development support, create an issue in the GitHub repository.