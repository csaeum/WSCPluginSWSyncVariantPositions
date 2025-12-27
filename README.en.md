# WSC Sync Variant Positions

**[Deutsch](README.md) | [Français](README.fr.md)**

Synchronizes the variant selection positions (`product_configurator_setting.position`) with the property value positions from `property_group_option_translation.position` in Shopware 6.

## Compatibility

- Shopware 6.5.x, 6.6.x, 6.7.x
- **Important:** For other Shopware versions, always create a database backup first!

## Problem & Solution

### The Problem

When importing properties or managing them manually, positions are stored in the `property_group_option_translation` table. After generating variants, these positions are **not** automatically transferred to the configurator settings (`product_configurator_setting`), which control the order of variant selection on the product.

### The Solution

This plugin synchronizes the positions retroactively with a simple console command.

## How It Works

The plugin combines SQL for reading data (safe) with Shopware DAL for updates.

### Technical Process

1. **Reading Data (SQL SELECT):**
   - Compares `product_configurator_setting.position` with `property_group_option_translation.position`
   - Linking via `property_group_option_id`
   - For multiple translations, `MAX(position)` is used

2. **Update (DAL):**
   - Only changed positions are updated
   - Uses `product_configurator_setting.repository` for safe updates
   - Batch updates for better performance (500 entries per batch)

## Installation

### Requirements

- Shopware 6.5.x, 6.6.x, or 6.7.x
- PHP 8.1 or higher
- Composer (optional, for manual installation)

### Installation via Shopware Store

1. Search for and install the plugin in the Shopware Store
2. Activate the plugin
3. Clear cache

### Manual Installation

1. Download and extract the ZIP file to `custom/plugins/WSCPluginSWSyncVariantPositions/`

2. Install and activate the plugin:
```bash
bin/console plugin:refresh
bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
bin/console cache:clear
```

## Usage

### Dry-Run (Preview Without Changes)

Shows which positions would be changed without actually making any changes:

```bash
bin/console wsc:sync-variant-positions --dry-run
```

### Execute Synchronization

Synchronize all variant positions:

```bash
bin/console wsc:sync-variant-positions
```

### Synchronize Single Product Only

Synchronize a single product by UUID:

```bash
bin/console wsc:sync-variant-positions --product-id=018d9a5c3a4b70b8a8f8c2e8e8f8e8f8
```

### Verbose Output

With the `-v` option, more details are displayed (shows the first 20 changes):

```bash
bin/console wsc:sync-variant-positions --dry-run -v
```

### After Synchronization

Clear cache (optional, usually not necessary):

```bash
bin/console cache:clear
```

## Command Line Options

| Option | Short | Description |
|--------|-------|-------------|
| `--dry-run` | `-d` | Only shows what would be changed (no changes) |
| `--product-id=UUID` |  | Optional: Synchronize only a specific product |
| `-v` | | Verbose output with change details (shows first 20 changes) |

## Use Cases

- After importing properties with custom position values
- After generating variants
- When variant selection order is incorrect in the frontend
- Regular synchronization after bulk changes

## Technical Details

- **Namespace:** `WSCPluginSWSyncVariantPositions`
- **Command:** `wsc:sync-variant-positions`
- **Repository:** `product_configurator_setting.repository`
- **License:** GPL-3.0-or-later

## Frequently Asked Questions (FAQ)

**Q: Do I need to clear the cache after each use?**
A: Usually not. DAL changes take effect immediately. Only clear the cache if you experience issues in the frontend.

**Q: Can I safely execute the command?**
A: Yes. Use `--dry-run` first to see what will be changed. The plugin only modifies position values, no other data.

**Q: What happens if no position exists in property_group_option_translation?**
A: These entries are skipped and remain unchanged.

**Q: Can I run the command automatically?**
A: Yes, e.g., as a cronjob or after an import script.

## Support & Contributions

- **Issues:** [GitHub Issues](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/issues)
- **Discussions:** [GitHub Discussions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/discussions)
- **Support:** [support@web-seo-consulting.eu](mailto:support@web-seo-consulting.eu)

Pull requests are welcome! Please open an issue first to discuss larger changes.

## License & Support

**Made with ❤️ by WSC - Web SEO Consulting**

This plugin is free and open source (GPL-3.0-or-later). If it helped you, I appreciate your support:

[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/csaeum)
[![GitHub Sponsors](https://img.shields.io/badge/GitHub%20Sponsors-ea4aaa?style=for-the-badge&logo=github-sponsors&logoColor=white)](https://github.com/sponsors/csaeum)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/csaeum)

---

**WSC - Web SEO Consulting**
[www.web-seo-consulting.eu](https://www.web-seo-consulting.eu)
