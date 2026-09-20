document.addEventListener('DOMContentLoaded', function() {
    // Check whether order data exists (passed from PHP).
    if (typeof wlsfData === 'undefined' || !wlsfData.orders || wlsfData.orders.length === 0) {
        return;
    }

    const popup = document.getElementById('wlsf-fomo-popup');
    if (!popup) return;

    const orders = wlsfData.orders;
    let currentIndex = 0;

    function showNextNotification() {
        const order = orders[currentIndex];

        // Insert data safely using textContent (prevents XSS in JS).
        document.getElementById('wlsf-city').textContent = order.city;
        document.getElementById('wlsf-product').textContent = order.product_name;
        document.getElementById('wlsf-time').textContent = order.time_ago;

        document.getElementById('wlsf-url').href = order.url;
        document.getElementById('wlsf-img').src = order.image;

        // Slide the popup up.
        popup.classList.add('wlsf-show');

        // Hide after 5 seconds.
        setTimeout(function() {
            popup.classList.remove('wlsf-show');

            // Advance to the next order; loop back to the start when done.
            currentIndex++;
            if (currentIndex >= orders.length) {
                currentIndex = 0;
            }

            // Random wait between 8 and 15 seconds before the next notification.
            const randomWait = Math.floor(Math.random() * (15000 - 8000 + 1) + 8000);
            setTimeout(showNextNotification, randomWait);

        }, 5000); // Visible for 5 seconds
    }

    // Start the first animation 3 seconds after the visitor lands.
    setTimeout(showNextNotification, 3000);
});
