# Security Policy

- Report vulnerabilities privately via email to the maintainers.
- Use nonces and capability checks for all state-changing actions.
- Sanitize input, escape output, and validate intents; avoid direct `$_GET/$_POST` without checks.
- No external CDNs; only local, pinned dependencies.
- Log critical failures using `inc/core/logger.php`.
