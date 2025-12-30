# WSC Sync Variant Positions

**[Deutsch](README.md) | [Français](README.fr.md)**

![Shopware 6](https://img.shields.io/badge/Shopware-6.5%20%7C%206.6%20%7C%206.7-179EFF?logo=shopware&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-GPL--3.0--or--later-blue)
![Maintained](https://img.shields.io/badge/Maintained-Yes-green)

---

## ⭐ License & Support

**Made with ❤️ by [WSC - Web SEO Consulting](https://github.com/csaeum)**

This project is free and open source under the [GPL-3.0-or-later](LICENSE) license.

---

### 💖 Support My Work

If this project helped you:

[![GitHub Sponsors](https://img.shields.io/badge/Sponsor-GitHub-ea4aaa?style=for-the-badge&logo=github)](https://github.com/sponsors/csaeum)
[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/csaeum)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/csaeum)

---

### 🔧 More Shopware Plugins

| Plugin | Description |
|--------|-------------|
| [WSCPluginSWSyncVariantPositions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions) | Synchronize variant positions |

---

[![GitHub followers](https://img.shields.io/github/followers/csaeum?style=social)](https://github.com/csaeum)
[![GitHub stars](https://img.shields.io/github/stars/csaeum?style=social)](https://github.com/csaeum?tab=repositories)

---

## What does this plugin do?

Synchronizes variant selection positions (`product_configurator_setting.position`) with property value positions from `property_group_option_translation.position` in Shopware 6.

### The Problem

When importing properties or manually maintaining them, positions are stored in the `property_group_option_translation` table. After generating variants, these positions are **not** automatically transferred to configurator settings (`product_configurator_setting`), which control the order of variant selection on the product.

### The Solution

This plugin offers **three ways** for synchronization:

1. **🖥️ Admin Interface** - Manual synchronization via Shopware Admin Panel
2. **⚡ CLI Command** - Fast execution via console
3. **🔄 Scheduled Task** - Automatic background synchronization

## Features

✅ **Three Synchronization Methods**
- Admin UI with live statistics
- CLI command for automation
- Scheduled task for regular sync

✅ **Flexible Options**
- Dry-run preview
- Product-specific sync via UUID filter
- Configurable batch size

✅ **Performance Optimized**
- Batch processing (500 entries/batch)
- Only changed positions are updated
- Efficient SQL queries

✅ **Multi-Language**
- German, English, French
- Fully translated (Admin UI + CLI)

✅ **Production Ready**
- Comprehensive error handling
- Logging for scheduled tasks
- Backward compatible with CLI

## Quick Start

### Via Shopware Admin

1. **Settings** → **Variant Position Sync**
2. Enable Dry-Run for preview
3. Optional: Enter Product ID for specific product
4. Click **Synchronize**

### Via CLI

```bash
# Preview (recommended for first test)
bin/console wsc:sync-variant-positions --dry-run

# Synchronize all products
bin/console wsc:sync-variant-positions

# Only one product
bin/console wsc:sync-variant-positions --product-id=018d9a5c3a4b70b8a8f8c2e8e8f8e8f8

# With verbose output
bin/console wsc:sync-variant-positions --dry-run -v
```

### Automatic Synchronization

1. **Settings** → **System** → **Plugins** → **WSC Sync Variant Positions**
2. Turn on "Enable Scheduled Sync"
3. Set interval (default: 3600 seconds = 1 hour)
4. Save

## Documentation

- **[📋 Prerequisites](README-Voraussetzungen.en.md)** - What is needed?
- **[🚀 Installation](README-Installation.en.md)** - Step-by-step guide
- **[⚙️ Configuration](README-Konfiguration.en.md)** - All configuration options

## Support & Contributions

- **Issues:** [GitHub Issues](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/issues)
- **Discussions:** [GitHub Discussions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/discussions)
- **Support:** [support@web-seo-consulting.eu](mailto:support@web-seo-consulting.eu)

Pull requests are welcome! Please open an issue first to discuss larger changes.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for all changes.

## License & Credits

**Made with ❤️ by [WSC - Web SEO Consulting](https://github.com/csaeum)**

This project is free and open source under the [GPL-3.0-or-later](LICENSE) license.

If this project helped you, I appreciate your support:

[![GitHub Sponsors](https://img.shields.io/badge/Sponsor-GitHub-ea4aaa?style=for-the-badge&logo=github)](https://github.com/sponsors/csaeum)
[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/csaeum)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/csaeum)

---

**WSC - Web SEO Consulting**
[www.web-seo-consulting.eu](https://www.web-seo-consulting.eu)
