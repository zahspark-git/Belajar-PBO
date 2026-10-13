<?php
session_start();

// Waktu Real-Time
date_default_timezone_set('Asia/Jakarta');

// Daftar 50 Kode Member
$kodeMemberTerdaftar = [
    "JT8K2MP9", "X9AL4VQ2", "RN3P7WZ5", "B5MQ2XY8", "K8VR9TW3",
    "Z2PL5SN7", "M4XT8KQ2", "P7WB3VR9", "Y3KN9MZ4", "L6RP2XT8",
    "H9MZ5VW3", "Q2XB8KP7", "W5PR3TY2", "V8TL9MZ6", "R4KP2WX9",
    "N7MB5VQ3", "T3XZ8RW4", "K9PL2TY8", "M2WR5KZ6", "P8VT3MX5",
    "X5RP9WQ2", "Z3MB2VR8", "L8KZ5TW4", "B4XR8MY9", "W9PL3VQ2",
    "Y2TB9WZ7", "R8MP5XT3", "N3KZ2VW8", "K7VR8MP4", "M9PL4WQ2",
    "P2XB9TZ6", "T5MR3VX8", "Z8KL2WY4", "X4PB8MR9", "L9VZ3TW2",
    "B2MR5XQ7", "W8KP9VZ3", "Y5RL2TX8", "R3MB8WP4", "N9VZ5MQ2",
    "K2PR8TW6", "M5KL3XY9", "P9MZ2VR4", "T4XB8WQ7", "Z9PL5TZ2",
    "X2MR3VW8", "L5KB9XP4", "B8VZ2MT9", "W3PR5WY2", "Y9ML8VQ5"
];

function cekKodeMemberValid($inputKode, $daftarKode) {
    $kodeBersih = strtoupper(trim($inputKode));
    return in_array($kodeBersih, $daftarKode);
}

// Daftar Toping Seblak Prasmanan Jeletot
$listTopping = [
    "T01" => ["nama" => "Kerupuk Bawang", "harga" => 2000],
    "T02" => ["nama" => "Kerupuk Mawar", "harga" => 2000],
    "T03" => ["nama" => "Kerupuk Oren", "harga" => 2000],
    "T04" => ["nama" => "Kerupuk ikan", "harga" => 2000],
    "T05" => ["nama" => "Kerupuk Tangga", "harga" => 2000],
    "T06" => ["nama" => "Makaroni Hitam", "harga" => 2000],
    "T07" => ["nama" => "Makaroni Kuning", "harga" => 2000],
    "T08" => ["nama" => "Babangi", "harga" => 2000],
    "T09" => ["nama" => "Mie", "harga" => 2000],
    "T10" => ["nama" => "Bihun", "harga" => 2000],
    "T11" => ["nama" => "Kwetiau", "harga" => 2000],
    "T12" => ["nama" => "Bakso keju", "harga" => 2000],
    "T13" => ["nama" => "Bakso urat", "harga" => 2000],
    "T14" => ["nama" => "Sosis Ayam", "harga" => 2000],
    "T15" => ["nama" => "Dumpling Cheese", "harga" => 2000],
    "T16" => ["nama" => "Chikuwa", "harga" => 1000],
    "T17" => ["nama" => "Siomay Basah", "harga" => 2000],
    "T18" => ["nama" => "Fishroll", "harga" => 2000],
    "T19" => ["nama" => "Baso Aci", "harga" => 2000],
    "T20" => ["nama" => "Japlak", "harga" => 1000],
    "T21" => ["nama" => "Lidah Kering", "harga" => 1000],
    "T22" => ["nama" => "Ceker Ayam", "harga" => 2000],
    "T23" => ["nama" => "Telur Ayam", "harga" => 3000],
    "T24" => ["nama" => "Jamur Enoki", "harga" => 3000],
    "T25" => ["nama" => "Tulang", "harga" => 3000],
    "T26" => ["nama" => "Cirawang", "harga" => 2000],
    "T27" => ["nama" => "Sayur", "harga" => 0],
];

