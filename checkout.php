<?php
ob_start();
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari sesi
$username = $_SESSION['username'];
$nama_lengkap = $_SESSION['nama_lengkap'];

// Koneksi ke database
include 'koneksi.php';

// Ambil produk yang dipilih untuk checkout
$pesanan_ids = [];
if (isset($_POST['pesanan_ids']) && !empty($_POST['pesanan_ids'])) {
    $pesanan_ids = $_POST['pesanan_ids'];

    if (is_array($pesanan_ids) && count($pesanan_ids) > 0) {
        $placeholders = implode(',', array_fill(0, count($pesanan_ids), '?'));

        $query_checkout = "
            SELECT 
                p.222247_id_pesanan, 
                p.222247_tanggal_pesanan, 
                p.222247_status_pesanan, 
                p.222247_total_harga, 
                p.222247_jumlah,
                pr.222247_foto, 
                pr.222247_nama_produk,
                pr.222247_deskripsi
            FROM tbl_pesanan_222247 AS p
            JOIN tbl_produk_222247 AS pr 
                ON p.222247_id_produk = pr.222247_id_produk
            WHERE p.222247_id_pesanan IN ($placeholders)
        ";

        $stmt = $koneksi->prepare($query_checkout);
        if ($stmt === false) {
            die('Gagal mempersiapkan query checkout.');
        }

        $stmt->bind_param(str_repeat('i', count($pesanan_ids)), ...$pesanan_ids);
        $stmt->execute();
        $result_checkout = $stmt->get_result();
    } else {
        $result_checkout = null;
    }
} else {
    $result_checkout = null;
}

// Ambil data pengguna
$query_pengguna = "
    SELECT 
        222247_nama_lengkap, 
        222247_nomor_telepon, 
        222247_alamat 
    FROM tbl_pengguna_222247 
    WHERE 222247_username = ?
";
$stmt_pengguna = $koneksi->prepare($query_pengguna);
if ($stmt_pengguna === false) {
    die('Gagal mempersiapkan query pengguna.');
}

$stmt_pengguna->bind_param('s', $username);
$stmt_pengguna->execute();
$result_pengguna = $stmt_pengguna->get_result();
$pengguna_data = $result_pengguna->fetch_assoc();

// Siapkan data checkout untuk tampilan (kumpulkan ke array dan hitung total)
$checkout_items = [];
$total_amount = 0;
if ($result_checkout && $result_checkout->num_rows > 0) {
    while ($r = $result_checkout->fetch_assoc()) {
        $checkout_items[] = $r;
        $total_amount += (float) $r['222247_total_harga'];
    }
}

// Preview length for product description (characters)
$desc_preview_len = 400;

$title = "Checkout Pembayaran";
include 'include/header.php';
include 'include/navbar.php';
?>

