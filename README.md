# WSC Sync Variant Positions

**[English](README.en.md) | [Français](README.fr.md)**

![Shopware 6](https://img.shields.io/badge/Shopware-6.5%20%7C%206.6%20%7C%206.7-179EFF?logo=shopware&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-GPL--3.0--or--later-blue)
![Maintained](https://img.shields.io/badge/Maintained-Yes-green)

---

## ⭐ Lizenz & Unterstützung

**Made with ❤️ by [WSC - Web SEO Consulting](https://github.com/csaeum)**

Dieses Projekt ist kostenlos und Open Source unter der [GPL-3.0-or-later](LICENSE) Lizenz.

---

### 💖 Unterstütze meine Arbeit

Wenn dir dieses Projekt geholfen hat:

[![GitHub Sponsors](https://img.shields.io/badge/Sponsor-GitHub-ea4aaa?style=for-the-badge&logo=github)](https://github.com/sponsors/csaeum)
[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/csaeum)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/csaeum)

---

### 🔧 Weitere Shopware Plugins

| Plugin | Beschreibung |
|--------|--------------|
| [WSCPluginSWSyncVariantPositions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions) | Varianten-Positionen synchronisieren |

---

[![GitHub followers](https://img.shields.io/github/followers/csaeum?style=social)](https://github.com/csaeum)
[![GitHub stars](https://img.shields.io/github/stars/csaeum?style=social)](https://github.com/csaeum?tab=repositories)

---

## Was macht dieses Plugin?

Synchronisiert die Positionen der Varianten-Auswahl (`product_configurator_setting.position`) mit den Eigenschaftswert-Positionen aus `property_group_option_translation.position` in Shopware 6.

### Das Problem

Beim Import von Eigenschaften (Properties) oder bei manueller Pflege werden die Positionen in der Tabelle `property_group_option_translation` gespeichert. Nach dem Generieren der Varianten werden diese Positionen jedoch **nicht** automatisch auf die Configurator-Einstellungen (`product_configurator_setting`) übertragen, die die Reihenfolge der Variantenauswahl am Produkt steuern.

### Die Lösung

Dieses Plugin bietet **drei Wege** zur Synchronisation:

1. **🖥️ Admin Interface** - Manuelle Synchronisation über das Shopware Admin Panel
2. **⚡ CLI Command** - Schnelle Ausführung über die Konsole
3. **🔄 Scheduled Task** - Automatische Hintergrund-Synchronisation

## Features

✅ **Drei Synchronisations-Methoden**
- Admin UI mit Live-Statistiken
- CLI-Command für Automatisierung
- Scheduled Task für regelmäßige Sync

✅ **Flexible Optionen**
- Dry-Run Vorschau
- Produkt-spezifischer Sync via UUID-Filter
- Konfigurierbare Batch-Größe

✅ **Performance-Optimiert**
- Batch-Processing (500 Einträge/Batch)
- Nur geänderte Positionen werden aktualisiert
- Effiziente SQL-Queries

✅ **Multi-Language**
- Deutsch, Englisch, Französisch
- Vollständig übersetzt (Admin UI + CLI)

✅ **Produktionsbereit**
- Umfangreiches Error-Handling
- Logging für Scheduled Tasks
- Rückwärtskompatibel mit CLI

## Schnellstart

### Über Shopware Admin

1. **Settings** → **Varianten Position Sync**
2. Dry-Run aktivieren für Vorschau
3. Optional: Produkt-ID eingeben für spezifisches Produkt
4. **Synchronisieren** klicken

### Über CLI

```bash
# Vorschau (empfohlen für ersten Test)
bin/console wsc:sync-variant-positions --dry-run

# Alle Produkte synchronisieren
bin/console wsc:sync-variant-positions

# Nur ein Produkt
bin/console wsc:sync-variant-positions --product-id=018d9a5c3a4b70b8a8f8c2e8e8f8e8f8

# Mit ausführlicher Ausgabe
bin/console wsc:sync-variant-positions --dry-run -v
```

### Automatische Synchronisation

1. **Einstellungen** → **System** → **Plugins** → **WSC Sync Variant Positions**
2. "Geplante Synchronisation aktivieren" einschalten
3. Intervall einstellen (Standard: 3600 Sekunden = 1 Stunde)
4. Speichern

## Dokumentation

- **[📋 Voraussetzungen](README-Voraussetzungen.md)** - Was wird benötigt?
- **[🚀 Installation](README-Installation.md)** - Schritt-für-Schritt Anleitung
- **[⚙️ Konfiguration](README-Konfiguration.md)** - Alle Einstellungsmöglichkeiten

## Support & Beiträge

- **Issues:** [GitHub Issues](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/issues)
- **Diskussionen:** [GitHub Discussions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/discussions)
- **Support:** [support@web-seo-consulting.eu](mailto:support@web-seo-consulting.eu)

Pull Requests sind willkommen! Bitte öffne zuerst ein Issue, um größere Änderungen zu besprechen.

## Changelog

Siehe [CHANGELOG.md](CHANGELOG.md) für alle Änderungen.

## Lizenz & Credits

**Made with ❤️ by [WSC - Web SEO Consulting](https://github.com/csaeum)**

Dieses Projekt ist kostenlos und Open Source unter der [GPL-3.0-or-later](LICENSE) Lizenz.

Wenn dir dieses Projekt geholfen hat, freue ich mich über deine Unterstützung:

[![GitHub Sponsors](https://img.shields.io/badge/Sponsor-GitHub-ea4aaa?style=for-the-badge&logo=github)](https://github.com/sponsors/csaeum)
[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/csaeum)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/csaeum)

---

**WSC - Web SEO Consulting**
[www.web-seo-consulting.eu](https://www.web-seo-consulting.eu)
