# Installation

**[English](README-Installation.en.md)**

## Voraussetzungen prüfen

Stelle sicher, dass alle [Voraussetzungen](README-Voraussetzungen.md) erfüllt sind.

## Installationsmethoden

### Methode 1: Über den Shopware Store (Empfohlen)

1. Im Shopware Admin einloggen
2. **Einstellungen** → **System** → **Plugins**
3. Nach "WSC Sync Variant Positions" suchen
4. **Installieren** klicken
5. **Aktivieren** klicken
6. Cache leeren (optional)

### Methode 2: Manuelle Installation via ZIP

1. **Plugin herunterladen**
   - Von [GitHub Releases](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/releases/latest)
   - Lade `WSCPluginSWSyncVariantPositions.zip` herunter

2. **Plugin hochladen**
   - Im Shopware Admin: **Einstellungen** → **System** → **Plugins**
   - **Plugin hochladen** klicken
   - ZIP-Datei auswählen

3. **Plugin aktivieren**
   ```bash
   bin/console plugin:refresh
   bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
   bin/console cache:clear
   ```

### Methode 3: Manuelle Installation via Composer (Entwickler)

1. **Plugin-Verzeichnis erstellen**
   ```bash
   mkdir -p custom/plugins/WSCPluginSWSyncVariantPositions
   ```

2. **Dateien kopieren**
   ```bash
   # Klone das Repository
   git clone https://github.com/csaeum/WSCPluginSWSyncVariantPositions.git custom/plugins/WSCPluginSWSyncVariantPositions
   ```

3. **Plugin installieren**
   ```bash
   bin/console plugin:refresh
   bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
   bin/console cache:clear
   ```

## Nach der Installation

### 1. Admin JavaScript kompilieren (nur bei manueller Installation)

```bash
# Shopware 6.5+
bin/console bundle:dump
bin/build-administration.sh

# Alternative (wenn Skript nicht vorhanden)
./bin/build-js.sh
```

### 2. Scheduled Task registrieren

```bash
bin/console scheduled-task:register
```

### 3. Funktionstest

#### CLI-Test
```bash
bin/console wsc:sync-variant-positions --dry-run
```

**Erwartete Ausgabe:**
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
1. **Einstellungen** → **Varianten Position Sync**
2. Seite sollte ohne Fehler laden
3. Dry-Run aktivieren und **Vorschau anzeigen** klicken
4. Ergebnisse sollten angezeigt werden

## Troubleshooting

### Problem: Plugin erscheint nicht in der Plugin-Liste

**Lösung:**
```bash
bin/console plugin:refresh
```

### Problem: Admin UI zeigt Fehler

**Lösung:**
```bash
bin/console cache:clear
bin/build-administration.sh
```

### Problem: "Command not found"

**Lösung:**
```bash
# Cache leeren
bin/console cache:clear

# Plugin neu installieren
bin/console plugin:uninstall WSCPluginSWSyncVariantPositions
bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
```

### Problem: Scheduled Task läuft nicht

**Lösung:**
```bash
# Task neu registrieren
bin/console scheduled-task:register

# Message Consumer Status prüfen
bin/console messenger:stats

# Message Consumer starten (falls nicht läuft)
bin/console messenger:consume async --time-limit=60 &
```

### Problem: "Class not found" Fehler

**Lösung:**
```bash
# Composer autoload neu generieren
composer dump-autoload

# Cache leeren
bin/console cache:clear
```

## Deinstallation

### Plugin deaktivieren
```bash
bin/console plugin:deactivate WSCPluginSWSyncVariantPositions
```

### Plugin deinstallieren
```bash
bin/console plugin:uninstall WSCPluginSWSyncVariantPositions
```

### Plugin-Dateien entfernen
```bash
rm -rf custom/plugins/WSCPluginSWSyncVariantPositions
```

**Hinweis:** Die Plugin-Konfiguration und Scheduled-Task-Einträge werden bei der Deinstallation automatisch entfernt.

## Update

### Über Shopware Store
Updates werden automatisch im Plugin-Manager angezeigt.

### Manuelle Update
1. Plugin deaktivieren
2. Neue Version herunterladen
3. Alte Dateien ersetzen
4. Plugin aktualisieren:
   ```bash
   bin/console plugin:refresh
   bin/console plugin:update WSCPluginSWSyncVariantPositions
   bin/console plugin:activate WSCPluginSWSyncVariantPositions
   bin/console cache:clear
   bin/build-administration.sh
   ```

## Nächste Schritte

Nach erfolgreicher Installation:
➡️ [Konfiguration](README-Konfiguration.md)