<style>
/* Improve product thumbnail clarity and layout */
.product-thumb {
    width: 100%;
    min-height: 200px; /* allow image to grow but also follow content */
    height: 100%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    cursor: pointer;
    transition: transform .15s ease-in-out;
}
.product-thumb:hover { transform: scale(1.02); }
.product-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
    display: block;
}
.checkout-product .row { align-items: stretch; }
.checkout-product .card { border: 0; box-shadow: 0 1px 6px rgba(0,0,0,0.06); }
.card-body .card-title { font-size: 1.05rem; }
.product-desc { color: #444; font-size: .95rem; line-height: 1.6; margin-top: .5rem; }
/* Collapsible description wrapper */
.product-desc-wrapper { overflow: hidden; transition: max-height .35s ease; max-height: 160px; }
.product-desc-wrapper.expanded { max-height: 1200px; }
.product-desc-wrapper.collapsed { max-height: 160px; }
/* Spacing tweaks */
.checkout-product .card-body { padding: 1.25rem; }
.checkout-product { margin-bottom: 1.25rem; }
@media (max-width: 576px) {
    .product-thumb { height: 160px; }
    .product-desc { max-height: none; }
}
.modal-img { max-width: 100%; height: auto; display:block; margin:0 auto; }
</style>
<style>
/* Overrides to make images follow content height */
.checkout-product .row { align-items: stretch; }
.product-thumb { min-height: 200px !important; height: 100% !important; }
.product-thumb img { height: 100% !important; object-fit: cover; }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Proses Pembayaran</h1>
                </div>
                <div class="col-sm-6">
                    <a href="index.php" class="btn btn-primary btn-sm mt-2 float-right">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <?php if (!empty($checkout_items)): ?>
                <div class="row">
                    <div class="col-lg-8 col-md-7 col-sm-12">
                        <div class="mb-3">
                            <?php foreach ($checkout_items as $item): ?>
                                <div class="card mb-3 checkout-product">
                                    <div class="row g-0">
                                        <div class="col-12 col-md-5">
                                            <div class="product-thumb h-100">
                                                <img src="pegawai/produk/<?php echo htmlspecialchars($item['222247_foto']); ?>" alt="Foto Produk">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-7">
                                            <div class="card-body py-3">
                                                <h5 class="card-title mb-1"><strong><?php echo htmlspecialchars($item['222247_nama_produk']); ?></strong></h5>
                                                <p class="mb-1 small text-muted">Tanggal: <?php echo date("d-m-Y H:i", strtotime($item['222247_tanggal_pesanan'])); ?></p>
                                                <p class="mb-1">Jumlah: <?php echo htmlspecialchars($item['222247_jumlah']); ?></p>
                                                <p class="mb-2">Harga: <strong>Rp <?php echo number_format($item['222247_total_harga'], 2, ',', '.'); ?></strong></p>

                                                <?php
                                                    $full_desc = htmlspecialchars($item['222247_deskripsi']);
                                                    $is_long = (strlen($full_desc) > $desc_preview_len);
                                                    $short_desc = $is_long ? substr($full_desc, 0, $desc_preview_len) . '...' : $full_desc;
                                                ?>
                                                <div class="product-desc-wrapper <?php echo $is_long ? 'collapsed' : 'expanded'; ?>">
                                                    <div class="product-desc"><?php echo nl2br($full_desc); ?></div>
                                                </div>
                                                <?php if ($is_long): ?>
                                                    <a href="#" class="btn btn-sm btn-link p-0 read-more">Baca selengkapnya</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-5 col-sm-12">
                        <div class="card sticky-top" style="top:22px;">
                            <div class="card-body">
                                <h5 class="card-title">Ringkasan Pesanan</h5>
                                <p class="mb-1 small text-muted">Jumlah item: <?php echo count($checkout_items); ?></p>
                                <p class="h5 mb-3"><strong>Total: Rp <?php echo number_format($total_amount, 2, ',', '.'); ?></strong></p>

                                <form action="proses_pembayaran.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="pesanan_ids" value="<?php echo htmlspecialchars(implode(',', $pesanan_ids)); ?>">

                                    <div class="form-group mb-3">
                                        <label for="payment_method">Metode Pembayaran</label>
                                        <select class="form-control" name="payment_method" id="payment_method" onchange="togglePaymentDetails(this.value)" required>
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option value="sea_bank_instant">SeaBank Bayar Instan</option>
                                            <option value="sea_bank">SeaBank</option>
                                            <option value="bca">Bank BCA</option>
                                            <option value="bri">Bank BRI</option>
                                            <option value="cod">COD</option>
                                            <option value="mandiri">Bank Mandiri</option>
                                            <option value="bni">Bank BNI</option>
                                            <option value="danamon">Bank Danamon</option>
                                            <option value="bsi">Bank Syariah Indonesia (BSI)</option>
                                            <option value="permata">Bank Permata</option>
                                            <option value="credit_card">Kartu Kredit/Debit</option>
                                        </select>

                                        <div id="payment-details" class="mt-2"></div>
                                        <div id="rekening-info" class="mt-2"></div>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="nama_penerima">Nama Penerima</label>
                                        <input type="text" class="form-control" name="nama_penerima" id="nama_penerima" required placeholder="Nama Lengkap">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="no_wa">Nomor WhatsApp</label>
                                        <input type="text" class="form-control" name="no_wa" id="no_wa" required placeholder="Masukkan Nomor Whatsapp">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="alamat">Alamat Pengiriman</label>
                                        <textarea class="form-control" name="alamat" id="alamat" rows="3" required placeholder="Masukkan Provinsi, Kota, dan Kecamatan."></textarea>
                                    </div>
                                    <div class="form-row mb-2">
                                        <div class="form-group col-6 pr-1">
                                            <label for="kode_pos">Kode Pos</label>
                                            <input type="text" class="form-control" name="kode_pos" id="kode_pos" required placeholder="Kode Pos">
                                        </div>
                                        <div class="form-group col-6 pl-1">
                                            <label for="titik_lokasi">Titik Lokasi</label>
                                            <input type="text" class="form-control" name="titik_lokasi" id="titik_lokasi" required placeholder="Nama jalan, No. rumah">
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-block mt-2">Buat Pesanan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Tidak ada pesanan yang dipilih atau pesanan tidak ditemukan.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$stmt->close();
$stmt_pengguna->close();
$koneksi->close();
include 'include/footer.php';
?>

<!-- Image modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body p-0">
        <img src="" id="modalImage" class="modal-img" alt="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
    // Fungsi untuk menampilkan detail pembayaran sesuai metode yang dipilih
    function togglePaymentDetails(method) {
        let paymentDetails = document.getElementById("payment-details");
        let rekeningInfo = document.getElementById("rekening-info");

        paymentDetails.innerHTML = ''; // Clear previous content
        rekeningInfo.innerHTML = ''; // Clear previous content

        // Kosongkan kolom yang tidak diperlukan
        let transferReceiptInput = document.getElementById("transfer_receipt");
        let deliveryAddressInput = document.getElementById("delivery_address");

        if (method === 'sea_bank_instant' || method === 'sea_bank' || method === 'bca' || method === 'bri' || method === 'mandiri' || method === 'bni' || method === 'danamon' || method === 'bsi' || method === 'permata') {
            paymentDetails.innerHTML = `
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="transfer_receipt">Foto Bukti Transfer</label>
                            <input type="file" class="form-control" name="transfer_receipt" id="transfer_receipt" required>
                        </div>
                    </div>
                </div>
            `;

            // Menampilkan nomor rekening yang sesuai dengan pilihan bank
           if (method === 'sea_bank_instant') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening SeaBank Bayar Instan:</strong><br>126 0878 1242 8358</div>`;
            } else if (method === 'sea_bank') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening SeaBank:</strong><br>276 8975 0191 1095</div>`;
            } else if (method === 'bca') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening Bank BCA:</strong><br>321 5634 4400 9982</div>`;
            } else if (method === 'bri') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening Bank BRI:</strong><br>0103 0111 3063 503</div>`;
            } else if (method === 'mandiri') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening Bank Mandiri:</strong><br>4961 6566 9401 302</div>`;
            } else if (method === 'bni') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening Bank BNI:</strong><br>0176 6709 3279 111</div>`;
            } else if (method === 'danamon') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening Bank Danamon:</strong><br>003 6104 123 26</div>`;
            } else if (method === 'bsi') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening Bank BSI:</strong><br>926 5982 3491 6574</div>`;
            } else if (method === 'permata') {
                rekeningInfo.innerHTML = `<div class="alert alert-info"><strong>Nomor Rekening Bank Permata:</strong><br>7937 0002 2917 337 </div>`;
            }
        } else if (method === 'credit_card') {
            paymentDetails.innerHTML = `
                <div class="form-group">
                    <label for="credit_card_number">Nomor Kartu Kredit</label>
                    <input type="text" class="form-control" name="credit_card_number" id="credit_card_number" required>
                </div>
                <div class="form-group">
                    <label for="credit_card_expiry">Tanggal Kadaluarsa</label>
                    <input type="month" class="form-control" name="credit_card_expiry" id="credit_card_expiry" required>
                </div>
            `;
        }
    }

    // Image modal logic: click thumbnail to open larger image
    (function(){
        var imgs = document.querySelectorAll('.product-thumb img');
        imgs.forEach(function(img){
            img.style.cursor = 'pointer';
            img.addEventListener('click', function(){
                var src = this.getAttribute('src');
                var alt = this.getAttribute('alt') || '';
                var modalImg = document.getElementById('modalImage');
                modalImg.setAttribute('src', src);
                modalImg.setAttribute('alt', alt);
                if (typeof $ === 'function' && $('#imageModal').modal) {
                    $('#imageModal').modal('show');
                } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    var imgModal = new bootstrap.Modal(document.getElementById('imageModal'));
                    imgModal.show();
                }
            });
        });

        // Read-more toggle for product descriptions (with slide animation)
        var rm = document.querySelectorAll('.read-more');
        rm.forEach(function(link){
            link.addEventListener('click', function(e){
                e.preventDefault();
                var cardBody = this.closest('.card-body');
                if (!cardBody) return;
                var wrapper = cardBody.querySelector('.product-desc-wrapper');
                if (!wrapper) return;
                var isCollapsed = wrapper.classList.contains('collapsed');
                if (isCollapsed) {
                    wrapper.classList.remove('collapsed');
                    wrapper.classList.add('expanded');
                    this.textContent = 'Sembunyikan';
                } else {
                    wrapper.classList.add('collapsed');
                    wrapper.classList.remove('expanded');
                    this.textContent = 'Baca selengkapnya';
                    wrapper.scrollIntoView({behavior:'smooth', block:'center'});
                }
            });
        });

        // Image modal zoom & pan
        (function(){
            var modalEl = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImage');
            var scale = 1, posX = 0, posY = 0, dragging = false, startX = 0, startY = 0;

            function setTransform(){
                modalImg.style.transform = 'translate(' + posX + 'px,' + posY + 'px) scale(' + scale + ')';
            }

            modalImg.addEventListener('wheel', function(e){
                e.preventDefault();
                var delta = e.deltaY > 0 ? -0.1 : 0.1;
                scale = Math.min(3, Math.max(1, scale + delta));
                setTransform();
            }, { passive: false });

            modalImg.addEventListener('pointerdown', function(e){
                dragging = true; modalImg.setPointerCapture && modalImg.setPointerCapture(e.pointerId); startX = e.clientX - posX; startY = e.clientY - posY; modalImg.style.cursor='grabbing';
            });
            modalImg.addEventListener('pointermove', function(e){
                if (!dragging) return;
                posX = e.clientX - startX; posY = e.clientY - startY; setTransform();
            });
            modalImg.addEventListener('pointerup', function(e){
                dragging = false; modalImg.releasePointerCapture && modalImg.releasePointerCapture(e.pointerId); modalImg.style.cursor='grab';
            });
            modalImg.addEventListener('dblclick', function(){ scale = 1; posX = 0; posY = 0; setTransform(); });

            function reset() { scale = 1; posX = 0; posY = 0; setTransform(); }
            if (typeof $ === 'function' && $('#imageModal').on) {
                $('#imageModal').on('hidden.bs.modal', function(){ reset(); });
            } else if (typeof bootstrap !== 'undefined') {
                modalEl.addEventListener('hidden.bs.modal', reset);
            }
        })();

    })();
</script>