if (!isset($_SESSION['pesanan']) || empty($_SESSION['pesanan'])) {
    $_SESSION['pesanan'] = [
        0 => ['items' => [], 'tingkatPedas' => 'Sedang', 'tipeKuah' => 'Nyemek', 'pemanis' => 'Kecap Manis']
    ];
}
if (!isset($_SESSION['mangkuk_aktif'])) {
    $_SESSION['mangkuk_aktif'] = 0;
}

if (!isset($_SESSION['tab_aktif'])) {
    $_SESSION['tab_aktif'] = 1;
}

// Hanya izinkan berpindah ke Tab 3 jika status pembayaran sudah LUNAS terverifikasi
if (isset($_POST['set_tab'])) {
    $targetTab = (int)$_POST['set_tab'];
    if ($targetTab == 3 && empty($_SESSION['hasil_transaksi']['is_lunas'])) {
        $_SESSION['tab_aktif'] = 2; // Tetap kunci di tab 2 jika belum lunas
    } else {
        $_SESSION['tab_aktif'] = $targetTab;
    }
}

if (isset($_POST['tambah_mangkuk_baru'])) {
    $_SESSION['pesanan'][] = ['items' => [], 'tingkatPedas' => 'Sedang', 'tipeKuah' => 'Nyemek', 'pemanis' => 'Kecap Manis'];
    $_SESSION['mangkuk_aktif'] = count($_SESSION['pesanan']) - 1;
}

