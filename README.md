# Woo Live Sales FOMO Notification

Boost conversions with real-time "someone just bought…" popups, highly optimized with Transient caching.

## Features

- **Social proof popups** — shows recent purchases (city + product + time ago) sliding up from the bottom-left corner.
- **Transient-cached** — order data is cached for 30 minutes; zero extra queries per page view after the first hit.
- **Privacy-conscious** — only the buyer's city is shown (sanitized); no names, emails, or addresses.
- **Non-intrusive** — never loads on cart/checkout pages; skips loading entirely when there are no recent sales.
- **XSS-safe** — data is rendered via `textContent` in JS, never raw HTML injection.

## How it works

1. `wc_get_orders()` pulls the 5 most recent completed/processing orders (cached in a 30-min transient).
2. Data is passed to JS via `wp_localize_script` (JSON-safe).
3. A popup cycles through orders: 3s initial delay → 5s visible → random 8–15s gap → next order.

## Requirements

| | Minimum | Tested |
|---|---|---|
| WordPress | 6.0 | 6.9 |
| WooCommerce | 8.0 | 8.7 |
| PHP | 7.4 | 8.4 |

## Install

1. Install and activate the plugin.
2. Done — popups appear automatically once your store has recent orders.

## Installation

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

## Configuration

**Zero-config** — there is no settings page. Once activated, popups appear automatically as soon as the store has recent completed/processing orders.

Tuning knobs (code-level):

| What | Where | Default |
|---|---|---|
| Order cache lifetime | `woo-live-sales-fomo.php` (transient TTL) | 30 minutes |
| Initial delay / visible time / gap | `assets/fomo.js` | 3 s / 5 s / random 8–15 s |
| Number of recent orders shown | `woo-live-sales-fomo.php` | 5 |

## FAQ

**What customer data is displayed?**
Only the buyer's city (sanitized), the product name, and a "time ago" string. No names, emails, or street addresses ever leave the server.

**Why don't I see any popups?**
Popups need recent completed/processing orders. If there are none, the script doesn't load at all. Also check that you're not on the cart or checkout page — popups are suppressed there on purpose.

**Does it add database load?**
No. Order data is cached in a 30-minute transient, so repeat page views cost zero extra queries.

**Is the popup XSS-safe?**
Yes. The frontend renders everything with `textContent`, never raw HTML injection.

## Screenshots

Screenshot (add your own)

_No screenshot assets are committed to this repository yet. Drop an image at `assets/screenshots/screenshot-1.png` and replace this placeholder — do not hotlink invented screenshots._

## License

GPL v2 or later.
