# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Multi-language README files (DE, EN, FR)
- GitHub Actions CI/CD workflow
- PHPStan configuration
- PHP CodeSniffer configuration
- Security scanning with Gitleaks
- .gitattributes for proper git archive exports

### Changed
- Improved project structure and documentation
- Enhanced README with more detailed examples and FAQ

## [6.7.5] - 2025-12-27

### Added
- Initial public release
- Console command `wsc:sync-variant-positions`
- Dry-run mode for previewing changes
- Product-specific synchronization via `--product-id` option
- Batch updates for better performance (500 entries per batch)
- Verbose mode for detailed output

### Features
- Synchronizes `product_configurator_setting.position` with `property_group_option_translation.position`
- Safe SQL SELECT for reading data
- Shopware DAL for updates
- Compatible with Shopware 6.5.x, 6.6.x, 6.7.x

[Unreleased]: https://github.com/csaeum/WSCPluginSWSyncVariantPositions/compare/v6.7.5...HEAD
[6.7.5]: https://github.com/csaeum/WSCPluginSWSyncVariantPositions/releases/tag/v6.7.5
