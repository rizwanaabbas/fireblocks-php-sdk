# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
