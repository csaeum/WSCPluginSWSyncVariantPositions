# Konfiguration

**[English](README-Konfiguration.en.md)**

## Plugin-Konfiguration

Die Plugin-Einstellungen findest du unter:
**Einstellungen** → **System** → **Plugins** → **WSC Sync Variant Positions** → **Konfiguration**

### Scheduled Task Einstellungen

#### Geplante Synchronisation aktivieren
- **Typ:** Boolean (Ja/Nein)
- **Standard:** Nein
- **Beschreibung:** Aktiviert die automatische Hintergrund-Synchronisation

**Wann aktivieren:**
- ✅ Bei regelmäßigen Eigenschafts-Importen
- ✅ Bei häufigen Variantengenerierungen
- ✅ Wenn manuelle Sync vergessen wird

**Wann deaktivieren:**
- ❌ Bei seltenen Änderungen
- ❌ Wenn manueller Sync bevorzugt wird
- ❌ Bei Performance-Problemen

#### Synchronisationsintervall (Sekunden)
- **Typ:** Integer
- **Standard:** 3600 (1 Stunde)
- **Minimum:** 60 (1 Minute)
- **Empfohlen:** 3600-86400

**Häufige Werte:**
- `300` = 5 Minuten (sehr häufig)
- `1800` = 30 Minuten (häufig)
- `3600` = 1 Stunde (Standard)
- `7200` = 2 Stunden
- `43200` = 12 Stunden
- `86400` = 24 Stunden (täglich)

**Hinweis:** Das Intervall wird automatisch aktualisiert, wenn du die Konfiguration speicherst.

### Synchronisationsverhalten

#### Batch-Größe
- **Typ:** Integer
- **Standard:** 500
- **Empfohlen:** 100-1000
- **Beschreibung:** Anzahl der Datensätze pro Batch-Update

**Optimierung:**
- **Kleine Shops (<1000 Varianten):** 100-250
- **Mittlere Shops (1000-10000):** 500 (Standard)
- **Große Shops (>10000):** 750-1000

**Bei Performance-Problemen:**
- Batch-Größe verringern (100-250)
- Intervall erhöhen (7200+)

## CLI-Nutzung

### Grundlegende Befehle

#### Vorschau (Dry-Run)
```bash
bin/console wsc:sync-variant-positions --dry-run
```
Zeigt was geändert werden würde, ohne tatsächlich zu ändern.

#### Vollständige Synchronisation
```bash
bin/console wsc:sync-variant-positions
```
Synchronisiert alle Produkte.

#### Produkt-spezifische Synchronisation
```bash
bin/console wsc:sync-variant-positions --product-id=018d9a5c3a4b70b8a8f8c2e8e8f8e8f8
```
Synchronisiert nur ein bestimmtes Produkt.

#### Mit ausführlicher Ausgabe
```bash
bin/console wsc:sync-variant-positions --dry-run -v
```
Zeigt die ersten 20 Änderungen detailliert.

### Optionen Referenz

| Option | Kurz | Typ | Beschreibung |
|--------|------|-----|--------------|
| `--dry-run` | `-d` | Flag | Nur Vorschau, keine Änderungen |
| `--product-id` | - | String | UUID des Produkts (Hex-Format) |
| `-v` | | Flag | Ausführliche Ausgabe |
| `-vv` | | Flag | Sehr ausführlich |
| `-vvv` | | Flag | Debug-Ausgabe |

### Automatisierung via Cron

Füge einen Cron-Job hinzu für regelmäßige Synchronisation:

```bash
# Täglich um 3:00 Uhr
0 3 * * * cd /pfad/zu/shopware && bin/console wsc:sync-variant-positions >> /var/log/shopware/sync-variants.log 2>&1

# Jede Stunde
0 * * * * cd /pfad/zu/shopware && bin/console wsc:sync-variant-positions >> /var/log/shopware/sync-variants.log 2>&1

# Jeden Sonntag um 2:00 Uhr
0 2 * * 0 cd /pfad/zu/shopware && bin/console wsc:sync-variant-positions >> /var/log/shopware/sync-variants.log 2>&1
```

## Admin UI Nutzung

### Zugriff
**Einstellungen** → **Varianten Position Sync**

### Funktionen

#### 1. Vorschau (Dry-Run)
- Schalter aktivieren
- **Vorschau anzeigen** klicken
- Ergebnisse werden angezeigt:
  - Anzahl zu aktualisierender Positionen
  - Anzahl unveränderte Positionen
  - Anzahl übersprungene Positionen

#### 2. Synchronisation
- Dry-Run deaktivieren
- Optional: Produkt-ID eingeben
- **Jetzt synchronisieren** klicken
- Warte auf Ergebnisse
- Bei Erfolg: Grüne Erfolgsmeldung

