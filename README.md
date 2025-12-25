# WSCPluginSWSyncVariantPositions

Synchronisiert die Positionen der Varianten-Auswahl (`product_configurator_setting.position`) mit den Eigenschaftswert-Positionen aus `property_group_option_translation.position` in Shopware 6.

## Kompatibilität

- Shopware 6.7.x
- bei anderen Shopware Versionen bitte immmer davor Datenbank Backup machen.

## Problem

Beim Import von Eigenschaften (Properties) oder manueller Pflege werden die Positionen in der Tabelle `property_group_option_translation` gespeichert. Nach dem Generieren der Varianten werden diese Positionen jedoch **nicht** automatisch auf die Configurator-Einstellungen (`product_configurator_setting`) übertragen, die die Reihenfolge der Variantenauswahl am Produkt steuern.

Dieses Plugin löst das Problem, indem es die Positionen nachträglich synchronisiert.

## Funktionsweise

Das Plugin kombiniert SQL für das Lesen der Daten (sicher) mit Shopware DAL für Updates.

### Technischer Ablauf

1. **Lesen der Daten (SQL SELECT):**
   - Vergleicht `product_configurator_setting.position` mit `property_group_option_translation.position`
   - Verknüpfung über `property_group_option_id`
   - Bei mehreren Übersetzungen wird `MAX(position)` verwendet
2. **Aktualisierung (DAL):**
   - Nur geänderte Positionen werden aktualisiert
   - Verwendet `product_configurator_setting.repository` für sichere Updates
   - Batch-Updates für bessere Performance (500 Einträge pro Batch)

## Installation

1. ZIP entpacken nach `custom/plugins/WSCPluginSWSyncVariantPositions/`

2. Plugin installieren und aktivieren:
```bash
bin/console plugin:refresh
bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
bin/console cache:clear
```

## Verwendung

### Dry-Run (Vorschau ohne Änderungen)

Zeigt an, welche Positionen geändert werden würden, ohne tatsächlich etwas zu ändern:

```bash
bin/console wsc:sync-variant-positions --dry-run
```

### Synchronisation ausführen

```bash
bin/console wsc:sync-variant-positions
```

### Nur ein Produkt synchronisieren

```bash
bin/console wsc:sync-variant-positions --product-id=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
```

### Ausführliche Ausgabe

Mit der `-v` Option werden mehr Details angezeigt:

```bash
bin/console wsc:sync-variant-positions --dry-run -v
```

### Nach der Synchronisation

Cache leeren:

```bash
bin/console cache:clear
```

## Optionen

| Option | Kurz | Beschreibung |
|--------|------|--------------|
| `--dry-run` | `-d` | Zeigt nur an, was geändert werden würde |
| `--product-id` |  | Optional: Nur ein bestimmtes Produkt (UUID) synchronisieren |
| `-v` | | Ausführliche Ausgabe mit Änderungsdetails |

## Lizenz & Unterstützung

**Made with ❤️ by WSC - Web SEO Consulting**

Dieses Plugin ist kostenlos und Open Source (GPL-3.0-or-later). Wenn es dir geholfen hat, freue ich mich über deine Unterstützung:

[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/csaeum)
[![GitHub Sponsors](https://img.shields.io/badge/GitHub%20Sponsors-ea4aaa?style=for-the-badge&logo=github-sponsors&logoColor=white)](https://github.com/sponsors/csaeum)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/csaeum)
