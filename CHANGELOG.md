# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [6.7.5.1] - 2025-12-30

### Added
- **Admin UI Integration** - Complete administration interface in Shopware Admin
  - New settings page under Settings → Variant Position Sync
  - Live statistics display (Updated/Unchanged/Skipped)
  - Dry-Run preview mode
  - Product-ID filter for selective sync
  - Multi-language support (de-DE, en-GB, fr-FR)
- **Scheduled Task System** - Automatic background synchronization
  - Configurable interval via plugin configuration
  - Enable/disable via admin settings
  - Automatic interval updates when config changes
  - Comprehensive logging
- **Plugin Configuration Page**
  - Scheduled task settings (enable/disable, interval)
  - Batch size configuration
  - Multi-language configuration labels
- **API Endpoints** for Admin UI
  - `/api/_action/wsc-sync-variant-positions/sync` - Execute sync
  - `/api/_action/wsc-sync-variant-positions/preview` - Dry-run preview
- **Service Layer Architecture**
  - `VariantPositionSyncService` - Core sync logic (DRY principle)
  - `SyncOptions` and `SyncResult` value objects
  - Reused by CLI, Admin API, and Scheduled Tasks
- **Event Subscriber** - Automatically updates task interval on config changes
- **Comprehensive Documentation**
  - Multi-language README files (DE, EN, FR)
  - Separate documentation for Prerequisites, Installation, Configuration
  - GitHub Actions CI/CD workflows
  - Release workflow with proper ZIP naming (Shopware-compatible)
  - .gitattributes for clean exports

### Changed
- **Refactored CLI Command** - Now uses service layer instead of direct DB access
- **Full backward compatibility** - Existing CLI functionality unchanged
- **Improved error handling** - Structured error responses in API
- Plugin version updated to 6.7.5.1

### Fixed
- ScheduledTaskHandler constructor - Correct parameter order for Shopware compatibility
- Scheduled task interval - Now properly reads from plugin configuration
- Logger property access in TaskHandler

### Technical Details
- Service-Oriented Architecture (Service → Consumer → Presentation layers)
- Batch processing with configurable size (default: 500)
- Automatic task interval synchronization on config save
- Compatible with Shopware 6.5.x, 6.6.x, 6.7.x

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
