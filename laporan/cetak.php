<?php
session_start();
require_once '../config/database.php';

// Validasi Login
if (!isset($_SESSION['user_id'])) {
  die("Akses ditolak. Silakan login.");
}

// Ambil Filter dari URL
$kategori  = $_GET['kategori'] ?? '';
$tgl_awal  = $_GET['tgl_awal'] ?? date('Y-m-d');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

// Judul Laporan & Query Switch
$judul = "";
$data  = [];

try {
  switch ($kategori) {
    case 'surat_masuk':
      $judul = "LAPORAN SURAT MASUK";
      // Urutkan berdasarkan Tanggal Terima ASC
      $stmt = $pdo->prepare("SELECT * FROM surat_masuk WHERE tgl_terima BETWEEN ? AND ? ORDER BY tgl_terima ASC, no_urut ASC");
      break;
    case 'surat_keluar':
      $judul = "LAPORAN SURAT KELUAR";
      $stmt = $pdo->prepare("SELECT * FROM surat_keluar WHERE tgl_kirim BETWEEN ? AND ? ORDER BY tgl_kirim ASC, no_urut ASC");
      break;
    case 'surat_sk':
      $judul = "LAPORAN SURAT KEPUTUSAN (SK)";
      $stmt = $pdo->prepare("SELECT * FROM surat_keputusan WHERE tgl_sk BETWEEN ? AND ? ORDER BY tgl_sk ASC, no_urut ASC");
      break;
    case 'nota_dinas':
      $judul = "LAPORAN NOTA DINAS";
      $stmt = $pdo->prepare("SELECT * FROM nota_dinas WHERE tgl_nota BETWEEN ? AND ? ORDER BY tgl_nota ASC, no_urut ASC");
      break;
    case 'surat_tugas':
      $judul = "LAPORAN SURAT TUGAS";
      $stmt = $pdo->prepare("SELECT * FROM surat_tugas WHERE tgl_tugas BETWEEN ? AND ? ORDER BY tgl_tugas ASC, id ASC");
      break;
    default:
      die("Kategori tidak valid.");
  }

  // Eksekusi Query
  $stmt->execute([$tgl_awal, $tgl_akhir]);
  $data = $stmt->fetchAll();
  if (count($data) == 0) {
    echo "<script>
            alert('DATA TIDAK DITEMUKAN! \\nTidak ada arsip " . str_replace('LAPORAN ', '', $judul) . " pada periode tanggal tersebut.');
            window.close(); // Kembali ke halaman filter
        </script>";
    exit; // Stop proses, jangan tampilkan kertas kosong
  }
} catch (PDOException $e) {
  die("Error Database: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Cetak Laporan - <?= $judul ?></title>
  <link href="<?= BASE_URL ?>assets/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* CSS Khusus Cetak */
    body {
      font-family: 'Times New Roman', serif;
      font-size: 11pt;
      color: #000;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 3px double #000;
      padding-bottom: 10px;
    }

    .header h3,
    .header h4 {
      margin: 0;
      font-weight: bold;
    }

    /* Table Styling agar hitam pekat saat diprint */
    .table thead th {
      background-color: #f0f0f0 !important;
      border: 1px solid #000 !important;
      color: #000 !important;
      vertical-align: middle;
      text-align: center;
      font-size: 10pt;
    }

    .table tbody td {
      border: 1px solid #000 !important;
      vertical-align: middle;
      font-size: 10pt;
      padding: 4px 8px;
      /* Padding lebih kecil agar muat banyak */
    }

    /* Print Settings */
    @media print {
      @page {
        size: landscape;
        margin: 1cm;
      }

      /* Landscape agar muat kolom banyak */
      .no-print {
        display: none;
      }

      body {
        -webkit-print-color-adjust: exact;
      }
    }
  </style>
</head>

<body onload="window.print()">

  <div class="container-fluid mt-2">

    <table style="width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px;">
      <tr>
        <td style="width: 15%; text-align: center; border: none !important;">
          <img src="<?= BASE_URL ?>assets/images/logo-bjm.png" style="width: 80px; height: auto;">
        </td>

        <td style="text-align: center; border: none !important;">
          <h4 style="margin: 0; font-weight: bold;">PEMERINTAH KOTA BANJARMASIN</h4>
          <h3 style="margin: 0; font-weight: bold;">DINAS LINGKUNGAN HIDUP</h3>
          <p style="margin: 0; font-size: 10pt;">Jl. RE Martadinata No.1, Telp. (0511) 123456, Banjarmasin</p>
        </td>

        <td style="width: 15%; border: none !important;"></td>
      </tr>
    </table>

    <div class="text-center mb-4">
      <h5 class="fw-bold text-decoration-underline"><?= $judul ?></h5>
      <p class="small">Periode: <?= date('d/m/Y', strtotime($tgl_awal)) ?> s/d <?= date('d/m/Y', strtotime($tgl_akhir)) ?></p>
    </div>

    <table class="table table-bordered table-sm">
      <thead>
        <?php if ($kategori == 'surat_masuk'): ?>
          <tr>
            <th rowspan="2" width="5%">No Urut</th>
            <th rowspan="2" width="10%">Tanggal</th>
            <th rowspan="2">Dari (Pengirim)</th>
            <th rowspan="2">Perihal</th>
            <th colspan="3">Disposisi</th>
            <th rowspan="2">Keterangan</th>
          </tr>
          <tr>
            <th>Kadis</th>
            <th>Sekretaris</th>
            <th>Bidang</th>
          </tr>

        <?php elseif ($kategori == 'surat_keluar'): ?>
          <tr>
            <th>No Urut</th>
            <th>Tanggal</th>
            <th>Tujuan</th>
            <th>Perihal</th>
            <th>Kode Klasifikasi</th>
            <th>Bidang</th>
            <th>Nomor Surat</th>
          </tr>

        <?php elseif ($kategori == 'surat_sk'): ?>
          <tr>
            <th>No Urut</th>
            <th>Tanggal</th>
            <th>Keterangan SK</th>
            <th>Perihal</th>
            <th>Bidang</th>
            <th>Nomor SK</th>
          </tr>

        <?php elseif ($kategori == 'nota_dinas'): ?>
          <tr>
            <th>No Urut</th>
            <th>Tanggal</th>
            <th>Tujuan</th>
            <th>Perihal</th>
            <th>Nominal (Rp)</th>
            <th>Kode Klasifikasi</th>
            <th>Bidang</th>
            <th>Nomor Nota</th>
          </tr>

        <?php elseif ($kategori == 'surat_tugas'): ?>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Perihal Penugasan</th>
            <th>Yang Ditugaskan</th>
            <th>Bidang</th>
            <th>Nomor ST</th>
          </tr>
        <?php endif; ?>
      </thead>

      <tbody>
        <?php if (count($data) > 0): ?>
          <?php $no = 1;
          foreach ($data as $row): ?>
            <tr>

              <?php if ($kategori == 'surat_masuk'): ?>
                <td class="text-center"><?= $row['no_urut'] ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_terima'])) ?></td>
                <td><?= $row['pengirim'] ?></td>
                <td><?= $row['perihal'] ?></td>
                <td class="text-center"><?= $row['disposisi_kadis'] ?></td>
                <td class="text-center"><?= $row['disposisi_sekretaris'] ?></td>
                <td class="text-center"><?= $row['disposisi_bidang'] ?></td>
                <td><?= $row['keterangan'] ?></td>

              <?php elseif ($kategori == 'surat_keluar'): ?>
                <td class="text-center"><?= $row['no_urut'] ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_kirim'])) ?></td>
                <td><?= $row['tujuan'] ?></td>
                <td><?= $row['perihal'] ?></td>
                <td class="text-center"><?= $row['kode_klasifikasi'] ?></td>
                <td><?= $row['bidang'] ?></td>
                <td class="fw-bold" style="white-space: nowrap;"><?= $row['nomor_surat'] ?></td>

              <?php elseif ($kategori == 'surat_sk'): ?>
                <td class="text-center"><?= $row['no_urut'] ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_sk'])) ?></td>
                <td><?= $row['keterangan_sk'] ?></td>
                <td><?= $row['perihal'] ?></td>
                <td><?= $row['bidang'] ?></td>
                <td class="fw-bold" style="white-space: nowrap;"><?= $row['nomor_sk'] ?></td>

              <?php elseif ($kategori == 'nota_dinas'): ?>
                <td class="text-center"><?= $row['no_urut'] ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_nota'])) ?></td>
                <td><?= $row['tujuan'] ?></td>
                <td><?= $row['perihal'] ?></td>
                <td style="text-align: right; white-space: nowrap;">
                  <?= number_format($row['nominal'], 0, ',', '.') ?>
                </td>
                <td class="text-center"><?= $row['kode_klasifikasi'] ?></td>
                <td><?= $row['bidang'] ?></td>
                <td class="fw-bold" style="white-space: nowrap;"><?= $row['nomor_nota'] ?></td>

              <?php elseif ($kategori == 'surat_tugas'): ?>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_tugas'])) ?></td>
                <td><?= $row['perihal_penugasan'] ?></td>
                <td><?= $row['yang_ditugaskan'] ?></td>
                <td><?= $row['bidang'] ?></td>
                <td class="fw-bold" style="white-space: nowrap;"><?= $row['nomor_surat_tugas'] ?></td>

              <?php endif; ?>

            </tr>
          <?php endforeach; ?>
          <?php
          if ($kategori == 'nota_dinas' && count($data) > 0) {
            $total_nominal = 0;
            foreach ($data as $d) {
              $total_nominal += $d['nominal'];
            }
          ?>
            <tr>
              <td colspan="4" style="text-align: right; font-weight: bold;">TOTAL JUMLAH</td>
              <td style="text-align: right; font-weight: bold; background-color: #eee;">
                Rp <?= number_format($total_nominal, 0, ',', '.') ?>
              </td>
              <td colspan="3"></td>
            </tr>
          <?php } ?>
        <?php else: ?>
          <tr>
            <td colspan="10" class="text-center py-4 fst-italic">Tidak ada data arsip pada rentang tanggal ini.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div style="float: right; text-align: center; width: 250px; margin-top: 30px; page-break-inside: avoid;">
      <p>Banjarmasin, <?= date('d F Y') ?></p>
      <p>Mengetahui,<br>Kepala Dinas</p>
      <br><br><br>
      <p class="fw-bold text-decoration-underline">.............................................</p>
      <p>NIP. ....................................</p>
    </div>

  </div>

</body>

</html>