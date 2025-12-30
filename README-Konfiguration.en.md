# Configuration

**[Deutsch](README-Konfiguration.md)**

## Plugin Configuration

You can find the plugin settings at:
**Settings** → **System** → **Plugins** → **WSC Sync Variant Positions** → **Configuration**

### Scheduled Task Settings

#### Enable Scheduled Sync
- **Type:** Boolean (Yes/No)
- **Default:** No
- **Description:** Enables automatic background synchronization

**When to enable:**
- ✅ With regular property imports
- ✅ With frequent variant generations
- ✅ When manual sync is forgotten

**When to disable:**
- ❌ With infrequent changes
- ❌ When manual sync is preferred
- ❌ With performance issues

#### Sync Interval (seconds)
- **Type:** Integer
- **Default:** 3600 (1 hour)
- **Minimum:** 60 (1 minute)
- **Recommended:** 3600-86400

**Common Values:**
- `300` = 5 minutes (very frequent)
- `1800` = 30 minutes (frequent)
- `3600` = 1 hour (default)
- `7200` = 2 hours
- `43200` = 12 hours
- `86400` = 24 hours (daily)

**Note:** The interval is automatically updated when you save the configuration.

### Synchronization Behavior

#### Batch Size
- **Type:** Integer
- **Default:** 500
- **Recommended:** 100-1000
- **Description:** Number of records per batch update

**Optimization:**
- **Small shops (<1000 variants):** 100-250
- **Medium shops (1000-10000):** 500 (default)
- **Large shops (>10000):** 750-1000

**For Performance Issues:**
- Decrease batch size (100-250)
- Increase interval (7200+)

## CLI Usage

### Basic Commands

#### Preview (Dry-Run)
```bash
bin/console wsc:sync-variant-positions --dry-run
```
Shows what would be changed without actually changing.

#### Full Synchronization
```bash
bin/console wsc:sync-variant-positions
```
Synchronizes all products.

#### Product-specific Synchronization
```bash
bin/console wsc:sync-variant-positions --product-id=018d9a5c3a4b70b8a8f8c2e8e8f8e8f8
```
Synchronizes only a specific product.

#### With Verbose Output
```bash
bin/console wsc:sync-variant-positions --dry-run -v
```
Shows the first 20 changes in detail.

### Options Reference

| Option | Short | Type | Description |
|--------|-------|------|-------------|
| `--dry-run` | `-d` | Flag | Preview only, no changes |
| `--product-id` | - | String | Product UUID (hex format) |
| `-v` | | Flag | Verbose output |
| `-vv` | | Flag | Very verbose |
| `-vvv` | | Flag | Debug output |

### Automation via Cron

Add a cron job for regular synchronization:

```bash
# Daily at 3:00 AM
0 3 * * * cd /path/to/shopware && bin/console wsc:sync-variant-positions >> /var/log/shopware/sync-variants.log 2>&1

# Every hour
0 * * * * cd /path/to/shopware && bin/console wsc:sync-variant-positions >> /var/log/shopware/sync-variants.log 2>&1

# Every Sunday at 2:00 AM
0 2 * * 0 cd /path/to/shopware && bin/console wsc:sync-variant-positions >> /var/log/shopware/sync-variants.log 2>&1
```

## Admin UI Usage

### Access
**Settings** → **Variant Position Sync**

### Features

#### 1. Preview (Dry-Run)
- Enable switch
- Click **Show Preview**
- Results are displayed:
  - Number of positions to update
  - Number of unchanged positions
  - Number of skipped positions

#### 2. Synchronization
- Disable Dry-Run
- Optional: Enter Product ID
- Click **Synchronize Now**
- Wait for results
- On success: Green success message

#### 3. Product Filter
- Product ID format: `018d9a5c3a4b70b8a8f8c2e8e8f8e8f8`
- Synchronizes only this product
- Useful for tests or specific products

### Product Finder