#### 3. Produkt-Filter
- Produkt-ID im Format: `018d9a5c3a4b70b8a8f8c2e8e8f8e8f8`
- Synchronisiert nur dieses Produkt
- Nützlich für Tests oder spezifische Produkte

### Produktfinder

**Produkt-ID im Admin finden:**
1. **Kataloge** → **Produkte**
2. Produkt öffnen
3. URL ansehen: `.../detail/018d9a5c3a4b70b8a8f8c2e8e8f8e8f8`
4. Letzte 32 Zeichen = Produkt-ID

**Produkt-ID via SQL finden:**
```sql
SELECT LOWER(HEX(id)) as product_id, product_number, name
FROM product
WHERE product_number = 'SW10001';
```

## Scheduled Task Verwaltung

### Task-Status prüfen
```bash
bin/console scheduled-task:list
```

### Task manuell ausführen
```bash
# Via Shopware Admin mit FroshTools Plugin
# Einstellungen → System → Scheduled Tasks → WSC Sync Variant Positions → Ausführen

# Via CLI (erfordert Message Consumer)
bin/console messenger:consume async --limit=1
```

### Task-Logs anzeigen
```bash
# Shopware Logs
tail -f var/log/dev.log | grep "variant position sync"

# System Logs (falls configured)
tail -f /var/log/shopware/scheduled-tasks.log
```

### Message Consumer starten
```bash
# Foreground (zum Testen)
bin/console messenger:consume async

# Background (Produktion)
bin/console messenger:consume async --time-limit=3600 &

# Mit Supervisor (empfohlen)
# /etc/supervisor/conf.d/shopware-messenger.conf
[program:shopware-messenger]
command=/var/www/shopware/bin/console messenger:consume async
user=www-data
autostart=true
autorestart=true
```

## Performance-Optimierung

### Große Datensätze (>10.000 Produkte)

1. **Batch-Größe anpassen**
   - Erhöhe auf 750-1000

2. **Scheduled Task Intervall erhöhen**
   - Mindestens 7200 Sekunden (2 Stunden)

3. **Zeitfenster nutzen**
   - Cron-Job für Nachtzeiten einrichten
   ```bash
   0 2 * * * cd /pfad/zu/shopware && bin/console wsc:sync-variant-positions
   ```

4. **Message Consumer Limits**
   ```bash
   bin/console messenger:consume async --memory-limit=512M --time-limit=3600
   ```

### Database-Optimierung

```sql
-- Index-Status prüfen
SHOW INDEX FROM product_configurator_setting;
SHOW INDEX FROM property_group_option_translation;

-- Tabellenstatistiken aktualisieren
ANALYZE TABLE product_configurator_setting;
ANALYZE TABLE property_group_option_translation;
```

## Fehlerbehandlung

### Logging aktivieren
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

### Häufige Probleme

#### Problem: Sync dauert sehr lange
**Lösung:**
- Batch-Größe erhöhen (750-1000)
- Database-Indizes prüfen
- Memory Limit erhöhen

#### Problem: Timeouts bei Scheduled Task
**Lösung:**
- Message Consumer Memory Limit erhöhen
- Intervall verlängern
- Batch-Größe verringern

#### Problem: Keine Änderungen sichtbar
**Lösung:**
```bash
# Cache leeren
bin/console cache:clear

# HTTP Cache leeren
bin/console http:cache:clear
```

## Best Practices

### Entwicklung
- ✅ Immer mit `--dry-run` testen
- ✅ Datenbank-Backup vor großen Sync
- ✅ Verbose-Modus für Debugging

### Produktion
- ✅ Scheduled Task für Automatisierung
- ✅ Monitoring der Task-Logs
- ✅ Regelmäßige Performance-Checks
- ✅ Message Consumer via Supervisor

### Testing
```bash
# Test mit einzelnem Produkt
bin/console wsc:sync-variant-positions --product-id=XXX --dry-run -v

# Test mit allen Produkten (Vorschau)
bin/console wsc:sync-variant-positions --dry-run

# Production-Run
bin/console wsc:sync-variant-positions
```

## Erweiterte Konfiguration

### Custom Batch-Größe via CLI
Die Batch-Größe ist aktuell fest im Plugin konfiguriert. Bei Bedarf kann ein Custom Command erstellt werden.

### Notifications bei Fehlern
Kann via Shopware's Logging + External Monitoring (z.B. Sentry) implementiert werden.

### Multi-Sales-Channel Setup
Das Plugin funktioniert channel-unabhängig, da es direkt auf die Datenbank zugreift.

## Support

Bei Problemen:
- 📧 [support@web-seo-consulting.eu](mailto:support@web-seo-consulting.eu)
- 💬 [GitHub Discussions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/discussions)
- 🐛 [GitHub Issues](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/issues)
