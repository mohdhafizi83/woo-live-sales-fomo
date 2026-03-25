document.addEventListener('DOMContentLoaded', function() {
    // Semak jika data pesanan wujud dari PHP
    if (typeof wlsfData === 'undefined' || !wlsfData.orders || wlsfData.orders.length === 0) {
        return;
    }

    const popup = document.getElementById('wlsf-fomo-popup');
    if (!popup) return;

    const orders = wlsfData.orders;
    let currentIndex = 0;

    function showNextNotification() {
        const order = orders[currentIndex];

        // Masukkan data dengan selamat menggunakan textContent (Elak XSS di JS)
        document.getElementById('wlsf-city').textContent = order.city;
        document.getElementById('wlsf-product').textContent = order.product_name;
        document.getElementById('wlsf-time').textContent = order.time_ago;
        
        document.getElementById('wlsf-url').href = order.url;
        document.getElementById('wlsf-img').src = order.image;

        // Naikkan pop-up
        popup.classList.add('wlsf-show');

        // Sembunyikan selepas 5 saat
        setTimeout(function() {
            popup.classList.remove('wlsf-show');
            
            // Pergi ke pesanan seterusnya, jika habis, ulang dari mula
            currentIndex++;
            if (currentIndex >= orders.length) {
                currentIndex = 0;
            }

            // Tunggu rawak antara 8 hingga 15 saat sebelum tunjuk pesanan seterusnya
            const randomWait = Math.floor(Math.random() * (15000 - 8000 + 1) + 8000);
            setTimeout(showNextNotification, randomWait);

        }, 5000); // Tunjuk selama 5 saat
    }

    // Mulakan animasi pertama selepas pelawat berada di web selama 3 saat
    setTimeout(showNextNotification, 3000);
});