**Find Product ID in Admin:**
1. **Catalogues** → **Products**
2. Open product
3. Check URL: `.../detail/018d9a5c3a4b70b8a8f8c2e8e8f8e8f8`
4. Last 32 characters = Product ID

**Find Product ID via SQL:**
```sql
SELECT LOWER(HEX(id)) as product_id, product_number, name
FROM product
WHERE product_number = 'SW10001';
```

## Scheduled Task Management

### Check Task Status
```bash
bin/console scheduled-task:list
```

### Execute Task Manually
```bash
# Via Shopware Admin with FroshTools Plugin
# Settings → System → Scheduled Tasks → WSC Sync Variant Positions → Execute

# Via CLI (requires Message Consumer)
bin/console messenger:consume async --limit=1
```

### View Task Logs
```bash
# Shopware Logs
tail -f var/log/dev.log | grep "variant position sync"

# System Logs (if configured)
tail -f /var/log/shopware/scheduled-tasks.log
```

### Start Message Consumer
```bash
# Foreground (for testing)
bin/console messenger:consume async

# Background (production)
bin/console messenger:consume async --time-limit=3600 &

# With Supervisor (recommended)
# /etc/supervisor/conf.d/shopware-messenger.conf
[program:shopware-messenger]
command=/var/www/shopware/bin/console messenger:consume async
user=www-data
autostart=true
autorestart=true
```

## Performance Optimization

### Large Datasets (>10,000 Products)

1. **Adjust Batch Size**
   - Increase to 750-1000

2. **Increase Scheduled Task Interval**
   - At least 7200 seconds (2 hours)

3. **Use Time Windows**
   - Set up cron job for nighttime
   ```bash
   0 2 * * * cd /path/to/shopware && bin/console wsc:sync-variant-positions
   ```

4. **Message Consumer Limits**
   ```bash
   bin/console messenger:consume async --memory-limit=512M --time-limit=3600
   ```

### Database Optimization

```sql
-- Check index status
SHOW INDEX FROM product_configurator_setting;
SHOW INDEX FROM property_group_option_translation;

-- Update table statistics
ANALYZE TABLE product_configurator_setting;
ANALYZE TABLE property_group_option_translation;
```

## Error Handling

### Enable Logging
```yaml
# config/packages/dev/monolog.yaml
monolog:
    channels: ['wsc_sync']
    handlers:
        wsc_sync:
            type: stream
            path: "%kernel.logs_dir%/wsc-sync.log"
            level: info
            channels: ["wsc_sync"]
```

### Common Issues

#### Issue: Sync takes very long
**Solution:**
- Increase batch size (750-1000)
- Check database indexes
- Increase memory limit

#### Issue: Timeouts with Scheduled Task
**Solution:**
- Increase Message Consumer memory limit
- Extend interval
- Decrease batch size

#### Issue: No changes visible
**Solution:**
```bash
# Clear cache
bin/console cache:clear

# Clear HTTP cache
bin/console http:cache:clear
```

## Best Practices

### Development
- ✅ Always test with `--dry-run`
- ✅ Database backup before large sync
- ✅ Verbose mode for debugging

### Production
- ✅ Scheduled Task for automation
- ✅ Monitor task logs
- ✅ Regular performance checks
- ✅ Message Consumer via Supervisor

### Testing
```bash
# Test with single product
bin/console wsc:sync-variant-positions --product-id=XXX --dry-run -v

# Test with all products (preview)
bin/console wsc:sync-variant-positions --dry-run

# Production run
bin/console wsc:sync-variant-positions
```

## Advanced Configuration

### Custom Batch Size via CLI
The batch size is currently fixed in plugin configuration. A custom command can be created if needed.

### Notifications on Errors
Can be implemented via Shopware's logging + external monitoring (e.g., Sentry).

### Multi-Sales-Channel Setup
The plugin works channel-independent as it accesses the database directly.

## Support

For issues:
- 📧 [support@web-seo-consulting.eu](mailto:support@web-seo-consulting.eu)
- 💬 [GitHub Discussions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/discussions)
- 🐛 [GitHub Issues](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/issues)
