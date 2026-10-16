# Getting started — Woo Live Sales FOMO Notification

### Upload the zip (recommended)

1. Download `woo-live-sales-fomo.zip` from the [latest release](https://github.com/mohdhafizi83/woo-live-sales-fomo/releases/latest).
2. In wp-admin, go to **Plugins → Add New → Upload Plugin**.
3. Choose the zip, click **Install Now**, then **Activate**.

### WP-CLI

```bash
wp plugin install --activate https://github.com/mohdhafizi83/woo-live-sales-fomo/releases/latest/download/woo-live-sales-fomo.zip
```

### Manual

Copy the plugin folder into `wp-content/plugins/` and activate it under **Plugins → Installed Plugins**.

## Verify it works

With at least one recent completed/processing order, open a product page (not cart/checkout) and wait ~3 seconds — a purchase popup should slide up from the bottom-left.

For troubleshooting, see the FAQ in the [README](../README.md#faq).
