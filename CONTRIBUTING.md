# Contributing

Thanks for helping improve **Woo Live Sales FOMO Notification**!

## Development setup

1. Clone this repo into `wp-content/plugins/woo-live-sales-fomo/` on a local WordPress dev site (Local, DDEV, Lando, etc.).
2. Activate the plugin in **wp-admin → Plugins**.

## Making changes

- Fork the repo and branch from `main`.
- Keep changes focused; one PR per idea.
- Run `php -l` on every changed PHP file.
- Follow WordPress security conventions: sanitize input, escape output (`esc_html`, `esc_url`, `esc_attr`), verify nonces on AJAX and settings handlers.
- Update `CHANGELOG.md` with your change under an `Unreleased` heading.

## Releases

Maintainers cut releases: bump the version in the plugin header and `readme.txt`, add a `CHANGELOG.md` entry, then tag `vX.Y.Z`. CI builds the plugin zip and publishes the GitHub Release automatically.
