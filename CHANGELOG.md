# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- `Vaults::listAccountsPaged()` — cursor-paged vault scan via `GET /v1/vault/accounts_paged`
- `Vaults::buildPagedQueryString()` / `previewPagedQuery()` — correct repeated `includeTagIds` query params for tagged CI vault discovery
- `Vaults::attachOrDetachTags()` — attach/detach standard vault tags (`POST /v1/vault/accounts/attached_tags`)
- `Vaults::getAssetAddress()` — raw deposit address response helper
- `FireblocksClient::getPath()` — GET with pre-built path/query (used by paged vault APIs)
- `CreateVaultAccountRequest` model for typed vault account creation

### Changed
- `Vaults::listAccounts()` now uses `listAccountsPaged()` internally (supports tag filters + cursor)
- `Transactions::buildPayload()` — structured source/destination payload for estimate + create
- API resource classes aligned with portal usage (wallets, webhooks, gas stations, network, users)

## [1.2.12] - 2026-08-31

### Fixed
- GitHub Actions CI: PSR-12 formatting (`phpcbf`), PHPUnit test directory config, PHPStan baseline + `never` return on exception handler

## [1.2.11] - 2026-08-31

### Added
- `FireblocksClient::forWhitelistAddress()` — separate API credentials for whitelist / external-wallet operations
- `FireblocksClient::withCredentials()` — clone client with overridden API key and secret path
- `WhitelistCredentialsNotConfiguredException` when whitelist credentials are missing
- Unit tests for whitelist credential routing (`FireblocksClientWhitelistTest`)

### Changed
- GitHub Actions CI workflow: checkout v4, composer advisory policy, matrix YAML quoting

## [1.2.10] - 2026-08-25

### Added
- JWT authentication with RS256
- Laravel service provider and facade
- Vault accounts API
- Transactions API with fluent builder
- Internal/External/Contract wallets API
- Exchange accounts API
- Webhooks API
- Gas stations API
- Network connections API
- Fiat accounts API
- Smart contract API
- Comprehensive exception handling
- Auto-retry with exponential backoff
