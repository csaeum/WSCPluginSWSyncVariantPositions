# Prerequisites

**[Deutsch](README-Voraussetzungen.md)**

## System Requirements

### Shopware Version
- Shopware 6.5.x, 6.6.x, or 6.7.x
- **Important:** For other Shopware versions, always create a database backup first!

### PHP
- PHP 8.1 or higher
- Required PHP extensions:
  - PDO
  - pdo_mysql
  - json
  - mbstring
  - xml

### Database
- MySQL 5.7+ or MariaDB 10.3+

### Server Environment
- Access to Shopware CLI (bin/console)
- Write permissions in plugin directory

## Optional Requirements

### For Automatic Synchronization (Scheduled Tasks)
- Message Queue Consumer must be running or Cron job configured
- See [Shopware Documentation on Scheduled Tasks](https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/add-scheduled-task.html)

### For Admin UI
- Modern browsers (Chrome, Firefox, Safari, Edge - current versions)
- JavaScript must be enabled

## Permissions

### For CLI Usage
- Shell access to the server
- Permission to execute Symfony console commands

### For Admin UI
- Admin access to Shopware backend
- Permission to access plugin settings

## Checking Prerequisites

### Check PHP Version
```bash
php -v
```

### Check Shopware Version
```bash
bin/console --version
```

### Check Required PHP Extensions
```bash
php -m | grep -E 'pdo|mysql|json|mbstring|xml'
```

## Compatibility Matrix

| Shopware Version | Plugin Version | Status |
|------------------|----------------|--------|
| 6.5.x | 6.7.5.0+ | ✅ Tested |
| 6.6.x | 6.7.5.0+ | ✅ Tested |
| 6.7.x | 6.7.5.0+ | ✅ Tested |
| < 6.5.0 | - | ❌ Not supported |

## Recommended Environment

For optimal performance, we recommend:
- PHP 8.2+
- MySQL 8.0+ or MariaDB 10.6+
- OPcache enabled
- Memory limit at least 512MB

## Before Installation

1. **Create Backup**
   - Database backup
   - Plugin directory backup

2. **Update Shopware**
   - Ensure Shopware is up to date

3. **Clear Cache**
   ```bash
   bin/console cache:clear
   ```

## Next Steps

If all prerequisites are met:
➡️ [Installation](README-Installation.en.md)
