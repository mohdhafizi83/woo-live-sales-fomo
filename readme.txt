=== Woo Live Sales FOMO Notification ===
Contributors: mohdhafizi83
Tags: woocommerce, fomo, sales notification, popup, conversion
Requires at least: 6.0
Tested up to: 6.9.4
Requires PHP: 7.4
WC requires at least: 8.0
WC tested up to: 8.7
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Boost your store's conversions with psychological triggers by showing real-time, highly-optimized sales notifications to your visitors.

== Description ==

Social proof is the most powerful psychological trigger in e-commerce. When visitors see that other people are actively buying from your store, it builds immediate trust and creates a "Fear Of Missing Out" (FOMO) that drives them to purchase.

However, most FOMO plugins run heavy database queries on every page load, slowing down your store and hurting your SEO. 

**Woo Live Sales FOMO Notification** is engineered for maximum scale.

**The Performance Secret:**
Instead of constantly querying your database, this plugin fetches your 5 most recent orders and aggressively caches them in your server's memory (Transient API) for 30 minutes. The frontend notification is powered entirely by 0-dependency Vanilla JavaScript and lightweight inline CSS transitions. 

**Features:**
* **Zero Configuration:** Activate it, and it instantly starts showing real data (City, Product Image, Time Ago).
* **Smart Filtering:** Automatically hides on the Cart and Checkout pages so it doesn't distract users who are already paying.
* **Organic Timing:** The notifications slide in naturally, waiting a randomized 8 to 15 seconds between popups so it feels authentic, not like a spammy robot.
* **100% Mobile Responsive:** Looks beautiful and non-intrusive on all devices.

== Installation ==

1. Ensure WooCommerce is installed and active with at least a few processing or completed orders.
2. Upload the `woo-live-sales-fomo` folder to your `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. Visit your website's homepage or shop page, wait 3 seconds, and watch the sales roll in at the bottom left of your screen!

== Frequently Asked Questions ==

= What if I haven't made any sales yet? =
If the plugin detects 0 processing or completed orders, it will silently go to sleep. It won't load any JavaScript or CSS, preserving your page speed perfectly until your first sale occurs.

= Is the customer's privacy protected? =
Yes. We strictly follow privacy best practices. The plugin only fetches the Billing City (e.g., "London" or "Kuala Lumpur"). It absolutely NEVER reveals full names, street addresses, or emails.

== Changelog ==

= 1.0.0 =
* Initial release on the WordPress repository.
* Implemented WooCommerce `wc_get_orders` with aggressive 30-minute transient caching.
* Built 0-dependency Vanilla JS rendering engine.
* Added randomized timing algorithms for organic popup pacing.