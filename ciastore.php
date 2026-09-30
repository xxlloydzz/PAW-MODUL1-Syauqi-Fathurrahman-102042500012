<?php

$products = [
    ["nama" => "Laptop ASUS Vivobook 14",       "kategori" => "Laptop",     "harga" => 8499000, "stok" => 12],
    ["nama" => "Smartphone Samsung Galaxy A55", "kategori" => "Smartphone", "harga" => 5999000, "stok" => 8],
    ["nama" => "Headset Sony WH-1000XM5",       "kategori" => "Audio",      "harga" => 4299000, "stok" => 0],
    ["nama" => "Keyboard Logitech MX Keys",     "kategori" => "Aksesori",   "harga" => 1250000, "stok" => 25],
    ["nama" => "Mouse Logitech MX Master 3S",   "kategori" => "Aksesori",   "harga" => 1750000, "stok" => 0],
    ["nama" => "Monitor LG 27\" UltraFine 4K",  "kategori" => "Monitor",    "harga" => 3899000, "stok" => 5],
    ["nama" => "SSD Samsung 980 PRO 1TB",       "kategori" => "Storage",    "harga" => 1499000, "stok" => 30],
    ["nama" => "Powerbank Anker 20000mAh",      "kategori" => "Aksesori",   "harga" => 649000,  "stok" => 15],
];

// Jumlah produk dihitung OTOMATIS dari data (tidak hardcode)
$total_produk = count($products);

// Format harga ke Rupiah, mis. 8499000 -> Rp 8.499.000
function rupiah($angka) {
    return "Rp " . number_format($angka, 0, ",", ".");
}

// Cek apakah produk dapat diskon 10% (harga Rp1.000.000 atau lebih)
function dapat_diskon($harga) {
    return $harga >= 1000000;
}

