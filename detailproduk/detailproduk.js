// Menunggu sampai semua konten HTML dimuat
document.addEventListener("DOMContentLoaded", function() {

    // Ambil elemen gambar utama
    const mainImage = document.getElementById("mainProductImage");
    
    // Ambil semua gambar thumbnail
    const thumbnails = document.querySelectorAll(".thumbnail-list img");

    // Loop setiap thumbnail dan tambahkan event listener
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener("click", function() {
            
            // 1. Ganti gambar utama
            // Ambil 'src' dari thumbnail yang diklik
            const newImageSrc = thumbnail.getAttribute("src");
            // Setel 'src' gambar utama menjadi 'src' thumbnail
            mainImage.setAttribute("src", newImageSrc);

            // 2. Update kelas 'active'
            // Hapus kelas 'active' dari semua thumbnail
            thumbnails.forEach(t => t.classList.remove("active"));
            
            // Tambahkan kelas 'active' ke thumbnail yang baru saja diklik
            this.classList.add("active");
        });
    });
// ===== KODE BARU UNTUK CAROUSEL THUMBNAIL (GANTI FUNGSI LAMA) =====

const prevButton = document.getElementById("thumb-prev");
const nextButton = document.getElementById("thumb-next");
const thumbWrapper = document.querySelector(".thumbnail-wrapper");
const thumbList = document.querySelector(".thumbnail-list");

if (prevButton && nextButton && thumbWrapper && thumbList) {
    let currentIndex = 0; // Melacak posisi geser (selalu 0, 6, 12, dst.)
    let itemWidth = 0;
    let visibleItems = 6; // TETAPKAN INI KE 6
    let totalItems = thumbnails.length;

    function updateCarouselState() {
        // Kalkulasi lebar item (hanya perlu sekali, tapi kita taruh di sini)
        itemWidth = thumbnails[0].offsetWidth + 10; // (10 adalah 'gap' dari CSS)
        totalItems = thumbnails.length;

        // Sembunyikan/tampilkan tombol panah
        prevButton.disabled = (currentIndex === 0);
        // Tombol 'next' nonaktif jika halaman berikutnya akan kosong
        nextButton.disabled = (currentIndex + visibleItems >= totalItems);
        
        // Sembunyikan kedua tombol jika semua item muat
        if (totalItems <= visibleItems) {
            prevButton.style.display = 'none';
            nextButton.style.display = 'none';
        } else {
             prevButton.style.display = 'block';
             nextButton.style.display = 'block';
        }
    }

    function moveCarousel() {
        // Geser list menggunakan CSS transform
        const moveAmount = -currentIndex * itemWidth;
        thumbList.style.transform = `translateX(${moveAmount}px)`;
        updateCarouselState();
    }

    // GANTI EVENT LISTENER 'NEXT' DENGAN INI
    nextButton.addEventListener("click", function() {
        // Cek apakah kita BISA bergerak maju
        if (currentIndex + visibleItems < totalItems) {
            currentIndex += visibleItems; // Lompat sebanyak 6 item
            moveCarousel();
        }
    });

    // GANTI EVENT LISTENER 'PREV' DENGAN INI
    prevButton.addEventListener("click", function() {
        // Cek apakah kita BISA bergerak mundur
        if (currentIndex > 0) {
            currentIndex -= visibleItems; // Mundur sebanyak 6 item
            moveCarousel();
        }
    });

    // Panggil saat pertama kali dimuat
    updateCarouselState();
    
    // Panggil lagi jika ukuran window berubah (responsive)
    window.addEventListener("resize", function() {
        // Panggil ulang untuk mengkalkulasi ulang itemWidth
        updateCarouselState();
        // Langsung geser ke posisi yang benar jika lebar berubah
        moveCarousel();
    });
}

});