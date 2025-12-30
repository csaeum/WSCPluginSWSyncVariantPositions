# Voraussetzungen

**[English](README-Voraussetzungen.en.md)**

## Systemanforderungen

### Shopware Version
- Shopware 6.5.x, 6.6.x oder 6.7.x
- **Wichtig:** Bei anderen Shopware Versionen bitte immer davor ein Datenbank-Backup erstellen!

### PHP
- PHP 8.1 oder höher
- Erforderliche PHP-Erweiterungen:
  - PDO
  - pdo_mysql
  - json
  - mbstring
  - xml

### Datenbank
- MySQL 5.7+ oder MariaDB 10.3+

### Server-Umgebung
- Zugriff auf Shopware CLI (bin/console)
- Schreibrechte im Plugin-Verzeichnis

## Optionale Anforderungen

### Für automatische Synchronisation (Scheduled Tasks)
- Message Queue Consumer muss laufen oder Cron-Job eingerichtet sein
- Siehe [Shopware Dokumentation zu Scheduled Tasks](https://developer.shopware.com/docs/guides/plugins/plugins/plugin-fundamentals/add-scheduled-task.html)

### Für Admin UI
- Moderne Browser (Chrome, Firefox, Safari, Edge - aktuelle Versionen)
- JavaScript muss aktiviert sein

## Berechtigungen

### Für CLI-Nutzung
- Shell-Zugriff auf den Server
- Berechtigung zum Ausführen von Symfony-Konsolenbefehlen

### Für Admin UI
- Admin-Zugang zum Shopware Backend
- Berechtigung zum Zugriff auf Plugin-Einstellungen

## Prüfung der Voraussetzungen

### PHP-Version prüfen
```bash
php -v
```

### Shopware-Version prüfen
```bash
bin/console --version
```

### Erforderliche PHP-Erweiterungen prüfen
```bash
php -m | grep -E 'pdo|mysql|json|mbstring|xml'
```

## Kompatibilitäts-Matrix

| Shopware Version | Plugin Version | Status |
|------------------|----------------|--------|
| 6.5.x | 6.7.5.0+ | ✅ Getestet |
| 6.6.x | 6.7.5.0+ | ✅ Getestet |
| 6.7.x | 6.7.5.0+ | ✅ Getestet |
| < 6.5.0 | - | ❌ Nicht unterstützt |

## Empfohlene Umgebung

Für optimale Performance empfehlen wir:
- PHP 8.2+
- MySQL 8.0+ oder MariaDB 10.6+
- OPcache aktiviert
- Memory Limit mindestens 512MB

## Vor der Installation

1. **Backup erstellen**
   - Datenbank-Backup
   - Plugin-Verzeichnis-Backup

2. **Shopware aktualisieren**
   - Stelle sicher, dass Shopware auf dem neuesten Stand ist

3. **Cache leeren**
   ```bash
   bin/console cache:clear
   ```

## Nächste Schritte

Wenn alle Voraussetzungen erfüllt sind:
➡️ [Installation](README-Installation.md)
