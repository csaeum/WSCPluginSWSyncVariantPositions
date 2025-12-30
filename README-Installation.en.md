# Installation

**[Deutsch](README-Installation.md)**

## Check Prerequisites

Make sure all [prerequisites](README-Voraussetzungen.en.md) are met.

## Installation Methods

### Method 1: Via Shopware Store (Recommended)

1. Log in to Shopware Admin
2. **Settings** → **System** → **Plugins**
3. Search for "WSC Sync Variant Positions"
4. Click **Install**
5. Click **Activate**
6. Clear cache (optional)

### Method 2: Manual Installation via ZIP

1. **Download Plugin**
   - From [GitHub Releases](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/releases/latest)
   - Download `WSCPluginSWSyncVariantPositions.zip`

2. **Upload Plugin**
   - In Shopware Admin: **Settings** → **System** → **Plugins**
   - Click **Upload plugin**
   - Select ZIP file

3. **Activate Plugin**
   ```bash
   bin/console plugin:refresh
   bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
   bin/console cache:clear
   ```

### Method 3: Manual Installation via Composer (Developers)

1. **Create Plugin Directory**
   ```bash
   mkdir -p custom/plugins/WSCPluginSWSyncVariantPositions
   ```

2. **Copy Files**
   ```bash
   # Clone the repository
   git clone https://github.com/csaeum/WSCPluginSWSyncVariantPositions.git custom/plugins/WSCPluginSWSyncVariantPositions
   ```

3. **Install Plugin**
   ```bash
   bin/console plugin:refresh
   bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
   bin/console cache:clear
   ```

## After Installation

### 1. Compile Admin JavaScript (only for manual installation)

```bash
# Shopware 6.5+
bin/console bundle:dump
bin/build-administration.sh

# Alternative (if script not available)
./bin/build-js.sh
```

### 2. Register Scheduled Task

```bash
bin/console scheduled-task:register
```

### 3. Function Test

#### CLI Test
```bash
bin/console wsc:sync-variant-positions --dry-run
```

**Expected Output:**
```
Configurator-Positionen synchronisieren
========================================

 ! [NOTE] Dry-Run Modus aktiv - es werden keine Änderungen vorgenommen

Lade Product-Configurator-Settings...
======================================

Ergebnis
========

Unverändert: X
Übersprungen (ohne Option): Y
Zu aktualisieren: Z

 ! [WARNING] Dry-Run beendet. Führe den Befehl ohne --dry-run aus, um die Änderungen anzuwenden.
```

#### Admin UI Test
1. **Settings** → **Variant Position Sync**
2. Page should load without errors
3. Enable Dry-Run and click **Show Preview**
4. Results should be displayed

## Troubleshooting

### Issue: Plugin not showing in plugin list

**Solution:**
```bash
bin/console plugin:refresh
```

### Issue: Admin UI shows errors

**Solution:**
```bash
bin/console cache:clear
bin/build-administration.sh
```

### Issue: "Command not found"

**Solution:**
```bash
# Clear cache
bin/console cache:clear

# Reinstall plugin
bin/console plugin:uninstall WSCPluginSWSyncVariantPositions
bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
```

### Issue: Scheduled Task not running

**Solution:**
```bash
# Re-register task
bin/console scheduled-task:register

# Check message consumer status
bin/console messenger:stats

# Start message consumer (if not running)
bin/console messenger:consume async --time-limit=60 &
```

### Issue: "Class not found" error

**Solution:**
```bash
# Regenerate composer autoload
composer dump-autoload

# Clear cache
bin/console cache:clear
```

## Uninstallation

### Deactivate Plugin
```bash
bin/console plugin:deactivate WSCPluginSWSyncVariantPositions
```

### Uninstall Plugin
```bash
bin/console plugin:uninstall WSCPluginSWSyncVariantPositions
```

### Remove Plugin Files
```bash
rm -rf custom/plugins/WSCPluginSWSyncVariantPositions
```

**Note:** Plugin configuration and scheduled task entries are automatically removed during uninstallation.

## Update

### Via Shopware Store
Updates are automatically displayed in the plugin manager.

### Manual Update
1. Deactivate plugin
2. Download new version
3. Replace old files
4. Update plugin:
   ```bash
   bin/console plugin:refresh
   bin/console plugin:update WSCPluginSWSyncVariantPositions
   bin/console plugin:activate WSCPluginSWSyncVariantPositions
   bin/console cache:clear
   bin/build-administration.sh
   ```

## Next Steps

After successful installation:
➡️ [Configuration](README-Konfiguration.en.md)
