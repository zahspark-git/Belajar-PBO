<?php
// Menyiapkan variabel
$nim = "";
$nama = "";
$fakultas = "";
$prodi = "";
$asal_sekolah = "";
$semester = "";
$hobi = "";

// Mengecek apakah form sudah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim          = $_POST["nim"] ?? "";
    $nama         = $_POST["nama"] ?? "";
    $fakultas     = $_POST["fakultas"] ?? "";
    $prodi        = $_POST["prodi"] ?? "";
    $asal_sekolah = $_POST["asal_sekolah"] ?? "";
    $semester     = $_POST["semester"] ?? "";
    $hobi         = $_POST["hobi"] ?? "";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>

    <!-- Font Cinzel Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Impor Font Genshin Drip dari File Lokal */
        @font-face {
            font-family: 'GenshinDrip';
            src: url('genshin-drip.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Cinzel', 'Georgia', serif;
            /* Gradasi Biru Mewah & Elegan */
            background: linear-gradient(135deg, #1C2B48 0%, #2A4365 40%, #8EB1D1 80%, #E8ECEF 100%);
            background-attachment: fixed;
            color: #1C2B48;
            margin: 0;
            padding: 40px 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* --- Animasi Badai Salju Deras --- */
        .snow-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .snowflake {
            position: absolute;
            background: #ffffff;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.9);
            animation: fallHeavySnow linear infinite;
        }

        /* Variasi 20 Butir Salju */
        .snowflake:nth-child(1)  { width: 6px;  height: 6px;  left: 2%;  animation-duration: 5s; animation-delay: 0s;   opacity: 0.9; }
        .snowflake:nth-child(2)  { width: 10px; height: 10px; left: 8%;  animation-duration: 8s; animation-delay: 1.5s; opacity: 0.8; }
        .snowflake:nth-child(3)  { width: 4px;  height: 4px;  left: 14%; animation-duration: 4s; animation-delay: 0.5s; opacity: 0.7; }
        .snowflake:nth-child(4)  { width: 8px;  height: 8px;  left: 20%; animation-duration: 6s; animation-delay: 2s;   opacity: 0.9; }
        .snowflake:nth-child(5)  { width: 5px;  height: 5px;  left: 26%; animation-duration: 5s; animation-delay: 3s;   opacity: 0.6; }
        .snowflake:nth-child(6)  { width: 12px; height: 12px; left: 32%; animation-duration: 9s; animation-delay: 1s;   opacity: 0.8; }
        .snowflake:nth-child(7)  { width: 7px;  height: 7px;  left: 38%; animation-duration: 7s; animation-delay: 0.2s; opacity: 0.9; }
        .snowflake:nth-child(8)  { width: 9px;  height: 9px;  left: 44%; animation-duration: 6s; animation-delay: 2.5s; opacity: 0.75;}
        .snowflake:nth-child(9)  { width: 5px;  height: 5px;  left: 50%; animation-duration: 4s; animation-delay: 1.2s; opacity: 0.85;}
        .snowflake:nth-child(10) { width: 11px; height: 11px; left: 56%; animation-duration: 8s; animation-delay: 0.8s; opacity: 0.9; }
        .snowflake:nth-child(11) { width: 6px;  height: 6px;  left: 62%; animation-duration: 5s; animation-delay: 2.2s; opacity: 0.8; }
        .snowflake:nth-child(12) { width: 8px;  height: 8px;  left: 68%; animation-duration: 7s; animation-delay: 3.5s; opacity: 0.7; }
        .snowflake:nth-child(13) { width: 4px;  height: 4px;  left: 74%; animation-duration: 4s; animation-delay: 0.1s; opacity: 0.9; }
        .snowflake:nth-child(14) { width: 10px; height: 10px; left: 80%; animation-duration: 6s; animation-delay: 1.8s; opacity: 0.85;}
        .snowflake:nth-child(15) { width: 7px;  height: 7px;  left: 85%; animation-duration: 5s; animation-delay: 2.7s; opacity: 0.75;}
        .snowflake:nth-child(16) { width: 12px; height: 12px; left: 90%; animation-duration: 8s; animation-delay: 0.4s; opacity: 0.9; }
        .snowflake:nth-child(17) { width: 5px;  height: 5px;  left: 94%; animation-duration: 4.5s; animation-delay: 1.6s; opacity: 0.8; }
        .snowflake:nth-child(18) { width: 9px;  height: 9px;  left: 17%; animation-duration: 6.5s; animation-delay: 3.1s; opacity: 0.85;}
        .snowflake:nth-child(19) { width: 6px;  height: 6px;  left: 42%; animation-duration: 5.2s; animation-delay: 1.9s; opacity: 0.7; }
        .snowflake:nth-child(20) { width: 10px; height: 10px; left: 66%; animation-duration: 7.5s; animation-delay: 0.7s; opacity: 0.9; }

        @keyframes fallHeavySnow {
            0% {
                transform: translateY(-20px) translateX(0);
            }
            50% {
                transform: translateY(50vh) translateX(35px);
            }
            100% {
                transform: translateY(105vh) translateX(-20px);
            }
        }
        /* --------------------------------- */

        .container {
            position: relative;
            z-index: 1;
            width: 600px;
            margin: auto;
            /* Glassmorphism Transparan Mewah */
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(28, 43, 72, 0.4), 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        /* Judul Menggunakan Font Genshin Drip */
        h1 {
            font-family: 'GenshinDrip', 'Cinzel', serif;
            text-align: center;
            color: #1C2B48;
            font-size: 32px;
            margin-top: 0;
            margin-bottom: 25px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Label Menggunakan Font Cinzel */
        label {
            font-family: 'Cinzel', 'Georgia', serif;
            display: block;
            margin-top: 16px;
            margin-bottom: 6px;
            color: #1C2B48;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.8px;
        }

        input,
        select {
            font-family: 'Cinzel', 'Georgia', serif;
            font-weight: 600;
            width: 100%;
            padding: 12px;
            box-sizing: border-box;
            border: 1.5px solid #C4D8E5;
            border-radius: 8px;
            background-color: #ffffff;
            color: #1C2B48;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus,
        select:focus {
            border-color: #8EB1D1;
            box-shadow: 0 0 10px rgba(142, 177, 209, 0.6);
        }

        /* Tombol Form Simpan Data */
        .btn-submit {
            font-family: 'GenshinDrip', 'Cinzel', serif;
            margin-top: 25px;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1C2B48 0%, #324B73 100%);
            color: #ffffff;
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.3);
            cursor: pointer;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(28, 43, 72, 0.3);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #324B73 0%, #1C2B48 100%);
            box-shadow: 0 6px 18px rgba(28, 43, 72, 0.5);
            transform: translateY(-2px);
        }

        .hasil {
            margin-top: 30px;
            padding: 22px;
            background: linear-gradient(135deg, #E8ECEF 0%, #C4D8E5 100%);
            color: #1C2B48;
            border-radius: 10px;
            border-left: 5px solid #1C2B48;
            box-shadow: 0 4px 12px rgba(28, 43, 72, 0.1);
        }

        .hasil h2 {
            font-family: 'GenshinDrip', 'Cinzel', serif;
            margin-top: 0;
            color: #1C2B48;
            font-size: 24px;
            margin-bottom: 15px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hasil p {
            font-family: 'Cinzel', 'Georgia', serif;
            margin: 10px 0;
            font-size: 15px;
        }

        /* --- Link Teks Judul Lagu ke YouTube --- */
        .bgm-link {
            position: fixed;
            bottom: 15px;
            right: 20px;
            z-index: 100;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            font-family: 'Cinzel', serif;
            font-size: 11px;
            letter-spacing: 0.8px;
            background: rgba(28, 43, 72, 0.35);
            padding: 6px 12px;
            border-radius: 15px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .bgm-link:hover {
            color: #ffffff;
            background: rgba(28, 43, 72, 0.85);
            border-color: #8EB1D1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
    </style>

</head>

<body>

<!-- Player YouTube Tersembunyi -->
<div style="position: absolute; width: 0; height: 0; overflow: hidden; opacity: 0;">
    <iframe id="ytPlayer" width="100" height="100" 
        src="https://www.youtube.com/embed/RuXa_yxZMGI?enablejsapi=1&autoplay=1&loop=1&playlist=RuXa_yxZMGI" 
        frameborder="0" allow="autoplay">
    </iframe>
</div>

<!-- Link Teks Judul Lagu (Klik untuk buka YouTube) -->
<a href="https://youtu.be/RuXa_yxZMGI?si=cVEYDFFFCIFNNB8w" target="_blank" class="bgm-link" title="Buka video di YouTube">
    🎵 Nod-Krai Main Theme
</a>

<!-- Elemen Badai Salju Deras -->
<div class="snow-container">
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
    <div class="snowflake"></div>
</div>

<div class="container">

    <h1>INPUT MAHASISWA</h1>

    <form method="POST">

        <label>NIM</label>
        <input type="text" name="nim" required>

        <label>Nama Mahasiswa</label>
        <input type="text" name="nama" required>

        <label>Fakultas</label>
        <input type="text" name="fakultas" required>

        <label>Program Studi</label>
        <input type="text" name="prodi" required>

        <label>Asal Sekolah</label>
        <input type="text" name="asal_sekolah" required>

        <label>Semester</label>
        <select name="semester" required>
            <option value="">-- Pilih Semester --</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
            <option value="3">Semester 3</option>
            <option value="4">Semester 4</option>
            <option value="5">Semester 5</option>
            <option value="6">Semester 6</option>
            <option value="7">Semester 7</option>
            <option value="8">Semester 8</option>
        </select>

        <label>Hobi</label>
        <input type="text" name="hobi">

        <button type="submit" class="btn-submit">Simpan Data</button>

    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>

        <div class="hasil">

            <h2>DATA MAHASISWA</h2>

            <p><strong>NIM:</strong> <?= htmlspecialchars($nim) ?></p>
            <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
            <p><strong>Fakultas:</strong> <?= htmlspecialchars($fakultas) ?></p>
            <p><strong>Program Studi:</strong> <?= htmlspecialchars($prodi) ?></p>
            <p><strong>Asal Sekolah:</strong> <?= htmlspecialchars($asal_sekolah) ?></p>
            <p><strong>Semester:</strong> <?= htmlspecialchars($semester) ?></p>
            <p><strong>Hobi:</strong> <?= htmlspecialchars($hobi) ?></p>

        </div>

    <?php } ?>

</div>

<script>
    let isPlaying = false;
    const playerFrame = document.getElementById('ytPlayer');

    function playAudio() {
        playerFrame.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
        isPlaying = true;
    }

    // Pemicu Otomatis: Musik menyala begitu ada klik / ketikan pertama di halaman
    ['click', 'touchstart', 'keydown'].forEach(event => {
        document.addEventListener(event, function autoPlay() {
            if (!isPlaying) {
                playAudio();
            }
            document.removeEventListener(event, autoPlay);
        }, { once: true });
    });
</script>

</body>
</html>