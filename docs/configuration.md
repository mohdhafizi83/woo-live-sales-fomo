# Configuration — Woo Live Sales FOMO Notification

**Zero-config** — there is no settings page. Once activated, popups appear automatically as soon as the store has recent completed/processing orders.

Tuning knobs (code-level):

| What | Where | Default |
|---|---|---|
| Order cache lifetime | `woo-live-sales-fomo.php` (transient TTL) | 30 minutes |
| Initial delay / visible time / gap | `assets/fomo.js` | 3 s / 5 s / random 8–15 s |
| Number of recent orders shown | `woo-live-sales-fomo.php` | 5 |
