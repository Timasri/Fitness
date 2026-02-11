-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Des 2024 pada 07.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `222247_fitnes`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_info_222247`
--

CREATE TABLE `tbl_info_222247` (
  `222247_id_info` int(11) NOT NULL,
  `222247_no_wa` varchar(15) NOT NULL,
  `222247_alamat` text NOT NULL,
  `222247_titik_lokasi` text NOT NULL,
  `222247_kode_pos` text NOT NULL,
  `222247_id_pembayaran` int(11) NOT NULL,
  `222247_nama_penerima` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_info_222247`
--

INSERT INTO `tbl_info_222247` (`222247_id_info`, `222247_no_wa`, `222247_alamat`, `222247_titik_lokasi`, `222247_kode_pos`, `222247_id_pembayaran`, `222247_nama_penerima`) VALUES
(30, '1231', 'Masukkan Dengan Jelas Alamat Anda.', 'VF8X+2F7 perintis kemerdekaan 12, Unnamed Road, Tamalanrea, Kec. Tamalanrea, Kota Makassar, Sulawesi Selatan 90245', '1231', 68, '1231'),
(31, '0824335754777', 'MAKASSAR', 'antang', '34254', 69, 'BeniUndipa'),
(32, '0824335754777', 'MAKASSAR', 'makasssar', '34254', 70, 'BeniUndipa'),
(33, '0824335754777', 'racing', 'dd', '3425', 71, 'CLARA'),
(34, '0824335754777', 'racing', 'sfsf', '3425', 72, 'CLARA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pegawai_222247`
--

CREATE TABLE `tbl_pegawai_222247` (
  `222247_id_pegawai` int(11) NOT NULL,
  `222247_username` varchar(50) NOT NULL,
  `222247_kata_sandi` varchar(255) NOT NULL,
  `222247_nama_lengkap` varchar(100) NOT NULL,
  `222247_role` enum('Admin','Pegawai') NOT NULL,
  `222247_no_telepon` varchar(20) DEFAULT NULL,
  `222247_session_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_pegawai_222247`
--

INSERT INTO `tbl_pegawai_222247` (`222247_id_pegawai`, `222247_username`, `222247_kata_sandi`, `222247_nama_lengkap`, `222247_role`, `222247_no_telepon`, `222247_session_token`) VALUES
(1, 'Timasri', '123', 'Timasri Olympia', 'Admin', '081341039855', '6062853728dd81dd3189f8df2c7c8f1d'),
(2, 'Adeentiro', '123', 'Adeentiro Muscle', 'Pegawai', '0895555444666', ''),
(5, 'Beni', '123', 'BeniUndipa', 'Admin', '082789098098', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pembayaran_222247`
--

CREATE TABLE `tbl_pembayaran_222247` (
  `222247_id_pembayaran` int(11) NOT NULL,
  `222247_id_pesanan` int(11) DEFAULT NULL,
  `222247_status_pembayaran` enum('Pembayaran Diproses','Lunas','Gagal') NOT NULL,
  `222247_tanggal_pembayaran` datetime DEFAULT NULL,
  `222247_metode` varchar(50) NOT NULL,
  `222247_bukti_pembayaran` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_pembayaran_222247`
--

INSERT INTO `tbl_pembayaran_222247` (`222247_id_pembayaran`, `222247_id_pesanan`, `222247_status_pembayaran`, `222247_tanggal_pembayaran`, `222247_metode`, `222247_bukti_pembayaran`) VALUES
(68, 25, 'Pembayaran Diproses', '2024-12-05 21:06:54', 'cod', ''),
(69, 28, 'Pembayaran Diproses', '2024-12-08 15:46:51', 'cod', ''),
(70, 28, 'Pembayaran Diproses', '2024-12-08 15:53:01', 'cod', ''),
(71, 29, 'Pembayaran Diproses', '2024-12-08 16:58:21', 'cod', ''),
(72, 31, 'Pembayaran Diproses', '2024-12-08 17:20:17', 'cod', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pengguna_222247`
--

CREATE TABLE `tbl_pengguna_222247` (
  `222247_id_pengguna` int(11) NOT NULL,
  `222247_username` varchar(50) NOT NULL,
  `222247_kata_sandi` varchar(255) NOT NULL,
  `222247_nama_lengkap` varchar(100) NOT NULL,
  `222247_email` varchar(100) NOT NULL,
  `222247_nomor_telepon` varchar(20) DEFAULT NULL,
  `222247_alamat` text DEFAULT NULL,
  `222247_session_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_pengguna_222247`
--

INSERT INTO `tbl_pengguna_222247` (`222247_id_pengguna`, `222247_username`, `222247_kata_sandi`, `222247_nama_lengkap`, `222247_email`, `222247_nomor_telepon`, `222247_alamat`, `222247_session_token`) VALUES
(2, 'Adeentiro', '123', 'Adeentiro Muscle', 'AdeentiroMuscle@gmail.com', '089765444533', 'Malang jawa barat', '8bda24e66bbab1d3baed3325d1f5abca'),
(6, 'Undipa', '123', 'Beni', 'beniUndipa@gmail.com', '082876365478', 'Universitas Dipa MAKASSAR', 'e60e11878b5f23338fe09bdeb99158f0'),
(7, 'surya', '123', 'surya', 'surya@gmail.com', '082322145237', 'makassar', '85caaddf62bfe8ce7bc171d4b3fd6efa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pesanan_222247`
--

CREATE TABLE `tbl_pesanan_222247` (
  `222247_id_pesanan` int(11) NOT NULL,
  `222247_id_pengguna` int(11) DEFAULT NULL,
  `222247_id_produk` int(11) NOT NULL,
  `222247_tanggal_pesanan` datetime NOT NULL,
  `222247_status_pesanan` enum('Pesanan Diproses','Pembayaran Diproses','Dikirim','Selesai','Dibatalkan') NOT NULL,
  `222247_total_harga` decimal(10,2) NOT NULL,
  `222247_jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_pesanan_222247`
--

INSERT INTO `tbl_pesanan_222247` (`222247_id_pesanan`, `222247_id_pengguna`, `222247_id_produk`, `222247_tanggal_pesanan`, `222247_status_pesanan`, `222247_total_harga`, `222247_jumlah`) VALUES
(24, 6, 7, '2024-12-05 16:36:25', 'Pesanan Diproses', 1358000.00, 1),
(25, 6, 8, '2024-12-05 16:49:26', 'Pesanan Diproses', 3533310.00, 1),
(26, 6, 8, '2024-12-05 20:06:34', 'Pesanan Diproses', 3533310.00, 1),
(27, 6, 8, '2024-12-05 20:47:13', 'Pesanan Diproses', 3533310.00, 1),
(28, 6, 8, '2024-12-05 20:54:03', 'Pesanan Diproses', 3533310.00, 1),
(29, 6, 8, '2024-12-08 15:58:55', 'Pesanan Diproses', 3533310.00, 1),
(30, 2, 8, '2024-12-08 17:19:21', 'Pesanan Diproses', 3533310.00, 1),
(31, 2, 8, '2024-12-08 17:19:44', 'Pesanan Diproses', 3533310.00, 1),
(32, 7, 8, '2024-12-09 07:30:37', 'Pesanan Diproses', 3533310.00, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_produk_222247`
--

CREATE TABLE `tbl_produk_222247` (
  `222247_id_produk` int(11) NOT NULL,
  `222247_nama_produk` varchar(100) NOT NULL,
  `222247_kategori` enum('Suplemen','Alat Fitness') NOT NULL,
  `222247_harga` decimal(10,2) NOT NULL,
  `222247_stok` int(11) NOT NULL,
  `222247_deskripsi` text DEFAULT NULL,
  `222247_foto` varchar(50) NOT NULL DEFAULT 'produk.jpg',
  `222247_rating` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_produk_222247`
--

INSERT INTO `tbl_produk_222247` (`222247_id_produk`, `222247_nama_produk`, `222247_kategori`, `222247_harga`, `222247_stok`, `222247_deskripsi`, `222247_foto`, `222247_rating`) VALUES
(7, 'Sport bike statis spining bike hitam treadmill electric low watt alat fitnes rumah', 'Alat Fitness', 1358000.00, 297, '✨Garansi 5 Tahun✨\r\nSpesifikasi\r\nBahan Baja,\r\nWarna hitam,\r\nLingkup aplikasi kantor, keluarga,\r\nProgram penurunan berat badan, kebugaran.\r\nFitur :\r\nKetinggian dapat diatur dalam 3 posisi,\r\nBeban maksimum 150kg,\r\nUkuran kemasan 86 * 20 * 73CM,\r\nBerat produk 14kg,\r\nUkuran yang diperluas  85 * 45 * 110CM,\r\nMode transmisi  transmisi sabuk dua arah, rasakan gerakan yang sangat tenang,\r\nFitur komponen roda gila ukuran besar, pedal ABS, pegangan loncatan kapas,\r\nTampilan meteran elektronik pindai, kecepatan, kalori, waktu, jarak\r\nHand pulse  ialah monitor detak jantung dapat membantu mengukur denyut nadi.', '674432e63802c.jpg', 5.00),
(8, 'Living Motorized Treadmill Listrik Alat fitness treadmill elektrik LED / Treadmill', 'Alat Fitness', 3533310.00, 92, '✨Garansi 12 Bulan✨\r\nFitur Uji Beban 100KG maks 150KG,\r\nKecepatan 1-12.8KM/JAM Dapat Disesuaikan,\r\n Tampilan Layar ada Kecepatan, Waktu, Kalori, Detak Jantung,\r\n Mesin Sangat Tenang,\r\n Desain Holder Tablet dan Ergonomis,\r\n Dengan Kunci Pengaman Magnetik Treadmill Lebar 560MM, Papan Lari Luas dan Menyerap Guncangan,\r\n Pegangan Cepat Multifungsi,\r\n Sistem Gerak Multifungsi,\r\n Sabuk Lari Awan 7 lapis ,\r\nBebas Lipat 90°,\r\nSpesifikasi Produk\r\nModel  SPM7,\r\nArea Lari 1000×400MM,\r\nDaya Puncak 2,5HP\r\nKisaran Kecepatan 1-12.8KM/H,\r\nUkuran DIlipat: 570×660×1275MM,\r\nUkuran yang Dibuka : 1335×660×1190MM.', '6744333c120c8.jpg', 4.90),
(9, 'BG SPORT Sliding Fitness Twister Mini Stepper Waist Twisting  Fitness Walker Glider', 'Alat Fitness', 737026.00, 239, '✨Garansi 1 bulan✨\r\nSliding Fitness bekerja pada otot paha, perut, dan lengan secara bersamaan berkat gerakan sliding lateral dan karet gelangnya. Selain melatih otot-otot tubuh bagian atas dan bawah, Sliding Fitness melatih keseimbangan. Platform yang mengumpulkan otot-otot tubuh bagian atas dan bawah.\r\nMax pengguna 100kg. NOTED : Silahkan hubungi admin kami terlebih dahulu, apabila ada kendala terhadap produk sebelum memberi ulasan.\r\n Jika ada keluhan terhadap produk, wajib di sertakan video unboxing, jika tidak ada video unboxing mohon maaf kami tidak menerima keluhan tersebut.', '674433bfd8e11.jpg', 5.00),
(10, 'Prevo Evolene 225gr Pre-Workout - Energy Drink Halal dan BPOM - No Caffeine', 'Suplemen', 349000.00, 469, 'Evolene Prevo merupakan Pre-Workout yang mengandung zero caffeine sehingga tidak bikin berdebar dan susah tidur. Prevo dikonsumsi 30 menit sebelum melakukan aktivitas olahraga agar menghasilkan energi yang ekstra membuat endurance makin lama dan\r\nmenambah fokus saat latihan.\r\nCara konsumsi :\r\nAmbil 1 sachet prevo, campur ke 150 mililiter air, shake dan minum.\r\nKomposisi Prevo:\r\nBeta alanine memicu supply amino yang mengakibatkan sensasi kesemutan untuk tenaga,\r\nKreatin monohidrat meningkatkan ATP (sumber energi) pada otot,\r\nLarginine dan citruline malate meningkatkan sirkulasi darah pada otot dan memicu tenaga lebih besar atau pumping,\r\nEkstrak guarana meningkatkan fokus tanpa mengakibatkan sakit kepala.', '6744c06616e6b.jpg', 5.00),
(11, 'Isolene Evolene 396gr/12 Sachet - Suplemen Fitness - Whey Protein Isolate', 'Suplemen', 239000.00, 550, 'KENAPA HARUS WHEY ISOLATE (ISOLENE) ?\r\nProtein Paling Tinggi (27 gr/serving) dapat mencukupi kebutuhan Protein harian.\r\nPaling Rendah Lemak (0,5 gr/serving), Bermanfaat untuk pembentukan otot bebas lemak.\r\nLebih Mudah Diserap Menurut US National Library of Medicine National Institutes of Health, diserap dalam waktu 2-3 jam. Sedangkan, isolane diserap dalam waktu 1 jam saja.\r\nIsolene bebas laktosa dan gula  cocok buat kamu yang alergi susu dan tinggi karbohidrat.\r\nKemasan:\r\n396gr atau 12 Serving  nya @228gr/Sachet/Sajian.\r\nDirekomendasikan untuk pria dan wanita,\r\nMemiliki alergi laktosa,\r\nMenginginkan kualitas protein premium untuk  program defisit kalori,\r\nCocok untuk mendukung program Cutting.', '6744c0b1c25d4.jpg', 4.80),
(12, 'Evomass Evolene 10lbs - Suplemen Fitness - Mass Gainer', 'Suplemen', 799000.00, 4404, 'Evomass merupakan gainer tinggi protein yang cocok untuk bantu kamu menaikkan berat badan dan massa otot sehingga badan lebih berisi. Tinggi protein yaitu 52 gr/ serving,\r\ntinggi kalori berkualitas, 932 kkal/ serving,\r\nrendah lemak hanya 4 gr/ serving,\r\ntinggi karbohidrat,\r\nTerdapat 11 gr BCAA yang mampu mencegah terkikisnya otot dan mempercepat proses recovery otot kamu,\r\nMengandung 8.5 gr Glutamine untuk mencegah kelelahan dan membantu mempercepat pemulihan saat cedera.\r\nSaran penyajian:\r\nKonsumsi sebanyak 6 scoops setiap hari.\r\n2-3 kali minum (2 scoop / sekali minum 3 kali sehari, atau 3 scoop / sekali minum 2 kali sehari).\r\nCampurkan dengan air dingin atau air biasa sebanyak 500 ml setiap penyajiannya. \r\nKocok/aduk, lalu minum.', '6744c0efe84bc.jpg', 4.90),
(13, 'Boxing glove Rounin Fightware/ sarung tinju / glove muaythai - Vengeance Series', 'Alat Fitness', 750000.00, 185, 'Rounin Monogram Boxing Gloves Series memiliki fitur: \r\n Tampilannya gahar dan keren dengan artwork cat tengkorak yang digunakan oleh pasukan elit tempur yang akan pergi berperang.\r\n Artwork logo pada pergelangan dicetak dengan menggunakan teknologi emboss yang presisi.\r\n Perekat pergelangan menggunakan bahan velcro terbaik dan awet digunakan.\r\n Padding foam khusus ini tidak hanya aman bagi tangan pemakainya tetapi juga melindungi partner sparring anda dari cedera serius. \r\n Bagian pergelangan menggunakan wrist joint system yang membuat pukulan menjadi lebih stabil. \r\n Dibalut dengan kulit yang menggunakan teknologi Rounin Armor yang tahan air dan tidak pecah-pecah (melotok) saat kering.\r\nBoxing gloves ini tersedia dalam ukuran:\r\n10 Oz, 12 Oz, 14 Oz.', '6744c1ec01f58.jpg', 4.90),
(14, 'Samsak Berdiri Reflek Bar Boxing Speed Ball Indofight', 'Alat Fitness', 2635000.00, 100, 'Spek,\r\n Membantu latihan pukulan dan tendangan dari berbagai arah\r\n Dilengkapi dengan reflex bar yang dapat memutar 360 derajat saat dipukul\r\n Pegas membuat samsak memantul fleksibel ketika dipukul\r\n Karet penghisap membuat sangat stabil\r\n Samsak dibuat dengan banyak lapisan sehingga tebal dan nyaman untuk dipukul, pantulan lembut tanpa berubah bentuk\r\n Ukuran besar dan cocok untuk simulasi combat yang sesungguhnya\r\n Tangki pemberat sangat stabil, bisa diisi pasir kering ataupun air.\r\n Berat tangki sekitar 200KG jika diisi dengan pasir kering dan sekitar 100KG jika diisi dengan air.\r\n Cocok untuk olahraga stand fighting seperti boxing, kickboxing, muay thai, taekwondo, karate, wushu, dll.\r\n Tinggi samsak bisa disesuaikan mulai dari 160-210cm\r\n Diameter samsak: 24cm\r\n Diameter tangki: 50cm', '6744c234e61f2.jpg', 5.00),
(15, 'lat Olahraga Fitness Bench Press Adjustable Multy Weight Fitness Bench Press', 'Alat Fitness', 1899000.00, 88, 'Hanya Bangku Bench Press, tidak termasuk Stik & beban.\r\nSpesifikasi Produk:\r\n Dimensi (Panjang x Lebar ): 145cm x 105cm (bisa diatur,\r\n Bisa dilipat, menghemat ruang,\r\n Bantalan kursinya empuk,\r\n Ketinggian pegangan Stik bisa disesuaikan,\r\n Bisa menahan barbel 300kg, tidak termasuk berat pengguna.\r\nFitur:\r\nMudah dirakit.\r\n Sandaran sandaran kursi dengan bantalan busa lembut untuk kenyamanan.\r\nMenolak untuk memperkuat perut atau tubuh bagian atas.\r\nFleksibel menghemat ruang.\r\nUjung karet anti selip melindungi lantai dari goresan.\r\nFlat sit up bench latihan perut crunch, cocok untuk komersial, kelembagaan ringan dan penggunaan di rumah.\r\nBagus untuk bench press, latihan punggung sit up, latihan dumbbell dan barbell.\r\nKonstruksi baja kokoh berkualitas tinggi.', '6744c2768f9ca.jpg', 5.00),
(16, 'Evolene BCAA 375gram - Suplemen Fitness  25 Sachet (serving) Official store makassar', 'Suplemen', 275000.00, 5500, 'Evolene BCAA adalah produk dengan formula khusus yang mengandung 7,4 gram BCAA dengan rasio 2:1:1 yang telah terbukti mampu mencegah pengikisan massa otot selama proses cutting, latihan yang intens atau diet dan mengurangi dehidrasi saat latihan keras.\r\nTiap serving mengandung\r\n3.7Gr LEUCINE, \r\n1.85Gr ISOLEUCINE, \r\n1.85Gr VALINE, \r\n2 Gr Glutamine, \r\n100mg L-carnitine.\r\nSuplemen BCAA biasanya dikonsumsi untuk meningkatkan pertumbuhan otot dan meningkatkan kinerja olahraga. Mereka juga dapat membantu menurunkan berat badan dan mengurangi kelelahan setelah berolahraga. BPOM & halal.', '6744c306cc037.jpg', 4.80),
(17, '[NEW] Crevolene Monohydrate Evolene - Creatine Monohydrate', 'Suplemen', 114000.00, 600, 'Creavolene Monohydrate dari Evolene mampu membantumu meningkatkan POWER & STRENGTH untuk olahraga. Zat creatine telah terbukti mampu menjadi asupan yang tepat bagi otot karena kandungan yang terdapat di dalamnya.\r\nKeunggulan nya\r\n5 gr creatine mampu meningkatkan ATP dan sumber energi pada otot juga menebalkan massa otot\r\nMudah larut dan mudah diserap tubuh\r\nBisa digunakan bersama Isolene/ Evowhey/ Evomass\r\nCreatine pertama di Indonesia dengan HPLC test rutin\r\n1 serving (5gr) Crevolene setara dengan kandungan Creatine pada 1kg daging sapi\r\nSaran penyajian:\r\n1 Serving (5 gr) per hari', '6744c34785f5a.jpg', 4.90),
(18, 'Crevolene Creapure Evolene Creatine (best produk)', 'Suplemen', 149000.00, 1400, 'Crevolene adalah produk creatine dari Evolene yang mampu membantumu meningkatkan POWER & STRENGTH untuk olahraga. Zat creatine telah terbukti mampu menjadi asupan yang tepat bagi otot karena kandungan yang terdapat di dalamnya.\r\nKeunggulan crevolene,\r\n5 gr creatine mampu meningkatkan ATP dan sumber energi pada otot juga menebalkan massa otot\r\nMudah larut & mudah diserap tubuh\r\nBisa digunakan bersama Isolene/ Evowhey/ Evomass\r\nTakaran Pemakaian nya\r\nHari 1-4 = 4 scoop\r\nHari 5-15 = 1 scoop\r\nHari 16-30 = off\r\nSeduh dengan air dingin/suhu normal sebanyak 20ml air.', '6744c43ad1739.jpg', 4.90),
(19, 'HTD Sport Mini Stepper Olahraga Air Climber Walker Glider Gym Alat Fitness', 'Alat Fitness', 335000.00, 290, '✨Garansi 6 Bulan✨\r\nSpesifikasi\r\nUkuran produk 37 * 30 * 16CM.\r\nBerat produk 6KG.\r\nBahan: struktur logam + plastik PVC.\r\nTampilan digital LED.\r\nResistensi 30 pound.\r\nKapasitas dukung beban 100KG.\r\nCatatan :\r\nKetersediaan Stok\r\nSelama Produk Tersebut Aktif Dan Tidak Terdapat Tulisan Pre-Order Maka Produk Tersebut Ready Siap Kirim\r\nBila Barang Sudah Sampai Harap Lakukan Video Unboxing Sejelas Mungkin, Dan Bila Terdapat Masalah Harap langsung Hubungi Kami Melalui Chat Dan Akan Di Bantu Proses / Klaim oleh admin.\r\nWajib menyerahkan video unboxing apabila memiliki keluhan terhadap produk, jika tidak ada mohon maaf kami tidak terima komplainan keluhan produk tersebut.', '6744c4e3ece87.jpg', 4.80),
(20, 'BG SPORT Power Squat Alat Olahraga Fitness Cardio', 'Alat Fitness', 598000.00, 245, '✨Garansi 12 Bulan✨𝐏𝐨𝐰𝐞𝐫 𝐒𝐪𝐮𝐚𝐭 𝐀𝐥𝐚𝐭 𝐎𝐥𝐚𝐡𝐫𝐚𝐠𝐚  𝐂𝐚𝐫𝐝𝐢𝐨 𝐌𝐨𝐝𝐞𝐥 𝐊𝐌-𝟐𝟎𝟏𝟎 \r\nFitur :\r\nHandlebar Yang Nyaman Dilapisi Dengan Busa EVA\r\nHandlebar Dan Kursi Yang Dapat Di Sesuaikan\r\nPedal Anti Slip Sehingga Tidak Licin\r\nAman Dari Getaran Dan Guncangan\r\nKaki Rangka Yang Terdapat Karet Anti Slip\r\nPower Squat \r\nTerdapat 3 tali resist di belakang bangku (Sesuaikan 1-3 tali resist sesuai Gravitasi yg anda butuhkan)\r\nStang berbentuk oval\r\nTerbuat Dari Baja Berkualitas Tinggi Kuat Dan Tahan Lama\r\nDapat mengatur jarak pada bangku supaya pengguna dapat duduk dengan nyaman.\r\nSize produk 102.5-125.5 Cm x 48.5 Cm x 98.5-111 cm \r\nSize Paket : 103 x 26 x 17 Cm\r\nN.W : 12.5Kg | G.W : 13.5Kg\r\nMaksimal Pengguna Hingga 100Kg.', '6744c55d74fab.jpg', 5.00),
(21, 'SPEEDS Sport Pull Up / Chin Up Hanging Bar Alat Olahraga Fitness / Gym Dirumah 042-26', 'Alat Fitness', 478000.00, 1800, '✨Garansi 12 Bulan✨ Alat Fitness Speeds Pull Up Multi Fungsi berkwalitas dan kokoh.\r\nMulti Fungsi : bisa untuk chin up, dipping, leg raise, push up dan pull up.\r\nalat olahraga bagus untuk membentuk otot lengan,otot perut.\r\nTeknik pengkondisian terbaik untuk membangun bahu dan lengan yang lebih kuat, dagu, gerakan menurunkan dan penarik menargetkan otot trisep, otot leher, dan otot bisep untuk mendukung penguatan seluruh tubuh bagian atas. Kami memahami stabilitas bisa menjadi masalah ketika menarik ke atas, menarik ke bawah itulah kami melengkapi dengan bantalan untuk kenyamanan, gagang nilon untuk mencengkeram daya dan katrol paduan aluminium kelas profesional.', '6744c5a4c7cae.jpg', 5.00),
(22, 'Evoboost Evolene 60 kapsul - Suplemen Fitness - Testosteron Booster', 'Suplemen', 329000.00, 985, 'EvoBoost dari Evolene adalah testosteron booster dengan 9 kandungan terbaik yang telah BPOM & Halal. Disarankan dikonsumsi untuk laki-laki mulai 30 tahun.Mengapa Harus Evoboost?\r\nHormon testosteron merupakan salah satu hormon pada pria yang sangat berperan penting dalam proses pembentukan otot. Testosteron yang rendah akan mengakibatkan lebih sedikit juga energi untuk latihan sehingga hasilnya tidak maksimal.\r\nDiformulasikan untuk bantu Anda:\r\n Maksimalkan pembentukan otot,\r\n Mencegah penumpukan lemak,\r\n Meningkatkan produksi hormon testosteron,\r\n Menjaga stamina pria,\r\n Meningkatkan kualitas tidur dan membantu recovery.\r\nEvoBoost diminum 2 x 1-2 kapsul, di pagi dan malam hari\r\n1 botol isi 60 kapsul.', '6744ce060c2c4.jpg', 4.90),
(23, 'EvoWhey Evolene Whey Protein 420gr/12 Sachet - Suplemen Fitness', 'Suplemen', 194000.00, 1500, 'Evowhey merupakan whey protein praktis untuk mendukung kebutuhan pembentukan badan dan ototmu  cocok untuk pemula hingga expert.\r\nKeunggulan evowhey evolene\r\nTinggi protein dengan kandungan 25 gr protein/ servingnya\r\nRendah kalori, hanya 140 kkal/ servingnya,\r\nMengandung BCAA 5,2 gr/ serving yang berfungsi membantu pertumbuhan otot, meredakan sakit dan nyeri saat olahraga\r\nSaran penyajian:\r\nKonsumsi minimal 1 serving per hari dan maksimal 2 serving per hari untuk hasil yang maksimal.\r\n Tuang 1 sachet Evowhey ke dalam shaker \r\nCampurkan dengan air dingin atau air biasa sebanyak 200 - 300 ml. Jangan campur dengan air panas karena akan merusak kandungannya. Kocok shaker hingga rata, lalu minum.', '6744ce39d0c2f.jpg', 5.00),
(24, 'Evolene Evogreen 50s 100% Plant Based Isolate Protein - IVS Certified', 'Suplemen', 649000.00, 180, 'Evogreen evolene \r\nProven 100% Vegan Friendly & Lactose Intolerant. Evogreen merupakan 100% plant based protein source dengan rasa pisang nan lezat yang dapat membantu mencukupi protein harian dan membantu pembentukan badan ideal.\r\nKandungan Evogreen:\r\n✅ Pea protein\r\n✅ Tinggi protein, 21 gr/ servingnya\r\n✅ Gluten free\r\n✅ Cocok untuk lactose intolerant\r\n✅ 2 gr Fiber\r\n✅ 4 gr Glutamine\r\n✅ 4,15 gr BCAA\r\nSaran penyajian:\r\nKonsumsi minimal 1 serving per hari dan maksimal 2 serving per hari untuk hasil yang maksimal.\r\nTuang 1 sachet Evogreen \r\nCampurkan dengan air dingin atau air biasa sebanyak 200-300ml. Jangan campur dengan air panas karena akan merusak kandungannya. \r\nKocok shaker hingga rata, lalu minum.', '6744ce7bd6237.jpg', 5.00),
(25, 'TrailTop Gym Ball Free Pompa Gym Ball Ibu Hamil 65/75cm Bola Yoga Alat Olahraga', 'Alat Fitness', 65900.00, 200, 'Gym Ball Ini Ideal Untuk Melatih, Mengencangkan dan Memperkuat Otot, Anda Dapat Menggunakan Beban Untuk Melatih Area Lain Dari Tubuh, Termasuk Dada, Bahu, Dan Lengan.\r\nManfaat Gym Ball,\r\nFitness Anda Dapat Melakukan Sit Up Dengan Bola Ini Sehingga Bagian Tulang Belakang Tetap Nyaman Dan Membentuk Perut Yang Ramping,\r\nMemperkuat Otot Letak Kan Kaki Di Atas Bola Dan Melakukan Push Up, Dengan Begitu Anda Akan Terasa Lebih Berat , Bagian Bahu Dan Lengan Akan Jauh Lebih Cepat Melebar Dan Membentuk Dada Yang Bidang Serta Terbentuk Lengan Yang Kokoh ( Terbukti ).\r\nRehabilitasi Bagi Anda Yang Sakit Pinggang , Dimana Selalau Tunduk Atau Membengkok Badan Ke Depan Utk Kegiataan Sehari Hari Ataupun Anda Yang Kerja Kantoran.\r\nDetail Produk :\r\nTersedia 2 Ukuran : Diamter 65cm Dan 75cm.\r\nHarga 1 Pcs Bola Yoga Bonus Pompa.\r\nBahan PVC.', '6744cf02abc08.jpg', 4.80),
(26, 'SPEEDS Ab Wheel 2in1 Ab Roller Wheels Plank Core Roller Alat Push Up Sit Up Stand', 'Alat Fitness', 121500.00, 4600, 'Ab Roller 2 Wheels Automatic Rebound With Plank Trainer + Timer Automati (Free Matras Lutut)\r\nDesain Body : Dengan struktur segitiga memberikan kekuatan dan kestabilan saat menopang berat beban\r\nRoda : Sistem pegas baja membentuk Spiral yang memberikan efek rebound ke titik awal\r\nLapisan Anti Slip pada beberapan lapisan roda yang memberikan efek aman di lantai dan pengguna\r\nSiku : Dilenkapi bantalan siku yang empuk dan nyaman menyesuaikan ukuran sikut\r\nKetika Ab Wheel sampai pada posisi maksimal akan otomatis kembali memantul ke posisi awal\r\nLcd Display,\r\nMin = Mengatur Waktu Menit\r\nSec = Mengatur Waktu Deti\r\nStop/Start= Menjalankan / Menghentikan Timer\r\nTekan Min&Sec Secara Bersama untuk reset waktu\r\nLatihan ini dapat memberikan manfaat ,\r\nOtot Lengan,\r\nOtot Dada,\r\nOtot Perut,\r\nOtot Punggung,\r\nOtot Pantat,\r\nOtot Paha.', '6744cf97c223c.jpg', 4.90),
(27, 'SPEEDS Matras Yoga Mat NBR Karpet Spons Tikar Alas Karpet 10MM Berstandar SNI', 'Alat Fitness', 44500.00, 28269, '✏Speeds Matt Yoga✏\r\nSangat Ringan karna terbuat dari karet Nitrile Butadiene Rubber yang komponennya dikenal tahan terhadap minyak dan zat asam.\r\nPermukaannya halus dan sangat flexible, membuat sangat nyaman dipakai untuk menopang tubuh, lutut, pinggang dan lainnya pada saat melakukan latihan.\r\n✏cocok untuk tulang ekor yang sakit karna ketebalannya✏\r\nTersedia 5 warna Biru, Ungu, Hitam, Abu abu dan Pink.\r\nVariasi :\r\n1 Tebal 6mm (185 x 62cm) *1sisi\r\n2 Tebal 6mm (185 x 62cm) *2sisi \r\n3 Tebal 6mm (185 x 68cm) *1sisi\r\n4 Tebal 6mm (185 x 68cm) *2sisi \r\n5 Tebal 10mm (185 x 62cm) Lis,\r\n21Tebal 8mm (185 x 62cm) Polos,\r\n6 Microfiber (185 x 62cm).\r\nKode varian Warna + tas (T)\r\nHJ : Hijau/ HJT : Hijau + Tas,\r\nB : Biru / BT : Biru + Tas,\r\nA : Abu/ AT : Abu + Tas,\r\nP : Pink/ PT : Pink + Tas,\r\nU : Ungu / UT : Ungu + Tas. Produk SNI memberikan jaminan keunggulan dan kepercayaan.', '6744cffa43d6d.jpg', 5.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_ulasan_222247`
--

CREATE TABLE `tbl_ulasan_222247` (
  `222247_ulasan_id` int(11) NOT NULL,
  `222247_produk_id` int(11) NOT NULL,
  `222247_pengguna_id` int(11) NOT NULL,
  `222247_ulasan_text` text NOT NULL,
  `222247_rating` tinyint(4) NOT NULL CHECK (`222247_rating` between 1 and 5),
  `222247_tanggal_ulasan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_ulasan_222247`
--

INSERT INTO `tbl_ulasan_222247` (`222247_ulasan_id`, `222247_produk_id`, `222247_pengguna_id`, `222247_ulasan_text`, `222247_rating`, `222247_tanggal_ulasan`) VALUES
(6, 8, 6, 'baik', 5, '2024-12-09 00:11:12');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_info_222247`
--
ALTER TABLE `tbl_info_222247`
  ADD PRIMARY KEY (`222247_id_info`),
  ADD KEY `222247_id_pengguna` (`222247_id_pembayaran`);

--
-- Indeks untuk tabel `tbl_pegawai_222247`
--
ALTER TABLE `tbl_pegawai_222247`
  ADD PRIMARY KEY (`222247_id_pegawai`),
  ADD UNIQUE KEY `222247_username` (`222247_username`);

--
-- Indeks untuk tabel `tbl_pembayaran_222247`
--
ALTER TABLE `tbl_pembayaran_222247`
  ADD PRIMARY KEY (`222247_id_pembayaran`),
  ADD KEY `222247_id_pesanan` (`222247_id_pesanan`);

--
-- Indeks untuk tabel `tbl_pengguna_222247`
--
ALTER TABLE `tbl_pengguna_222247`
  ADD PRIMARY KEY (`222247_id_pengguna`),
  ADD UNIQUE KEY `222247_username` (`222247_username`);

--
-- Indeks untuk tabel `tbl_pesanan_222247`
--
ALTER TABLE `tbl_pesanan_222247`
  ADD PRIMARY KEY (`222247_id_pesanan`),
  ADD KEY `222247_id_pengguna` (`222247_id_pengguna`),
  ADD KEY `222247_id_produk` (`222247_id_produk`);

--
-- Indeks untuk tabel `tbl_produk_222247`
--
ALTER TABLE `tbl_produk_222247`
  ADD PRIMARY KEY (`222247_id_produk`);

--
-- Indeks untuk tabel `tbl_ulasan_222247`
--
ALTER TABLE `tbl_ulasan_222247`
  ADD PRIMARY KEY (`222247_ulasan_id`),
  ADD KEY `222247_produk_id` (`222247_produk_id`),
  ADD KEY `222247_pengguna_id` (`222247_pengguna_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_info_222247`
--
ALTER TABLE `tbl_info_222247`
  MODIFY `222247_id_info` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `tbl_pegawai_222247`
--
ALTER TABLE `tbl_pegawai_222247`
  MODIFY `222247_id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_pembayaran_222247`
--
ALTER TABLE `tbl_pembayaran_222247`
  MODIFY `222247_id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT untuk tabel `tbl_pengguna_222247`
--
ALTER TABLE `tbl_pengguna_222247`
  MODIFY `222247_id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `tbl_pesanan_222247`
--
ALTER TABLE `tbl_pesanan_222247`
  MODIFY `222247_id_pesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `tbl_produk_222247`
--
ALTER TABLE `tbl_produk_222247`
  MODIFY `222247_id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `tbl_ulasan_222247`
--
ALTER TABLE `tbl_ulasan_222247`
  MODIFY `222247_ulasan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_info_222247`
--
ALTER TABLE `tbl_info_222247`
  ADD CONSTRAINT `tbl_info_222247_ibfk_1` FOREIGN KEY (`222247_id_pembayaran`) REFERENCES `tbl_pembayaran_222247` (`222247_id_pembayaran`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_pembayaran_222247`
--
ALTER TABLE `tbl_pembayaran_222247`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`222247_id_pesanan`) REFERENCES `tbl_pesanan_222247` (`222247_id_pesanan`);

--
-- Ketidakleluasaan untuk tabel `tbl_pesanan_222247`
--
ALTER TABLE `tbl_pesanan_222247`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`222247_id_pengguna`) REFERENCES `tbl_pengguna_222247` (`222247_id_pengguna`),
  ADD CONSTRAINT `tbl_pesanan_222247_ibfk_1` FOREIGN KEY (`222247_id_produk`) REFERENCES `tbl_produk_222247` (`222247_id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_ulasan_222247`
--
ALTER TABLE `tbl_ulasan_222247`
  ADD CONSTRAINT `tbl_ulasan_222247_ibfk_1` FOREIGN KEY (`222247_produk_id`) REFERENCES `tbl_produk_222247` (`222247_id_produk`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_ulasan_222247_ibfk_2` FOREIGN KEY (`222247_pengguna_id`) REFERENCES `tbl_pengguna_222247` (`222247_id_pengguna`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
