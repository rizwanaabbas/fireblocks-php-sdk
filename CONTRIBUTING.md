# Contributing to Fireblocks PHP SDK

Thank you for your interest in contributing! Please follow these guidelines.

## Getting Started

1. Fork the repository
2. Clone your fork: `git clone https://github.com/your-username/fireblocks-php-sdk.git`
3. Install dependencies: `composer install`
4. Create a branch: `git checkout -b feature/your-feature`

## Development

### Code Style

We follow PSR-12 coding standards. Run the linter before committing:

```bash
composer phpcs        # Check code style
composer phpcbf       # Fix code style automatically
```

### Static Analysis

We use PHPStan for static analysis:

```bash
composer phpstan
```

### Testing

Write tests for new features and ensure all tests pass:

```bash
composer test
```

### Commit Messages

Follow conventional commits:
- `feat:` New feature
- `fix:` Bug fix
- `docs:` Documentation changes
- `test:` Adding tests
- `refactor:` Code refactoring
- `style:` Code style changes

## Pull Request Process

1. Update the README.md with details of changes if applicable
2. Ensure all checks pass (CI, tests, static analysis)
3. Update the CHANGELOG.md
4. Request review from maintainers

## Code of Conduct

Be respectful and constructive in all interactions.
