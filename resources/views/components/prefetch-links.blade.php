{{-- Prefetch halaman tujuan saat hover/sentuh agar navigasi terasa instan (ringan, tanpa dependensi eksternal). --}}
<script>
    (function () {
        const prefetched = new Set();
        let timer = null;

        function prefetch(url) {
            if (prefetched.has(url)) return;
            prefetched.add(url);
            const link = document.createElement('link');
            link.rel = 'prefetch';
            link.href = url;
            document.head.appendChild(link);
        }

        function eligible(a) {
            if (!a || !a.href) return false;
            if (a.origin !== location.origin) return false;      // hanya same-origin
            if (a.href === location.href) return false;
            if (a.hasAttribute('download')) return false;
            if (a.getAttribute('target') === '_blank') return false;
            const href = a.getAttribute('href') || '';
            if (href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return false;
            // jangan prefetch aksi yang mengubah data
            if (/logout|hapus|delete|destroy|read/i.test(a.href)) return false;
            return true;
        }

        // Desktop: prefetch setelah hover sesaat (hindari prefetch tiap link yang dilewati kursor)
        document.addEventListener('mouseover', function (e) {
            const a = e.target.closest('a');
            if (!eligible(a)) return;
            timer = setTimeout(() => prefetch(a.href), 65);
        });
        document.addEventListener('mouseout', function () {
            if (timer) { clearTimeout(timer); timer = null; }
        });

        // Mobile: prefetch begitu sentuhan dimulai
        document.addEventListener('touchstart', function (e) {
            const a = e.target.closest('a');
            if (eligible(a)) prefetch(a.href);
        }, { passive: true });
    })();
</script>
