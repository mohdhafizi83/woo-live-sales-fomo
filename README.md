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

## License

GPL v2 or later.
