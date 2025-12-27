# WSC Sync Variant Positions

**[Deutsch](README.md) | [English](README.en.md)**

Synchronise les positions de sélection des variantes (`product_configurator_setting.position`) avec les positions des valeurs de propriété dans `property_group_option_translation.position` dans Shopware 6.

## Compatibilité

- Shopware 6.5.x, 6.6.x, 6.7.x
- **Important:** Pour d'autres versions de Shopware, créez toujours une sauvegarde de la base de données au préalable!

## Problème & Solution

### Le Problème

Lors de l'importation de propriétés ou de leur gestion manuelle, les positions sont stockées dans la table `property_group_option_translation`. Après la génération des variantes, ces positions ne sont **pas** automatiquement transférées aux paramètres du configurateur (`product_configurator_setting`), qui contrôlent l'ordre de sélection des variantes sur le produit.

### La Solution

Ce plugin synchronise les positions rétroactivement avec une simple commande de console.

## Fonctionnement

Le plugin combine SQL pour la lecture des données (sécurisé) avec Shopware DAL pour les mises à jour.

### Processus Technique

1. **Lecture des Données (SQL SELECT):**
   - Compare `product_configurator_setting.position` avec `property_group_option_translation.position`
   - Liaison via `property_group_option_id`
   - Pour plusieurs traductions, `MAX(position)` est utilisé

2. **Mise à Jour (DAL):**
   - Seules les positions modifiées sont mises à jour
   - Utilise `product_configurator_setting.repository` pour des mises à jour sécurisées
   - Mises à jour par lot pour de meilleures performances (500 entrées par lot)

## Installation

### Prérequis

- Shopware 6.5.x, 6.6.x, ou 6.7.x
- PHP 8.1 ou supérieur
- Composer (optionnel, pour installation manuelle)

### Installation via Shopware Store

1. Rechercher et installer le plugin dans le Shopware Store
2. Activer le plugin
3. Vider le cache

### Installation Manuelle

1. Télécharger et extraire le fichier ZIP dans `custom/plugins/WSCPluginSWSyncVariantPositions/`

2. Installer et activer le plugin:
```bash
bin/console plugin:refresh
bin/console plugin:install --activate WSCPluginSWSyncVariantPositions
bin/console cache:clear
```

## Utilisation

### Dry-Run (Aperçu Sans Modifications)

Affiche quelles positions seraient modifiées sans effectuer réellement de changements:

```bash
bin/console wsc:sync-variant-positions --dry-run
```

### Exécuter la Synchronisation

Synchroniser toutes les positions de variantes:

```bash
bin/console wsc:sync-variant-positions
```

### Synchroniser un Seul Produit

Synchroniser un seul produit par UUID:

```bash
bin/console wsc:sync-variant-positions --product-id=018d9a5c3a4b70b8a8f8c2e8e8f8e8f8
```

### Sortie Détaillée

Avec l'option `-v`, plus de détails sont affichés (montre les 20 premiers changements):

```bash
bin/console wsc:sync-variant-positions --dry-run -v
```

### Après la Synchronisation

Vider le cache (optionnel, généralement pas nécessaire):

```bash
bin/console cache:clear
```

## Options de Ligne de Commande

| Option | Court | Description |
|--------|-------|-------------|
| `--dry-run` | `-d` | Affiche uniquement ce qui serait modifié (aucun changement) |
| `--product-id=UUID` |  | Optionnel: Synchroniser uniquement un produit spécifique |
| `-v` | | Sortie détaillée avec les détails des changements (montre les 20 premiers changements) |

## Cas d'Utilisation

- Après l'importation de propriétés avec des valeurs de position personnalisées
- Après la génération de variantes
- Lorsque l'ordre de sélection des variantes est incorrect dans le frontend
- Synchronisation régulière après des modifications en masse

## Détails Techniques

- **Namespace:** `WSCPluginSWSyncVariantPositions`
- **Commande:** `wsc:sync-variant-positions`
- **Repository:** `product_configurator_setting.repository`
- **Licence:** GPL-3.0-or-later

## Foire Aux Questions (FAQ)

**Q: Dois-je vider le cache après chaque utilisation?**
R: Généralement non. Les modifications DAL prennent effet immédiatement. Ne videz le cache que si vous rencontrez des problèmes dans le frontend.

**Q: Puis-je exécuter la commande en toute sécurité?**
R: Oui. Utilisez d'abord `--dry-run` pour voir ce qui sera modifié. Le plugin ne modifie que les valeurs de position, aucune autre donnée.

**Q: Que se passe-t-il si aucune position n'existe dans property_group_option_translation?**
R: Ces entrées sont ignorées et restent inchangées.

**Q: Puis-je exécuter la commande automatiquement?**
R: Oui, par exemple, comme tâche cron ou après un script d'importation.

## Support & Contributions

- **Issues:** [GitHub Issues](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/issues)
- **Discussions:** [GitHub Discussions](https://github.com/csaeum/WSCPluginSWSyncVariantPositions/discussions)
- **Support:** [support@web-seo-consulting.eu](mailto:support@web-seo-consulting.eu)

Les pull requests sont les bienvenues! Veuillez d'abord ouvrir une issue pour discuter des changements plus importants.

## Licence & Support

**Made with ❤️ by WSC - Web SEO Consulting**

Ce plugin est gratuit et open source (GPL-3.0-or-later). S'il vous a aidé, j'apprécie votre soutien:

[![Buy Me a Coffee](https://img.shields.io/badge/Buy%20Me%20a%20Coffee-ffdd00?style=for-the-badge&logo=buy-me-a-coffee&logoColor=black)](https://buymeacoffee.com/csaeum)
[![GitHub Sponsors](https://img.shields.io/badge/GitHub%20Sponsors-ea4aaa?style=for-the-badge&logo=github-sponsors&logoColor=white)](https://github.com/sponsors/csaeum)
[![PayPal](https://img.shields.io/badge/PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/csaeum)

---

**WSC - Web SEO Consulting**
[www.web-seo-consulting.eu](https://www.web-seo-consulting.eu)