if (isset($_POST['pilih_mangkuk'])) {
    $_SESSION['mangkuk_aktif'] = (int)$_POST['pilih_mangkuk'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_topping'])) {
    $kodeTopping = $_POST['kode_topping'];
    $qty         = (int)$_POST['jumlah'];
    $aktif       = $_SESSION['mangkuk_aktif'];

    if (isset($listTopping[$kodeTopping])) {
        $topping = $listTopping[$kodeTopping];
        $_SESSION['pesanan'][$aktif]['items'][] = [
            'nama'   => $topping['nama'],
            'harga'  => $topping['harga'],
            'jumlah' => $qty
        ];
    }
}

if (isset($_POST['hapus_topping'])) {
    $mIdx = (int)$_POST['mangkuk_idx'];
    $itemIdx = (int)$_POST['item_idx'];
    if (isset($_SESSION['pesanan'][$mIdx]['items'][$itemIdx])) {
        array_splice($_SESSION['pesanan'][$mIdx]['items'], $itemIdx, 1);
    }
}

if (isset($_POST['hapus_mangkuk'])) {
    $mIdx = (int)$_POST['mangkuk_idx'];
    if (count($_SESSION['pesanan']) > 1) {
        array_splice($_SESSION['pesanan'], $mIdx, 1);
        if ($_SESSION['mangkuk_aktif'] >= count($_SESSION['pesanan'])) {
            $_SESSION['mangkuk_aktif'] = count($_SESSION['pesanan']) - 1;
        }
    }
}

if (isset($_POST['reset_pesanan'])) {
    unset($_SESSION['pesanan'], $_SESSION['mangkuk_aktif'], $_SESSION['hasil_transaksi']);
    $_SESSION['tab_aktif'] = 1;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

function hitungTotalPesanan($semuaMangkuk, $isMemberValid) {
    $subtotalGlobal = 0;
    $detailMangkuk = [];

    foreach ($semuaMangkuk as $idx => $mangkuk) {
        $subtotalMangkuk = 0;
        foreach ($mangkuk['items'] as $item) {
            $subtotalMangkuk += ($item['harga'] * $item['jumlah']);
        }
        $subtotalGlobal += $subtotalMangkuk;
        $detailMangkuk[$idx] = ['subtotal' => $subtotalMangkuk, 'total' => $subtotalMangkuk];
    }

    $totalSebelumDiskon = $subtotalGlobal;
    $diskon = 0;

    if ($isMemberValid && $totalSebelumDiskon >= 30000) {
        $diskon = 0.10;
    } elseif ($isMemberValid) {
        $diskon = 0.05;
    }

    $nominalDiskon = $totalSebelumDiskon * $diskon;
    $totalBayar = $totalSebelumDiskon - $nominalDiskon;

    return [
        'detailMangkuk'      => $detailMangkuk,
        'subtotalGlobal'     => $subtotalGlobal,
        'totalSebelumDiskon' => $totalSebelumDiskon,
        'nominalDiskon'      => $nominalDiskon,
        'totalBayar'         => $totalBayar
    ];
}

if (isset($_POST['simpan_racikan'])) {
    if (isset($_POST['racikan'])) {
        foreach ($_POST['racikan'] as $idx => $opts) {
            $_SESSION['pesanan'][$idx]['tipeKuah']     = $opts['tipe_kuah'];
            $_SESSION['pesanan'][$idx]['tingkatPedas'] = $opts['tingkat_pedas'];
            $_SESSION['pesanan'][$idx]['pemanis']      = $opts['pemanis'];
        }
    }
    $_SESSION['tab_aktif'] = 2;
}

// PROSES VERIFIKASI PEMBAYARAN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['proses_seblak'])) {
    $namaPemesan    = $_POST['nama_pemesan'];
    $kodeInput      = strtoupper(trim($_POST['kode_member']));
    $metodeBayar    = $_POST['metode_bayar'];
    $waktuTransaksi = date('d-m-Y H:i:s') . " WIB";

    $isMemberValid = false;
    if (!empty($kodeInput)) {
        if (cekKodeMemberValid($kodeInput, $kodeMemberTerdaftar)) {
            $isMemberValid = true;
            $pesanMember   = "$kodeInput";
        } else {
            $isMemberValid = false;
            $pesanMember   = "Kode Tidak Terdaftar!";
        }
    } else {
        $pesanMember = "Non-Member";
    }

    $hasil = hitungTotalPesanan($_SESSION['pesanan'], $isMemberValid);
    $_SESSION['hasil_transaksi'] = [
        'is_lunas'       => true,
        'namaPemesan'    => $namaPemesan,
        'pesanMember'    => $pesanMember,
        'metodeBayar'    => $metodeBayar,
        'waktuTransaksi' => $waktuTransaksi,
        'hasil'          => $hasil,
        'pesanan'        => $_SESSION['pesanan']
    ];

    $_SESSION['tab_aktif'] = 3; // Buka Tab Struk
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kedai Seblak Prasmanan - Crimson Edition</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body { 
            font-family: 'Georgia', 'Times New Roman', serif; 
            margin: 20px; 
            background-color: #F2F1ED; 
            color: #161616; 
        }
        .form-container { 
            max-width: 680px; 
            background: #ffffff; 
            padding: 25px; 
            border: 2px solid #710014; 
            margin: 0 auto 25px auto; 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(22, 22, 22, 0.08);
        }

        /* Disesuaikan Agar Navigasi Tab Presisi di Tengah */
        .main-tabs { 
            display: flex; 
            justify-content: center;
            align-items: center;
            gap: 12px; 
            margin: 0 auto 20px auto; 
            border-bottom: 2px solid #710014; 
            max-width: 520px;
        }
        .main-tabs form {
            flex: 1;
            margin: 0;
        }
        .main-tab-btn { 
            width: 100%; 
            padding: 12px 15px; 
            background: #B38F6F; 
            color: #ffffff; 
            border: none; 
            font-weight: bold; 
            font-size: 14px; 
            cursor: pointer; 
            border-radius: 8px 8px 0 0;
            text-align: center;
            box-sizing: border-box;
        }
        .main-tab-btn.active { background: #710014; color: #F2F1ED; }

        .form-group { margin-bottom: 14px; }
        .form-group label { display: inline-block; width: 180px; font-size: 14px; color: #710014; font-weight: bold; }
        .form-group select, .form-group input[type="text"], .form-group input[type="number"] { 
            width: 320px; 
            padding: 8px; 
            background-color: #ffffff; 
            color: #161616; 
            border: 1px solid #B38F6F; 
            border-radius: 6px;
            font-family: inherit;
        }
        .btn-add { 
            padding: 8px 16px; 
            background: #710014; 
            color: #F2F1ED; 
            border: none; 
            font-weight: bold; 
            cursor: pointer; 
            border-radius: 6px; 
            transition: 0.3s;
        }
        .btn-add:hover { background: #161616; }
        .btn-submit { 
            width: 100%; 
            padding: 12px; 
            background: #710014; 
            color: #F2F1ED; 
            border: none; 
            font-weight: bold; 
            font-size: 15px;
            cursor: pointer; 
            margin-top: 15px; 
            border-radius: 6px; 
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-submit:hover { background: #161616; }
        .btn-reset { 
            padding: 5px 12px; 
            background: transparent; 
            color: #710014; 
            border: 1px solid #710014; 
            cursor: pointer; 
            float: right; 
            border-radius: 4px; 
            font-size: 12px;
        }
        .btn-danger { 
            padding: 4px 8px; 
            background: #710014; 
            color: #ffffff; 
            border: none; 
            font-size: 12px; 
            cursor: pointer; 
            border-radius: 4px; 
        }
        .btn-danger:hover { background: #161616; }
        .btn-tab { 
            padding: 8px 12px; 
            margin-right: 5px; 
            border: 1px solid #B38F6F; 
            background: #B38F6F; 
            color: #ffffff; 
            cursor: pointer; 
            border-radius: 6px 6px 0 0; 
            font-weight: bold;
        }
        .btn-tab.active { background: #710014; color: #F2F1ED; border-color: #710014; }
        .table-mangkuk { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; color: #161616; }
        .table-mangkuk th, .table-mangkuk td { border: 1px solid #B38F6F; padding: 8px; text-align: left; }
        .table-mangkuk th { background-color: #B38F6F; color: #ffffff; }
        .mangkuk-box { 
            border: 1px solid #B38F6F; 
            padding: 15px; 
            margin-bottom: 15px; 
            border-radius: 8px; 
            background: #faf8f5; 
            position: relative; 
        }
        .info-bayar-box {
            background: #faf8f5;
            border: 1px solid #B38F6F;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 13px;
            text-align: center;
        }
        .btn-app-external {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 15px;
            background: #161616;
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            font-size: 12px;
        }
        .btn-app-external:hover { background: #710014; }
        .struk-wrapper { max-width: 420px; margin: auto; }
        .struk-card { 
            background: #ffffff; 
            padding: 25px; 
            border: 2px solid #710014; 
            font-family: 'Courier New', Courier, monospace; 
            color: #161616;
            box-shadow: 0 5px 20px rgba(22, 22, 22, 0.12);
            border-radius: 8px;
        }
        .struk-header, .struk-footer { text-align: center; }
        .btn-group-download { display: flex; gap: 10px; margin-top: 15px; }
        .btn-png { flex: 1; padding: 10px; background: #161616; color: white; border: none; font-weight: bold; cursor: pointer; border-radius: 6px; }
        .btn-jpg { flex: 1; padding: 10px; background: #710014; color: #F2F1ED; border: none; font-weight: bold; cursor: pointer; border-radius: 6px; }

        /* CSS Khusus Tabel Informasi Struk */
        .tb-info-struk {
            width: 100% !important;
            border-collapse: collapse !important;
            border: none !important;
            font-family: 'Courier New', Courier, monospace !important;
            font-size: 13px !important;
            margin-bottom: 5px;
        }
        .tb-info-struk td {
            border: none !important;
            padding: 2px 0 !important;
            vertical-align: top !important;
            background: transparent !important;
        }
        .tb-info-struk .lbl {
            width: 110px !important;
            white-space: nowrap !important;
        }
        .tb-info-struk .titik {
            width: 15px !important;
            text-align: center !important;
        }
        .tb-info-struk .val {
            text-align: left !important;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2 style="text-align: center; color: #710014; margin-top: 0; letter-spacing: 1px;">SEBLAK PRASMANAN JELETOT</h2>
        <p style="text-align: center; color: #B38F6F; margin-top: -10px; font-style: italic; font-size: 13px;">—  —</p>

        <!-- Navigasi 3 Tab Utama -->
        <div class="main-tabs">
            <form method="POST"><button type="submit" name="set_tab" value="1" class="main-tab-btn <?php echo ($_SESSION['tab_aktif'] == 1) ? 'active' : ''; ?>">1. Pemesanan</button></form>
            <form method="POST"><button type="submit" name="set_tab" value="2" class="main-tab-btn <?php echo ($_SESSION['tab_aktif'] == 2) ? 'active' : ''; ?>">2. Pembayaran</button></form>
            <form method="POST"><button type="submit" name="set_tab" value="3" class="main-tab-btn <?php echo ($_SESSION['tab_aktif'] == 3) ? 'active' : ''; ?>" <?php echo empty($_SESSION['hasil_transaksi']['is_lunas']) ? 'disabled style="opacity:0.4; cursor:not-allowed;" title="Selesaikan pembayaran dulu"' : ''; ?>>3. Struk</button></form>
        </div>

        <!-- TAB 1: PEMESANAN -->
        <?php if ($_SESSION['tab_aktif'] == 1): ?>
            <form method="POST" style="margin-bottom: 15px;">
                <label style="color:#710014;"><strong>Pilih Mangkuk yang Diisi:</strong></label><br><br>
                <?php foreach ($_SESSION['pesanan'] as $idx =>$m): ?>
                    <button type="submit" name="pilih_mangkuk" value="<?php echo $idx; ?>" 
                            class="btn-tab <?php echo ($idx ==$_SESSION['mangkuk_aktif']) ? 'active' : ''; ?>">
                        Mangkuk #<?php echo ($idx + 1); ?>
                    </button>
                <?php endforeach; ?>
                <button type="submit" name="tambah_mangkuk_baru" class="btn-add" style="padding: 7px 12px;">+ Mangkuk Baru</button>
            </form>

            <form method="POST">
                <p style="margin: 0 0 10px 0; color: #B38F6F;"><em>Mengisi Mangkuk #<?php echo ($_SESSION['mangkuk_aktif'] + 1); ?></em></p>
                <div class="form-group">
                    <label>Pilih Topping:</label>
                    <select name="kode_topping" required>
                        <?php foreach ($listTopping as $kode =>$detail): ?>
                            <option value="<?php echo $kode; ?>">
                                <?php echo htmlspecialchars($detail['nama']) . " (Rp " . number_format($detail['harga'], 0, ',', '.') . ")"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jumlah (Porsi/Pcs):</label>
                    <input type="number" name="jumlah" min="1" value="1" required>
                </div>
                <button type="submit" name="tambah_topping" value="1" class="btn-add">+ Masukkan ke Mangkuk #<?php echo ($_SESSION['mangkuk_aktif'] + 1); ?></button>
            </form>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #B38F6F;">

            <form method="POST">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <h4 style="color: #710014; margin: 0;">Daftar Mangkuk Pesanan:</h4>
                    <button type="submit" name="reset_pesanan" class="btn-reset">Reset Semua Pesanan</button>
                </div>
                <br>

                <?php foreach ($_SESSION['pesanan'] as $idx =>$mangkuk): ?>
                    <div class="mangkuk-box">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <strong style="color: #710014;">Mangkuk #<?php echo ($idx + 1); ?></strong>
                            <?php if (count($_SESSION['pesanan']) > 1): ?>
                                <button type="submit" name="hapus_mangkuk" value="1" class="btn-danger" style="padding:4px 8px;">
                                    ✖ Hapus Mangkuk Ini
                                </button>
                                <input type="hidden" name="mangkuk_idx" value="<?php echo $idx; ?>">
                            <?php endif; ?>
                        </div>
                        
                        <table class="table-mangkuk">
                            <thead>
                                <tr>
                                    <th>Item Topping</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th style="text-align:center; width:60px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($mangkuk['items'])): ?>
                                    <tr><td colspan="5" style="text-align:center;"><em>Belum ada topping di mangkuk ini.</em></td></tr>
                                <?php else: ?>
                                    <?php foreach ($mangkuk['items'] as $itemIdx =>$item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                            <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo $item['jumlah']; ?></td>
                                            <td>Rp <?php echo number_format($item['harga'] *$item['jumlah'], 0, ',', '.'); ?></td>
                                            <td style="text-align:center;">
                                                <button type="submit" name="hapus_topping" value="1" class="btn-danger" 
                                                        onclick="this.form.mangkuk_idx_del.value='<?php echo $idx; ?>'; this.form.item_idx_del.value='<?php echo$itemIdx; ?>';">
                                                    Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div style="margin-top: 10px;">
                            <div class="form-group">
                                <label>Tipe Kuah:</label>
                                <select name="racikan[<?php echo $idx; ?>][tipe_kuah]">
                                    <option value="Nyemek" <?php echo ($mangkuk['tipeKuah'] == 'Nyemek') ? 'selected' : ''; ?>>Nyemek</option>
                                    <option value="Kuah" <?php echo ($mangkuk['tipeKuah'] == 'Kuah Banyak') ? 'selected' : ''; ?>>Kuah</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tingkat Pedas:</label>
                                <select name="racikan[<?php echo $idx; ?>][tingkat_pedas]">
                                    <option value="Level 0" <?php echo ($mangkuk['tingkatPedas'] == 'Level 0') ? 'selected' : ''; ?>>Level 0</option>
                                    <option value="Level 1" <?php echo ($mangkuk['tingkatPedas'] == 'Level 1') ? 'selected' : ''; ?>>Level 1</option>
                                    <option value="Level 2" <?php echo ($mangkuk['tingkatPedas'] == 'Level 2') ? 'selected' : ''; ?>>Level 2</option>
                                    <option value="Level 3" <?php echo ($mangkuk['tingkatPedas'] == 'Level 3') ? 'selected' : ''; ?>>Level 3</option>
                                    <option value="Level 4" <?php echo ($mangkuk['tingkatPedas'] == 'Level 4') ? 'selected' : ''; ?>>Level 4</option>
                                    <option value="Level 5" <?php echo ($mangkuk['tingkatPedas'] == 'Level 5') ? 'selected' : ''; ?>>Level 5</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Pemanis Tambahan:</label>
                                <select name="racikan[<?php echo $idx; ?>][pemanis]">
                                    <option value="Kecap Manis" <?php echo ($mangkuk['pemanis'] == 'Kecap Manis') ? 'selected' : ''; ?>>Kecap Manis</option>
                                    <option value="Gula Pasir" <?php echo ($mangkuk['pemanis'] == 'Gula Pasir') ? 'selected' : ''; ?>>Gula Pasir</option>
                                    <option value="Tanpa Manis" <?php echo ($mangkuk['pemanis'] == 'Tanpa Manis') ? 'selected' : ''; ?>>Tanpa Manis</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <input type="hidden" name="mangkuk_idx" id="mangkuk_idx_del" value="">
                <input type="hidden" name="item_idx" id="item_idx_del" value="">

                <button type="submit" name="simpan_racikan" class="btn-submit">LANJUT KE PEMBAYARAN</button>
            </form>
        <?php endif; ?>

        <!-- TAB 2: PEMBAYARAN -->
        <?php if ($_SESSION['tab_aktif'] == 2): ?>
            <h3 style="color: #710014; margin-top: 0; text-align:center;">Lakukan Pembayaran</h3>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Nama Pemesan:</label>
                    <input type="text" name="nama_pemesan" value="Azizah" required>
                </div>
                <div class="form-group">
                    <label>Kode Member (Eksklusif):</label>
                    <input type="text" name="kode_member" id="inputKodeMember" placeholder="Masukkan Kode Member">
                </div>
                <div class="form-group">
                    <label>Metode Pembayaran:</label>
                    <select name="metode_bayar" id="selectMetodeBayar" onchange="updateInfoBayar()" required>
                        <option value="Tunai / Cash">Tunai</option>
                        <option value="QRIS / E-Wallet">QRIS</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                    </select>
                </div>

                <div class="info-bayar-box">
                    <h5 style="margin:0 0 8px 0; color:#710014; font-size:14px; text-align:left;">Area Pembayaran & Verifikasi:</h5>
                    <div id="infoDetailBayar"></div>

                    <!-- Area Upload Bukti Transfer jika memilih non-cash -->
                    <div id="areaUploadBukti" style="margin-top: 15px; text-align: left; border-top: 1px dashed #B38F6F; padding-top: 10px;">
                        <label style="font-weight: bold; color: #710014;">Upload Bukti Pembayaran <span id="labelWajib" style="color:red;">*</span>:</label><br>
                        <input type="file" name="bukti_bayar" id="fileBuktiBayar" accept="image/*" style="margin-top: 5px;">
                    </div>
                </div>

                <div style="background:#faf8f5; border:1px solid #B38F6F; padding:15px; border-radius:8px; margin:15px 0;">
                    <h5 style="margin:0 0 10px 0; color:#710014;">Ringkasan Mangkuk Pesanan:</h5>
                    <?php 
                    $subtotalSatu = 0;
                    foreach ($_SESSION['pesanan'] as$i => $mk) {$mSub = 0;
                        foreach ($mk['items'] as $it) {$mSub += ($it['harga'] *$it['jumlah']); }
                        $subtotalSatu +=$mSub;
                        echo "<p style='margin:4px 0; font-size:13px;'><strong>Mangkuk #" . ($i+1) . ":</strong> Rp " . number_format($mSub, 0, ',', '.') . " <em>(" . count($mk['items']) . " topping)</em></p>";
                    }
                    ?>
                    <hr style="border-top:1px dashed #B38F6F; margin:8px 0;">
                    <p style="margin:0; font-weight:bold; color:#710014;">Total: Rp <?php echo number_format($subtotalSatu, 0, ',', '.'); ?></p>
                </div>

                <button type="submit" name="proses_seblak" class="btn-submit">VERIFIKASI</button>
            </form>

            <script>
                function updateInfoBayar() {
                    const metode = document.getElementById('selectMetodeBayar').value;
                    const infoBox = document.getElementById('infoDetailBayar');
                    const areaUpload = document.getElementById('areaUploadBukti');
                    const fileInput = document.getElementById('fileBuktiBayar');
                    const labelWajib = document.getElementById('labelWajib');

                    if (metode === 'QRIS / E-Wallet') {
                        areaUpload.style.display = 'block';
                        fileInput.required = true;
                        labelWajib.style.display = 'inline';
                        infoBox.innerHTML = `
                            <p style="margin: 5px 0;">Silakan scan QR Code di bawah ini menggunakan aplikasi E-Wallet atau M-Banking Anda:</p>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=SEBLAK-JELETOT-PAYMENT" alt="QRIS Code" style="margin: 10px 0; border: 3px solid #710014; border-radius: 8px;"><br>
                            <span style="font-size:12px; color:#B38F6F;"><strong>NMID:</strong> ID10293847561 | <strong>A/N:</strong> Seblak Prasmanan Jeletot</span>
                        `;
                    } else if (metode === 'Transfer Bank') {
                        areaUpload.style.display = 'block';
                        fileInput.required = true;
                        labelWajib.style.display = 'inline';
                        infoBox.innerHTML = `
                            <p style="margin: 5px 0;">Klik tombol di bawah ini untuk membuka aplikasi pihak ke-3:</p>
                            <div style="display:flex; justify-content:center; gap:8px; flex-wrap:wrap;">
                                <a href="https://m.bca.co.id" target="_blank" class="btn-app-external">📱 Buka BCA Mobile</a>
                                <a href="https://livin.bankmandiri.co.id" target="_blank" class="btn-app-external">📱 Buka Livin' Mandiri</a>
                                <a href="https://mbanking.bni.co.id" target="_blank" class="btn-app-external">📱 Buka BNI Mobile</a>
                            </div>
                            <p style="margin-top:10px; font-size:12px; color:#B38F6F;">No. Rekening BCA: <strong>8830-1928-44</strong> (a/n Seblak Jeletot)</p>
                        `;
                    } else if (metode === 'Tunai / Cash') {
                        areaUpload.style.display = 'none';
                        fileInput.required = false;
                        fileInput.value = '';
                        labelWajib.style.display = 'none';
                        infoBox.innerHTML = '<p style="margin:5px 0;">Serahkan uang tunai fisik secara langsung ke meja kasir untuk memproses transaksi.</p>';
                    }
                }

                updateInfoBayar();
            </script>
        <?php endif; ?>

        <!-- TAB 3: STRUK (HANYA MUNCUL JIKA PEMBAYARAN SUDAH LUNAS TERVERIFIKASI) -->
        <?php if ($_SESSION['tab_aktif'] == 3 && !empty($_SESSION['hasil_transaksi']['is_lunas'])): ?>
            <?php $tr =$_SESSION['hasil_transaksi']; ?>
            <div class="struk-wrapper">
                <div class="struk-card" id="areaStruk">
                    <div class="struk-header">
                        <h3 style="margin: 0; color:#710014;">SEBLAK PRASMANAN JELETOT</h3>
                        <p style="margin: 5px 0; font-size: 12px; color:#161616;">Jl. Raya Sukabumi No. 123</p>
                        <hr style="border-top: 1px dashed #710014;">
                    </div>

                    <!-- Informasi Pelanggan Menggunakan Kelas Khusus .tb-info-struk -->
                    <table class="tb-info-struk">
                        <tr>
                            <td class="lbl">Pelanggan</td>
                            <td class="titik">:</td>
                            <td class="val"><?php echo htmlspecialchars($tr['namaPemesan']); ?></td>
                        </tr>
                        <tr>
                            <td class="lbl">Kode Member</td>
                            <td class="titik">:</td>
                            <td class="val"><?php echo htmlspecialchars($tr['pesanMember']); ?></td>
                        </tr>
                        <tr>
                            <td class="lbl">Pembayaran</td>
                            <td class="titik">:</td>
                            <td class="val"><?php echo htmlspecialchars($tr['metodeBayar']); ?></td>
                        </tr>
                        <tr>
                            <td class="lbl">Waktu</td>
                            <td class="titik">:</td>
                            <td class="val"><?php echo $tr['waktuTransaksi']; ?></td>
                        </tr>
                    </table>

                    <hr style="border-top: 1px dashed #710014; margin-top: 8px;">

                    <?php foreach ($tr['pesanan'] as $idx =>$mangkuk): ?>
                        <p style="margin: 5px 0; font-weight: bold; color:#710014;">MANGKUK #<?php echo ($idx + 1); ?></p>
                        <p style="margin: 0; font-size: 12px; color:#B38F6F;">Racikan: <?php echo $mangkuk['tipeKuah'] . " | " . $mangkuk['tingkatPedas'] . " | " . $mangkuk['pemanis']; ?></p>
                        
                        <?php foreach ($mangkuk['items'] as$item): ?>
                            <div style="display: flex; justify-content: space-between; font-size: 12px;">
                                <span><?php echo $item['jumlah'] . 'x ' . htmlspecialchars($item['nama']); ?></span>
                                <span>Rp <?php echo number_format($item['harga'] *$item['jumlah'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>

                        <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: bold; margin-top: 3px; color:#710014;">
                            <span>Subtotal Mangkuk #<?php echo ($idx + 1); ?></span>
                            <span>Rp <?php echo number_format($tr['hasil']['detailMangkuk'][$idx]['total'], 0, ',', '.'); ?></span>
                        </div>
                        <hr style="border-top: 1px dotted #B38F6F; margin: 5px 0;">
                    <?php endforeach; ?>

                    <div style="font-size: 13px; margin-top: 10px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span>Total:</span>
                            <span>Rp <?php echo number_format($tr['hasil']['totalSebelumDiskon'], 0, ',', '.'); ?></span>
                        </div>
                        <?php if ($tr['hasil']['nominalDiskon'] > 0): ?>
                            <div style="display: flex; justify-content: space-between; color: #710014;">
                                <span>Diskon Member:</span>
                                <span>- Rp <?php echo number_format($tr['hasil']['nominalDiskon'], 0, ',', '.'); ?></span>
                            </div>
                        <?php endif; ?>
                        <hr style="border-top: 1px dashed #710014; margin: 5px 0;">
                        <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: bold; color:#710014;">
                            <span>TOTAL BAYAR:</span>
                            <span>Rp <?php echo number_format($tr['hasil']['totalBayar'], 0, ',', '.'); ?></span>
                        </div>
                    </div>

                    <div class="struk-footer" style="margin-top: 15px; font-size: 12px; color:#710014;">
                        <p style="margin: 0; font-style: italic;">Bunga Melati Warnanya Putih</p>
                        <p style="margin: 2px 0 0 0; font-weight: bold;">TERIMA KASIH!</p>
                    </div>
                </div>

                <div class="btn-group-download">
                    <button onclick="downloadStruk('jpg')" class="btn-jpg">Download Struk</button>
                </div>
            </div>

            <script>
                function downloadStruk(format) {
                    const element = document.getElementById('areaStruk');
                    html2canvas(element).then(canvas => {
                        const link = document.createElement('a');
                        link.download = 'Struk_Seblak_' + Date.now() + '.' + format;
                        link.href = canvas.toDataURL('image/' + (format === 'jpg' ? 'jpeg' : 'png'));
                        link.click();
                    });
                }
            </script>
        <?php endif; ?>

    </div>

</body>
</html>