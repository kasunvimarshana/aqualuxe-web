# Security Notes
- All public forms include nonces and sanitize/escape routines.
- AJAX endpoints validate nonces and return JSON safely.
- Avoid third-party CDNs; all assets local.
- Keep WordPress core and plugins updated.
- Consider setting security headers at the server (CSP, X-Content-Type-Options, X-Frame-Options, Referrer-Policy).
