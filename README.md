# WSC Sync Variant Positions

**[English](README.en.md) | [Français](README.fr.md)**

Synchronisiert die Positionen der Varianten-Auswahl (`product_configurator_setting.position`) mit den Eigenschaftswert-Positionen aus `property_group_option_translation.position` in Shopware 6.

## Kompatibilität

- Shopware 6.5.x, 6.6.x, 6.7.x
- **Wichtig:** Bei anderen Shopware Versionen bitte immer davor ein Datenbank-Backup erstellen!

## Problem & Lösung

### Das Problem

Beim Import von Eigenschaften (Properties) oder bei manueller Pflege werden die Positionen in der Tabelle `property_group_option_translation` gespeichert. Nach dem Generieren der Varianten werden diese Positionen jedoch **nicht** automatisch auf die Configurator-Einstellungen (`product_configurator_setting`) übertragen, die die Reihenfolge der Variantenauswahl am Produkt steuern.

### Die Lösung

Dieses Plugin synchronisiert die Positionen nachträglich mit einem einfachen Konsolen-Befehl.

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

### Voraussetzungen

- Shopware 6.5.x, 6.6.x oder 6.7.x
- PHP 8.1 oder höher
- Composer (optional, für manuelle Installation)

### Installation über Shopware Store

1. Plugin im Shopware Store suchen und installieren
2. Plugin aktivieren
3. Cache leeren

### Manuelle Installation

1. ZIP-Datei herunterladen und entpacken nach `custom/plugins/WSCPluginSWSyncVariantPositions/`

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

Alle Variantenpositionen synchronisieren:

```bash
bin/console wsc:sync-variant-positions
```

### Nur ein Produkt synchronisieren

Ein einzelnes Produkt anhand der UUID synchronisieren:

```bash
bin/console wsc:sync-variant-positions --product-id=018d9a5c3a4b70b8a8f8c2e8e8f8e8f8
```

### Ausführliche Ausgabe

Mit der `-v` Option werden mehr Details angezeigt (zeigt die ersten 20 Änderungen):

```bash
bin/console wsc:sync-variant-positions --dry-run -v
```

### Nach der Synchronisation

Cache leeren (optional, meist nicht nötig):

```bash
bin/console cache:clear
```

## Kommandozeilen-Optionen

| Option | Kurz | Beschreibung |
|--------|------|--------------|
| `--dry-run` | `-d` | Zeigt nur an, was geändert werden würde (keine Änderungen) |
| `--product-id=UUID` |  | Optional: Nur ein bestimmtes Produkt synchronisieren |
| `-v` | | Ausführliche Ausgabe mit Änderungsdetails (zeigt erste 20 Änderungen) |

## Anwendungsfälle

- Nach dem Import von Eigenschaften mit eigenen Positions-Werten
- Nach dem Generieren von Varianten
- Bei falscher Reihenfolge der Variantenauswahl im Frontend
- Regelmäßige Synchronisation nach Bulk-Änderungen

## Technische Details

- **Namespace:** `WSCPluginSWSyncVariantPositions`
- **Command:** `wsc:sync-variant-positions`
- **Repository:** `product_configurator_setting.repository`
- **Lizenz:** GPL-3.0-or-later

## Häufige Fragen (FAQ)

**Q: Muss ich nach jeder Verwendung den Cache leeren?**
A: In der Regel nicht. Die DAL-Änderungen werden sofort wirksam. Nur bei Problemen im Frontend sollte der Cache geleert werden.

**Q: Kann ich den Befehl gefahrlos ausführen?**
A: Ja. Verwende zuerst `--dry-run` um zu sehen, was geändert wird. Das Plugin ändert nur Positions-Werte, keine anderen Daten.

**Q: Was passiert, wenn keine Position in property_group_option_translation vorhanden ist?**
A: Diese Einträge werden übersprungen und bleiben unverändert.

**Q: Kann ich den Befehl automatisiert ausführen?**
A: Ja, z.B. als Cronjob oder nach einem Import-Script.

## Support & Beiträge

- **Issues:** [GitHub Issues](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/issues)
- **Diskussionen:** [GitHub Discussions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/discussions)
- **Support:** [support@web-seo-consulting.eu](mailto:support@web-seo-consulting.eu)

Pull Requests sind willkommen! Bitte öffne zuerst ein Issue, um größere Änderungen zu besprechen.

## Lizenz & Unterstützung

**Made with ❤️ by WSC - Web SEO Consulting**

Dieses Plugin ist kostenlos und Open Source (GPL-3.0-or-later). Wenn es dir geholfen hat, freue ich mich über deine Unterstützung:

[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/csaeum)
[![GitHub Sponsors](https://img.shields.io/badge/GitHub%20Sponsors-ea4aaa?style=for-the-badge&logo=github-sponsors&logoColor=white)](https://github.com/sponsors/csaeum)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/csaeum)

---

**WSC - Web SEO Consulting**
[www.web-seo-consulting.eu](https://www.web-seo-consulting.eu)