// Hitung harga setelah diskon 10% — DIHITUNG PHP, bukan ditulis manual
function harga_setelah_diskon($harga) {
    return (int) round($harga * 0.9);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cia Store — Katalog Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- ================= HEADER ================= -->
    <header class="topbar">
        <div class="topbar-inner">
            <span class="brand">Cia Store</span>
            <span class="tagline">Toko Teknologi Terlengkap</span>
        </div>
    </header>

    <!-- ================= KATALOG PRODUK ================= -->
    <main class="wrap">
        <!-- Hero / bagian pembuka -->
        <section class="hero">
            <p class="hero-label">Cia Store</p>
            <h2>From A-Z Electronic Store.</h2>
            <p class="hero-desc">Temukan berbagai perangkat dan aksesoris teknologi yang kamu sukai.</p>
            <a href="#katalog" class="hero-btn">Lihat Produk</a>
        </section>

        <!-- Jumlah produk tampil otomatis dari data -->
        <h1 class="page-title" id="katalog">Total Produk Tersedia: <?php echo $total_produk; ?> Item</h1>

        <div class="grid">
            <!-- Perulangan PHP: setiap produk dicetak sebagai card otomatis -->
            <?php foreach ($products as $p): ?>
                <?php $ada_stok = $p["stok"] > 0; ?>
                <?php $diskon = dapat_diskon($p["harga"]); ?>
                <div class="card">
                    <p class="card-cat"><?php echo htmlspecialchars($p["kategori"]); ?><?php if ($diskon): ?> <span class="disc">| Diskon 10%</span><?php endif; ?></p>
                    <h3><?php echo htmlspecialchars($p["nama"]); ?></h3>

                    <!-- Harga: tampilkan harga normal (coret) + harga diskon bila dapat diskon -->
                    <?php if ($diskon): ?>
                        <p class="old-price"><?php echo rupiah($p["harga"]); ?></p>
                        <p class="price"><?php echo rupiah(harga_setelah_diskon($p["harga"])); ?></p>
                    <?php else: ?>
                        <p class="price"><?php echo rupiah($p["harga"]); ?></p>
                    <?php endif; ?>

                    <p class="stock">Stok: <?php echo $p["stok"]; ?> unit</p>

                    <!-- Percabangan PHP: status & tombol berdasarkan stok -->
                    <?php if ($ada_stok): ?>
                        <?php $harga_bayar = $diskon ? harga_setelah_diskon($p["harga"]) : $p["harga"]; ?>
                        <p class="status ok">Tersedia</p>
                        <button class="btn-buy btn-beli" type="button"
                            data-nama="<?php echo htmlspecialchars($p["nama"]); ?>"
                            data-harga="<?php echo rupiah($harga_bayar); ?>">Beli Sekarang</button>
                    <?php else: ?>
                        <p class="status out">Stok Habis</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- ================= MODAL CHECKOUT ================= -->
    <div class="modal-overlay" id="checkout-modal">
        <div class="modal">
            <!-- Tampilan pembayaran -->
            <div id="co-view-bayar">
                <h3>Checkout</h3>
                <p class="co-label">Produk</p>
                <p class="co-nama" id="co-nama"></p>
                <p class="co-label">Total Pembayaran</p>
                <p class="co-harga" id="co-harga"></p>
                <div class="co-qris">
                    <p class="co-label">Scan QRIS untuk membayar</p>
                    <div class="qris-card">
                        <p class="qris-title">QRIS</p>
                        <p class="qris-merchant">TOKO I BRILIAN B, ELEKTRONIK</p>
                        <p class="qris-nmid">NMID: ID1026567210988</p>
                        <!-- QRIS digambar sebagai SVG inline (±1.452 modul) hasil regenerasi dari payload QRIS asli -->
                        <?php include "qris-svg.php"; ?>
                        <p class="qris-foot">A01 &bull; QR Code Standar Pembayaran Nasional</p>
                    </div>
                </div>
                <button class="btn btn-primary" type="button" onclick="cekPembayaran()">Cek Pembayaran</button>
                <button class="btn btn-ghost" type="button" onclick="tutupCheckout()">Batal</button>
            </div>
            <!-- Tampilan pembayaran berhasil -->
            <div id="co-view-sukses" style="display: none;">
                <div class="co-check">&#10003;</div>
                <h3>Pembayaran berhasil!</h3>
                <p class="co-desc">Terima kasih telah berbelanja di Cia Store. Pesananmu sedang diproses.</p>
                <button class="btn btn-primary" type="button" onclick="tutupCheckout()">Kembali ke Katalog</button>
            </div>
        </div>
    </div>

    <!-- ================= FOOTER ================= -->
    <footer>&copy; 2026 Cia Store. All rights reserved.</footer>

    <script>
        // Buka modal checkout dengan data produk dari tombol yang diklik
        function bukaCheckout(nama, harga) {
            document.getElementById("co-nama").textContent = nama;
            document.getElementById("co-harga").textContent = harga;
            document.getElementById("co-view-bayar").style.display = "block";
            document.getElementById("co-view-sukses").style.display = "none";
            document.getElementById("checkout-modal").classList.add("open");
            document.body.style.overflow = "hidden";
        }

        // Tutup modal checkout
        function tutupCheckout() {
            document.getElementById("checkout-modal").classList.remove("open");
            document.body.style.overflow = "";
        }

        // Simulasi cek pembayaran -> tampilkan "Pembayaran berhasil!"
        function cekPembayaran() {
            document.getElementById("co-view-bayar").style.display = "none";
            document.getElementById("co-view-sukses").style.display = "block";
        }

        // Pasang event ke semua tombol "Beli Sekarang"
        document.querySelectorAll(".btn-beli").forEach(function (btn) {
            btn.addEventListener("click", function () {
                bukaCheckout(this.dataset.nama, this.dataset.harga);
            });
        });

        // Klik area gelap di luar modal untuk menutup
        document.getElementById("checkout-modal").addEventListener("click", function (e) {
            if (e.target === this) tutupCheckout();
        });

        // Tombol Escape untuk menutup modal
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") tutupCheckout();
        });
    </script>

</body>
</html>
