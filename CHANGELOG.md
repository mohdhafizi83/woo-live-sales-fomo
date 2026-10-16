# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-10-16

### Added
- Real-time "someone just bought…" popup cycling recent completed/processing orders.
- 30-minute transient cache for order data (zero extra queries per page view after first hit).
- Privacy-conscious output: buyer city only, sanitized; rendered via `textContent` (XSS-safe).
- Suppressed on cart/checkout; script skipped entirely when there are no recent sales.
