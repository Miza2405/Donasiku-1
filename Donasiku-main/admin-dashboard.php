<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DonasiKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Library Chart.js untuk Grafik Statistik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; overflow-x: hidden; }
        .text-emerald { color: #059669; } .bg-emerald { background-color: #059669; }
        .wrapper { display: flex; width: 100%; align-items: stretch; min-height: 100vh; }
        
        #sidebar { min-width: 250px; max-width: 250px; background: #ffffff; box-shadow: 2px 0 10px rgba(0,0,0,0.05); z-index: 100; }
        #sidebar .sidebar-header { padding: 20px; background: #059669; color: white; text-align: center; }
        #sidebar ul.components { padding: 20px 0; }
        #sidebar ul li a { padding: 12px 20px; font-size: 1em; display: block; color: #555; text-decoration: none; font-weight: 600; cursor: pointer; transition: 0.2s;}
        #sidebar ul li a:hover, #sidebar ul li.active > a { color: #059669; background: #eef2f0; border-right: 4px solid #059669; }
        
        #content { width: 100%; padding: 20px; }
        .top-navbar { background: white; border-radius: 10px; padding: 10px 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .dash-card { border: none; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); transition: 0.3s; }
        .dash-card:hover { transform: translateY(-5px); box-shadow: 0 8px 15px rgba(0,0,0,0.08); }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        
        .table-custom { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .table-custom th, .table-custom td { padding: 15px; vertical-align: middle; }
        
        .content-section { display: none; animation: fadeIn 0.4s ease-in-out; }
        .content-section.active-section { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .modal-content-custom { border-radius: 15px; }

        .form-control:focus, .form-select:focus { border-color: #059669; box-shadow: 0 0 0 0.25rem rgba(5, 150, 105, 0.25); }
        /* Style Khusus Laporan ala Referensi */
        .report-card { background: #ffffff; border: 1px solid #eef2f5; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); display: flex; align-items: center; }
        .report-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; margin-right: 15px; }
        .report-icon-in { background-color: #e6f4ea; color: #059669; }
        .report-icon-out { background-color: #fce8e6; color: #d93025; }
        .report-icon-balance { background-color: #e8f0fe; color: #1a73e8; }
        .report-label { font-size: 0.85rem; color: #5f6368; font-weight: 600; margin-bottom: 2px; }
        .report-value { font-size: 1.4rem; font-weight: 700; color: #202124; margin-bottom: 5px; }
        .report-trend { font-size: 0.75rem; font-weight: 600; }
        .trend-up { color: #059669; } .trend-down { color: #d93025; }
        
        .filter-bar { background: #ffffff; border: 1px solid #eef2f5; border-radius: 12px; padding: 15px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); margin-bottom: 24px; }
        .chart-box { background: #ffffff; border: 1px solid #eef2f5; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); height: 100%; }
        /* ========================================================
   CSS UNTUK SLIDER REKENING & E-WALLET (AKTIF / NONAKTIF)
   ======================================================== */

/* 1. Jika Slider menggunakan bawaan Bootstrap (.form-switch) */
.form-switch .form-check-input {
    cursor: pointer;
    transition: all 0.3s ease;
}
/* Warna saat OFF (Nonaktif) -> Abu-abu */
.form-switch .form-check-input:not(:checked) {
    background-color: #cbd5e1 !important; 
    border-color: #94a3b8 !important;
}
/* Warna saat ON (Aktif) -> Hijau Emerald */
.form-switch .form-check-input:checked {
    background-color: #059669 !important; 
    border-color: #059669 !important;
}

/* 2. Jika Slider menggunakan CSS Kustom (.switch / .slider) */
.switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 24px;
}
.switch input { 
    opacity: 0;
    width: 0;
    height: 0;
}
/* Warna saat OFF (Nonaktif) -> Abu-abu */
.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1; /* <-- Ini yang membuatnya abu-abu */
    transition: .4s;
    border-radius: 34px;
}
.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}
/* Warna saat ON (Aktif) -> Hijau Emerald */
input:checked + .slider {
    background-color: #059669;
}
input:checked + .slider:before {
    transform: translateX(22px);
}
    </style>
</head>
<body>
<div class="wrapper">
    <!-- SIDEBAR NAVIGASI -->
    <nav id="sidebar" class="d-none d-md-block">
        <div class="sidebar-header"><h3 class="fw-bold mb-0">🎁 DonasiKu</h3><small>Panel Admin</small></div>
        <ul class="list-unstyled components" id="menu-list">
            <li class="active" id="menu-dashboard"><a onclick="switchMenu('dashboard', 'Dashboard Utama')"><i class="bi bi-grid-1x2-fill me-2"></i> Dashboard</a></li>
            <li id="menu-program"><a onclick="switchMenu('program', 'Manajemen Program')"><i class="bi bi-box2-heart-fill me-2"></i> Program Donasi</a></li>
            <li id="menu-donatur"><a onclick="switchMenu('donatur', 'Data Donatur')"><i class="bi bi-people-fill me-2"></i> Data Donatur</a></li>
            <li id="menu-transaksi"><a onclick="switchMenu('transaksi', 'Data Transaksi')"><i class="bi bi-wallet-fill me-2"></i> Data Transaksi</a></li>
            <li id="menu-laporan"><a onclick="switchMenu('laporan', 'Laporan Keuangan')"><i class="bi bi-bar-chart-line-fill me-2"></i> Laporan</a></li>
            <li id="menu-pengaturan"><a onclick="switchMenu('pengaturan', 'Pengaturan Sistem')"><i class="bi bi-gear-fill me-2"></i> Pengaturan</a></li>
        </ul>
        <div class="p-3 mt-4"><button onclick="logout()" class="btn btn-danger w-100 rounded-pill"><i class="bi bi-box-arrow-left me-2"></i> Keluar</button></div>
    </nav>

    <!-- KONTEN UTAMA -->
    <div id="content">
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <div><h5 class="fw-bold mb-0 text-emerald" id="top-title">Dashboard Utama</h5></div>
            <div class="d-flex align-items-center"><span class="me-3 fw-semibold text-muted">Halo, Admin</span><img src="https://ui-avatars.com/api/?name=Admin&background=059669&color=fff" alt="Avatar Admin" class="rounded-circle" width="40"></div>
        </div>

        <!-- 1. DASHBOARD -->
        <div id="sec-dashboard" class="content-section active-section">
            <div class="row g-4 mb-4">
                <div class="col-md-4"><div class="card dash-card p-4"><div class="d-flex justify-content-between align-items-center"><div><p class="text-muted fw-semibold mb-1">Total Dana Donasi</p><h3 class="fw-bold text-emerald mb-0" id="stat-total-dana">Rp 0</h3></div><div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-cash-stack"></i></div></div></div></div>
                <div class="col-md-4"><div class="card dash-card p-4"><div class="d-flex justify-content-between align-items-center"><div><p class="text-muted fw-semibold mb-1">Menunggu Verifikasi</p><h3 class="fw-bold text-warning mb-0" id="stat-pending-trx">0 Trx</h3></div><div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div></div></div></div>
                <div class="col-md-4"><div class="card dash-card p-4"><div class="d-flex justify-content-between align-items-center"><div><p class="text-muted fw-semibold mb-1">Total Donatur</p><h3 class="fw-bold text-primary mb-0" id="stat-total-donatur">0 Orang</h3></div><div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div></div></div></div>
            </div>
            
            <h5 class="fw-bold mb-3 mt-4">Transaksi Perlu Verifikasi</h5>
            <div class="table-custom table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>ID TRX</th><th>Nama Donatur</th><th>Program Donasi</th><th>Nominal</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody id="tbody-pending-transaksi">
                        <tr>
                            <td class="fw-bold">#TRX-001</td>
                            <td><a href="#" class="text-emerald text-decoration-none fw-bold" onclick="bukaInvoice('TRX-001', 'Suud nofa', 'Sedekah Jariyah', 'Rp 2.500.000', '20 Mar 2026', 'Bank BCA')">Suud nofa</a></td>
                            <td>Sedekah Jariyah</td>
                            <td class="fw-semibold text-emerald">Rp 2.500.000</td>
                            <td><span id="badge-TRX-001" class="badge bg-warning text-dark">Pending</span></td>
                            <td id="aksi-TRX-001"><button class="btn btn-sm btn-success rounded-pill" onclick="bukaInvoice('TRX-001', 'Suud nofa', 'Sedekah Jariyah', 'Rp 2.500.000', '20 Mar 2026', 'Bank BCA')"><i class="bi bi-check-circle"></i> Verifikasi</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. MANAJEMEN PROGRAM (Gambar disinkronkan dengan halaman Donasi) -->
        <div id="sec-program" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Daftar Program Kampanye</h5>
                <button class="btn btn-emerald rounded-pill" data-bs-toggle="modal" data-bs-target="#tambahProgramModal">
                    <i class="bi bi-plus-circle me-1"></i> Buat Program Baru
                </button>
            </div>
            
            <div class="table-custom table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Gambar</th>
                            <th>Judul Program</th>
                            <th>Kategori</th>
                            <th>Target Dana</th>
                            <th>Terkumpul</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-program">
                        <!-- 1. Bencana -->
                        <tr>
                            <td><img src="https://akcdn.detik.net.id/visual/2025/11/27/longsor-di-malalak-timur-agam-1764227249858_169.jpeg?w=1200" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Indonesia Darurat Bencana: Longsor & Banjir</td>
                            <td><span class="badge bg-danger">Bencana Alam</span></td>
                            <td>Rp 100.000.000</td>
                            <td class="text-emerald fw-bold">Rp 35.076.524</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" title="Edit Program"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger" title="Tutup Program"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 2. Sembako -->
                        <tr>
                            <td><img src="https://www.lead.co.id/wp-content/uploads/2020/04/IMG-20200404-WA0187.jpg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Berbagi Paket Sembako Untuk Keluarga Dhuafa</td>
                            <td><span class="badge bg-warning text-dark">Pangan & Sembako</span></td>
                            <td>Rp 30.000.000</td>
                            <td class="text-emerald fw-bold">Rp 18.500.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 3. Medis -->
                        <tr>
                            <td><img src="https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Bantuan Medis Darurat & Kemanusiaan</td>
                            <td><span class="badge bg-danger">Kesehatan</span></td>
                            <td>Rp 100.000.000</td>
                            <td class="text-emerald fw-bold">Rp 54.560.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 4. Palestina -->
                        <tr>
                            <td><img src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Bantu Pangan dan Air Bersih Untuk Palestina</td>
                            <td><span class="badge bg-danger">Krisis Pangan</span></td>
                            <td>Rp 200.000.000</td>
                            <td class="text-emerald fw-bold">Rp 27.066.258</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 5. Yatim -->
                        <tr>
                            <td><img src="https://pantiyatim.or.id/wp-content/uploads/2020/11/anak-yatim.jpeg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Santunan Anak Yatim Pelosok Negeri</td>
                            <td><span class="badge bg-info text-dark">Pendidikan</span></td>
                            <td>Rp 50.000.000</td>
                            <td class="text-emerald fw-bold">Rp 12.400.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 6. Modal Usaha -->
                        <tr>
                            <td><img src="https://cdn0-production-images-kly.akamaized.net/gzchwijL4F4IEVmk-0wP9C21_Js=/0x96:999x659/500x281/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/3512811/original/005192600_1626421965-shutterstock_2004727295.jpg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Bantuan Modal Usaha Untuk Ibu Tangguh</td>
                            <td><span class="badge bg-secondary">Pemberdayaan</span></td>
                            <td>Rp 20.000.000</td>
                            <td class="text-emerald fw-bold">Rp 8.000.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 7. Masjid -->
                        <tr>
                            <td><img src="https://pro.kutaitimurkab.go.id/wp-content/uploads/2025/06/a59864bd-0917-49ca-a55d-3549fffe2210-1024x684.jpeg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Pembangunan Masjid Pelosok Desa</td>
                            <td><span class="badge bg-success">Pembangunan</span></td>
                            <td>Rp 500.000.000</td>
                            <td class="text-emerald fw-bold">Rp 250.000.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 8. Beasiswa -->
                        <tr>
                            <td><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTwWyWUtVCOybBsE-XXfPNVywMlvGP5NSTdPw&s=10" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Beasiswa Pendidikan Santri Penghafal Quran</td>
                            <td><span class="badge bg-info text-dark">Pendidikan</span></td>
                            <td>Rp 150.000.000</td>
                            <td class="text-emerald fw-bold">Rp 45.000.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                        <!-- 9. Sedekah Makanan -->
                        <tr>
                            <td><img src="https://d1jvl8fx4qy5cj.cloudfront.net/wp-content/uploads/2020/05/Pemulung_89206118_1589299356.jpg" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>
                            <td class="fw-bold">Sedekah Makanan Hangat Untuk Pekerja Jalanan</td>
                            <td><span class="badge bg-warning text-dark">Pangan & Sembako</span></td>
                            <td>Rp 10.000.000</td>
                            <td class="text-emerald fw-bold">Rp 5.200.000</td>
                            <td><span class="badge bg-success">Aktif</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-power"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. DATA DONATUR -->
        <div id="sec-donatur" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4"><h5 class="fw-bold mb-0">Daftar Pengguna (Donatur)</h5></div>
            <div class="table-custom table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>No</th><th>Nama Lengkap</th><th>Email</th><th>No. HP</th><th>Tgl Terdaftar</th><th>Aksi</th></tr></thead>
                    <tbody id="tbody-donatur"><tr><td colspan="6" class="text-center text-muted py-4">Memuat data donatur...</td></tr></tbody>
                </table>
            </div>
        </div>

        <!-- 4. DATA TRANSAKSI -->
        <div id="sec-transaksi" class="content-section">
            <h5 class="fw-bold mb-4">Semua Riwayat Transaksi</h5>
            <div class="table-custom table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Tanggal</th><th>ID TRX</th><th>Donatur</th><th>Program</th><th>Nominal</th><th>Status</th></tr></thead>
                    <tbody id="tbody-semua-transaksi"><tr><td colspan="6" class="text-center text-muted py-4">Memuat data transaksi...</td></tr></tbody>
                </table>
            </div>
        </div>

        <!-- 5. LAPORAN & STATISTIK (Desain Baru) -->
        <div id="sec-laporan" class="content-section">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Laporan Keuangan & Penyaluran</h5>
                <button class="btn btn-outline-success rounded-pill btn-sm fw-semibold"><i class="bi bi-printer me-1"></i> Cetak PDF</button>
            </div>
            
            <!-- Kartu Statistik Atas -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="report-icon report-icon-in"><i class="bi bi-arrow-up-right"></i></div>
                        <div>
                            <div class="report-label">Total Pemasukan</div>
                            <div class="report-value">Rp 120.500.000</div>
                            <div class="report-trend trend-up"><i class="bi bi-arrow-up-short"></i> 18% dari bulan lalu</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="report-icon report-icon-out"><i class="bi bi-arrow-down-left"></i></div>
                        <div>
                            <div class="report-label">Total Pengeluaran</div>
                            <div class="report-value">Rp 85.250.000</div>
                            <div class="report-trend trend-down"><i class="bi bi-arrow-down-short"></i> 8% dari bulan lalu</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="report-card">
                        <div class="report-icon report-icon-balance"><i class="bi bi-wallet2"></i></div>
                        <div>
                            <div class="report-label">Saldo Bersih</div>
                            <div class="report-value text-emerald">Rp 35.250.000</div>
                            <div class="report-trend trend-up"><i class="bi bi-arrow-up-short"></i> 25% dari bulan lalu</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="filter-bar d-flex flex-wrap align-items-end gap-3">
                <div style="flex: 1; min-width: 150px;">
                    <label class="form-label small fw-bold text-muted mb-1">Periode</label>
                    <select class="form-select bg-light border-0"><option>Bulanan</option><option>Tahunan</option></select>
                </div>
                <div style="flex: 2; min-width: 200px;">
                    <label class="form-label small fw-bold text-muted mb-1">Pilih Bulan</label>
                    <input type="month" class="form-control bg-light border-0" value="2026-05">
                </div>
                <div>
                    <button class="btn btn-emerald px-4 fw-semibold" onclick="updateChartData()">Terapkan</button>
                </div>
            </div>

            <!-- Area Grafik -->
            <div class="row g-4 mb-4">
                <!-- Bar Chart (Kiri) -->
                <div class="col-lg-8">
                    <div class="chart-box">
                        <h6 class="fw-bold mb-4 text-muted">Pemasukan vs Pengeluaran</h6>
                        <div style="height: 300px;"><canvas id="barChart"></canvas></div>
                    </div>
                </div>
                <!-- Donut Chart (Kanan) -->
                <div class="col-lg-4">
                    <div class="chart-box">
                        <h6 class="fw-bold mb-4 text-muted">Distribusi Kategori</h6>
                        <div style="height: 250px; display: flex; justify-content: center;"><canvas id="donutChart"></canvas></div>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat -->
            <div class="chart-box mt-2">
                <h6 class="fw-bold mb-3 text-muted">Riwayat Penyaluran Dana Terakhir</h6>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>Tgl Penyaluran</th><th>Program Tujuan</th><th>Penerima Manfaat</th><th>Nominal</th><th>Bukti</th></tr></thead>
                        <tbody>
                            <tr><td>25 Mar 2026</td><td class="fw-bold">Santunan Anak Yatim</td><td>Panti Asuhan Kasih Ibu</td><td class="text-danger fw-bold">- Rp 15.000.000</td><td><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-image"></i></button></td></tr>
                            <tr><td>22 Mar 2026</td><td class="fw-bold">Bantuan Bencana Alam</td><td>Posko Bencana Agam</td><td class="text-danger fw-bold">- Rp 30.000.000</td><td><button class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-image"></i></button></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<!-- ================= MODALS ================= -->

<!-- Modal Tambah Rekening -->
<div class="modal fade" id="modalTambahRekening" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-emerald"><i class="bi bi-credit-card-2-front me-2"></i> Tambah Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formRekening">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jenis Pembayaran</label>
                        <select class="form-select">
                            <option>Transfer Bank</option>
                            <option>E-Wallet (Gopay/OVO/Dana)</option>
                            <option>QRIS (Barcode)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Bank / E-Wallet</label>
                        <input type="text" class="form-control" placeholder="Contoh: Bank BNI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor Rekening / Nomor Tujuan</label>
                        <input type="text" class="form-control" placeholder="Contoh: 0987654321" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Atas Nama (Pemilik Rekening)</label>
                        <input type="text" class="form-control" placeholder="Yayasan DonasiKu" required>
                    </div>
                    <button type="button" class="btn btn-emerald w-100 rounded-pill fw-bold" onclick="simpanPengaturan('Metode Pembayaran berhasil ditambahkan!')">Simpan Rekening</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Admin -->
<div class="modal fade" id="modalTambahAdmin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-emerald"><i class="bi bi-person-plus me-2"></i> Tambah Pengelola Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formAdmin">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" placeholder="Masukkan nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Utama</label>
                        <input type="email" class="form-control" placeholder="admin@donasiku.com" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Pilih Hak Akses (Role)</label>
                        <select class="form-select">
                            <option value="Super Admin">Super Admin (Bisa akses semua)</option>
                            <option value="Staf Keuangan">Staf Keuangan (Hanya validasi transaksi)</option>
                            <option value="Staf Program">Staf Program (Hanya kelola program kampanye)</option>
                        </select>
                    </div>
                    <div class="alert alert-warning small py-2 border-0 bg-warning bg-opacity-10 text-dark">
                        <i class="bi bi-info-circle-fill me-1"></i> Password default (12345) akan dikirim ke email tujuan.
                    </div>
                    <button type="button" class="btn btn-emerald w-100 rounded-pill fw-bold" onclick="simpanPengaturan('Admin baru berhasil didaftarkan!')">Daftarkan Admin</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Verifikasi Invoice -->
<div class="modal fade" id="invoiceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header bg-emerald text-white border-0"><h5 class="modal-title fw-bold" id="invoiceModalLabel"><i class="bi bi-receipt me-2"></i> Detail Transaksi <span id="inv-id">#TRX-000</span></h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 bg-light">
                <div class="bg-white p-3 rounded-3 shadow-sm border mb-3">
                    <div class="row mb-2"><div class="col-5 text-muted small">Nama Donatur</div><div class="col-7 fw-bold text-dark text-end" id="inv-name">Nama</div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small">Tgl Transaksi</div><div class="col-7 fw-semibold text-end" id="inv-date">Tgl</div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small">Metode Bayar</div><div class="col-7 fw-semibold text-end" id="inv-method">Metode</div></div>
                    <div class="row mb-2"><div class="col-5 text-muted small">Program Donasi</div><div class="col-7 fw-semibold text-end" id="inv-type">Program</div></div>
                </div>
                <div class="bg-emerald bg-opacity-10 p-3 rounded-3 border border-success border-opacity-25 d-flex justify-content-between align-items-center"><span class="fw-bold text-emerald">Total Nominal</span><h4 class="fw-bold text-emerald mb-0" id="inv-nominal">Rp 0</h4></div>
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-between"><button type="button" class="btn btn-outline-danger px-4 rounded-pill" onclick="aksiTolak()"><i class="bi bi-x-circle me-1"></i> Tolak</button><button type="button" class="btn btn-success px-4 rounded-pill" onclick="aksiVerifikasi()"><i class="bi bi-check-circle me-1"></i> Verifikasi Sah</button></div>
        </div>
    </div>
</div>

<!-- Modal Tambah Program Baru -->
<div class="modal fade" id="tambahProgramModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg modal-content-custom">
            <div class="modal-header border-0"><h5 class="modal-title fw-bold text-emerald"><i class="bi bi-plus-square-fill me-2"></i> Buat Program Kampanye Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 bg-light">
                <form id="formProgramBaru">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Judul Program</label>
                            <input type="text" class="form-control" placeholder="Contoh: Bantuan Air Bersih..." required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kategori</label>
                            <select class="form-select" required>
                                <option value="Bencana Alam">Bencana Alam</option>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Pendidikan">Pendidikan & Yatim</option>
                                <option value="Sembako">Pangan & Sembako</option>
                                <option value="Pembangunan">Pembangunan</option>
                                <option value="Pemberdayaan">Pemberdayaan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Target Pengumpulan Dana (Rp)</label>
                            <div class="input-group"><span class="input-group-text">Rp</span><input type="number" class="form-control" placeholder="100000000" required></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Batas Waktu</label>
                            <input type="date" class="form-control">
                            <small class="text-muted" style="font-size: 0.7rem;">Kosongkan jika program berjalan selamanya (Tanpa Batas)</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Upload Gambar/Banner Promosi</label>
                            <input type="file" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Kisah & Latar Belakang Program</label>
                            <textarea class="form-control" rows="4" placeholder="Ceritakan mengapa program ini dibuat..." required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 bg-light"><button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-emerald rounded-pill px-4" onclick="simpanProgram()"><i class="bi bi-save me-1"></i> Terbitkan Program</button></div>
        </div>
    </div>
</div>

<!-- 6. PENGATURAN SISTEM (Pembayaran & Admin) -->
        <div id="sec-pengaturan" class="content-section">
            <h5 class="fw-bold mb-4">Pengaturan Sistem</h5>
            
            <!-- Navigasi Tabs -->
            <ul class="nav nav-pills mb-4 gap-2" id="settingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" data-bs-toggle="pill" data-bs-target="#tab-pembayaran" type="button" style="border-radius: 10px;">
                        <i class="bi bi-credit-card-2-front-fill me-1"></i> Metode Pembayaran
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" data-bs-toggle="pill" data-bs-target="#tab-admin" type="button" style="border-radius: 10px;">
                        <i class="bi bi-person-badge-fill me-1"></i> Manajemen Admin
                    </button>
                </li>
            </ul>

            <!-- Isi Tabs -->
            <div class="tab-content bg-white p-4 rounded-4 shadow-sm border border-light">
                
                <!-- TAB 1: REKENING & PEMBAYARAN -->
                <div class="tab-pane fade show active" id="tab-pembayaran" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="fw-bold mb-1">Daftar Rekening & E-Wallet</h6>
                            <small class="text-muted">Metode pembayaran yang akan tampil di halaman donatur.</small>
                        </div>
                        <button class="btn btn-emerald btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahRekening">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Metode
                        </button>
                    </div>

                    <div class="row g-3">
                        <!-- Kartu Bank BCA -->
                        <div class="col-md-6">
                            <div class="card border border-success border-opacity-25 rounded-3 h-100 bg-light">
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded shadow-sm me-3 border"><i class="bi bi-bank2 text-primary fs-4"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Bank BCA</h6>
                                            <small class="text-muted d-block">1234-5678-90 a.n Yayasan DonasiKu</small>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch fs-4">
                                        <input class="form-check-input bg-success border-success" type="checkbox" checked title="Nonaktifkan">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Kartu Bank Mandiri -->
                        <div class="col-md-6">
                            <div class="card border border-success border-opacity-25 rounded-3 h-100 bg-light">
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded shadow-sm me-3 border"><i class="bi bi-bank2 text-warning fs-4"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-0">Bank Mandiri</h6>
                                            <small class="text-muted d-block">0987-6543-21 a.n Yayasan DonasiKu</small>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch fs-4">
                                        <input class="form-check-input bg-success border-success" type="checkbox" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Kartu QRIS -->
                        <div class="col-md-6">
                            <div class="card border border-success border-opacity-25 rounded-3 h-100 bg-light">
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded shadow-sm me-3 border"><i class="bi bi-qr-code-scan text-danger fs-4"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-0">QRIS Nasional</h6>
                                            <small class="text-muted d-block">Gopay, OVO, Dana, LinkAja</small>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch fs-4">
                                        <input class="form-check-input bg-success border-success" type="checkbox" checked>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: MANAJEMEN ADMIN -->
                <div class="tab-pane fade" id="tab-admin" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="fw-bold mb-1">Akses Pengelola Sistem</h6>
                            <small class="text-muted">Atur siapa saja yang memiliki akses ke dashboard ini.</small>
                        </div>
                        <button class="btn btn-emerald btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                            <i class="bi bi-person-plus-fill me-1"></i> Tambah Admin
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Profil</th>
                                    <th>Nama Lengkap</th>
                                    <th>Hak Akses (Role)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-admin">
                                <tr>
                                    <td><img src="https://ui-avatars.com/api/?name=Kevini&background=059669&color=fff" class="rounded-circle" width="40"></td>
                                    <td class="fw-bold">Kevin <br><small class="text-muted fw-normal">Kevin@email.com</small></td>
                                    <td><span class="badge bg-primary">Super Admin</span></td>
                                    <td><span class="text-success fw-semibold small"><i class="bi bi-circle-fill"></i> Aktif</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary disabled" title="Tidak bisa hapus diri sendiri"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="https://ui-avatars.com/api/?name=Suud+Nofa&background=0ea5e9&color=fff" class="rounded-circle" width="40"></td>
                                    <td class="fw-bold">Suud Nofa <br><small class="text-muted fw-normal">suud@donasiku.com</small></td>
                                    <td><span class="badge bg-info text-dark">Staf Program</span></td>
                                    <td><span class="text-success fw-semibold small"><i class="bi bi-circle-fill"></i> Aktif</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="hapusAdmin('Suud Nofa')"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><img src="https://ui-avatars.com/api/?name=Dean&background=f59e0b&color=fff" class="rounded-circle" width="40"></td>
                                    <td class="fw-bold">Miza <br><small class="text-muted fw-normal">Miza@email.com</small></td>
                                    <td><span class="badge bg-warning text-dark">Staf Keuangan</span></td>
                                    <td><span class="text-success fw-semibold small"><i class="bi bi-circle-fill"></i> Aktif</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-danger" onclick="hapusAdmin('Miza')"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let semuaTransaksiAdmin = [];
    let currentTransactionId = null;

    function formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
    }

    function formatTanggal(value) {
        return new Date(value).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function getStatusBadge(status) {
        if (status === 'success') {
            return '<span class="badge bg-success">Berhasil</span>';
        }

        if (status === 'failed') {
            return '<span class="badge bg-danger">Ditolak</span>';
        }

        return '<span class="badge bg-warning text-dark">Pending</span>';
    }

    function getCategoryLabel(category) {
        const labels = {
            jariyah: 'Sedekah Jariyah',
            yatim: 'Anak Yatim',
            pangan: 'Pangan',
            darurat: 'Darurat'
        };

        return labels[category] || category;
    }

    function getProgramImage(program) {
        if (program.image_url) {
            return program.image_url;
        }

        const fallbackImages = {
            jariyah: 'https://images.unsplash.com/photo-1564769662533-4f00a87b4056?auto=format&fit=crop&w=800&q=80',
            yatim: 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
            pangan: 'https://images.unsplash.com/photo-1593113630400-ea4288922497?auto=format&fit=crop&w=800&q=80',
            darurat: 'https://images.unsplash.com/photo-1584515933487-779824d29309?auto=format&fit=crop&w=800&q=80'
        };

        return fallbackImages[program.category] || fallbackImages.pangan;
    }

    async function fetchAdmin(action) {
        const response = await fetch('./api/admin.php?action=' + action);
        const responseText = await response.text();
        let result;

        try {
            result = JSON.parse(responseText);
        } catch (error) {
            throw new Error(responseText || 'Response server tidak valid');
        }

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Gagal memuat data admin');
        }

        return result.data;
    }

    function renderStats(data) {
        document.getElementById('stat-total-dana').innerText = formatRupiah(data.transactions.total_success);
        document.getElementById('stat-pending-trx').innerText = Number(data.transactions.pending_count || 0) + ' Trx';
        document.getElementById('stat-total-donatur').innerText = Number(data.donors.total_donors || 0) + ' Orang';
    }

    function renderPendingTransactions(transactions) {
        const tbody = document.getElementById('tbody-pending-transaksi');
        const pending = transactions.filter(function (trx) {
            return trx.status === 'pending';
        });

        if (pending.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada transaksi yang perlu diverifikasi.</td></tr>';
            return;
        }

        tbody.innerHTML = pending.map(function (trx) {
            return (
                '<tr>' +
                    '<td class="fw-bold">#' + escapeHtml(trx.trx_code) + '</td>' +
                    '<td><a href="#" class="text-emerald text-decoration-none fw-bold" onclick="bukaInvoiceById(' + trx.id + ')">' + escapeHtml(trx.donor_name) + '</a></td>' +
                    '<td>' + escapeHtml(trx.program_title) + '</td>' +
                    '<td class="fw-semibold text-emerald">' + formatRupiah(trx.amount) + '</td>' +
                    '<td>' + getStatusBadge(trx.status) + '</td>' +
                    '<td><button class="btn btn-sm btn-success rounded-pill" onclick="bukaInvoiceById(' + trx.id + ')"><i class="bi bi-check-circle"></i> Verifikasi</button></td>' +
                '</tr>'
            );
        }).join('');
    }

    function renderPrograms(programs) {
        const tbody = document.getElementById('tbody-program');

        if (programs.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada program donasi.</td></tr>';
            return;
        }

        tbody.innerHTML = programs.map(function (program) {
            const statusClass = program.status === 'active' ? 'bg-success' : 'bg-secondary';

            return (
                '<tr>' +
                    '<td><img src="' + getProgramImage(program) + '" class="rounded shadow-sm" width="55" height="40" style="object-fit: cover;"></td>' +
                    '<td class="fw-bold">' + escapeHtml(program.title) + '</td>' +
                    '<td><span class="badge bg-info text-dark">' + escapeHtml(getCategoryLabel(program.category)) + '</span></td>' +
                    '<td>' + formatRupiah(program.target_amount) + '</td>' +
                    '<td class="text-emerald fw-bold">' + formatRupiah(program.collected_amount) + '</td>' +
                    '<td><span class="badge ' + statusClass + '">' + escapeHtml(program.status) + '</span></td>' +
                    '<td>' +
                        '<button class="btn btn-sm btn-outline-primary me-2" onclick="editProgram(' + program.id + ')" title="Edit Program"><i class="bi bi-pencil-square"></i></button>' +
                        '<button class="btn btn-sm btn-outline-danger" onclick="hapusProgram(' + program.id + ', \'' + escapeHtml(program.title) + '\')" title="Hapus Program"><i class="bi bi-trash"></i></button>' +
                    '</td>' +
                '</tr>'
            );
        }).join('');
    }

    function renderDonatur(users) {
        const tbody = document.getElementById('tbody-donatur');

        if (users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada donatur.</td></tr>';
            return;
        }

        tbody.innerHTML = users.map(function (user, index) {
            return (
                '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td class="fw-bold">' + escapeHtml(user.name) + '</td>' +
                    '<td>' + escapeHtml(user.email) + '</td>' +
                    '<td>' + escapeHtml(user.phone || '-') + '</td>' +
                    '<td>' + formatTanggal(user.created_at) + '</td>' +
                    '<td><span class="text-muted small">Role user</span></td>' +
                '</tr>'
            );
        }).join('');
    }

    function renderAllTransactions(transactions) {
        const tbody = document.getElementById('tbody-semua-transaksi');

        if (transactions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>';
            return;
        }

        tbody.innerHTML = transactions.map(function (trx) {
            return (
                '<tr>' +
                    '<td>' + formatTanggal(trx.created_at) + '</td>' +
                    '<td class="fw-bold">#' + escapeHtml(trx.trx_code) + '</td>' +
                    '<td>' + escapeHtml(trx.donor_name) + '</td>' +
                    '<td>' + escapeHtml(trx.program_title) + '</td>' +
                    '<td class="fw-semibold text-emerald">' + formatRupiah(trx.amount) + '</td>' +
                    '<td>' + getStatusBadge(trx.status) + '</td>' +
                '</tr>'
            );
        }).join('');
    }

    function renderAdmins(admins) {
        const tbody = document.getElementById('tbody-admin');

        if (admins.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada admin.</td></tr>';
            return;
        }

        tbody.innerHTML = admins.map(function (admin) {
            const avatar = admin.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(admin.name) + '&background=059669&color=fff';

            return (
                '<tr>' +
                    '<td><img src="' + avatar + '" class="rounded-circle" width="40" height="40" style="object-fit: cover;"></td>' +
                    '<td class="fw-bold">' + escapeHtml(admin.name) + '<br><small class="text-muted fw-normal">' + escapeHtml(admin.email) + '</small></td>' +
                    '<td><span class="badge bg-primary">Admin</span></td>' +
                    '<td><span class="text-success fw-semibold small"><i class="bi bi-circle-fill"></i> Aktif</span></td>' +
                    '<td><span class="text-muted small">users.role = admin</span></td>' +
                '</tr>'
            );
        }).join('');
    }

    async function loadAdminDashboardData() {
        try {
            const stats = await fetchAdmin('stats');
            const transactions = await fetchAdmin('transactions');
            const programs = await fetchAdmin('programs');
            const users = await fetchAdmin('users');
            const admins = await fetchAdmin('admins');

            semuaTransaksiAdmin = transactions;
            renderStats(stats);
            renderPendingTransactions(transactions);
            renderAllTransactions(transactions);
            renderPrograms(programs);
            renderDonatur(users);
            renderAdmins(admins);
        } catch (error) {
            console.error('Admin dashboard error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Dashboard',
                text: error.message,
                confirmButtonColor: '#d33'
            });
        }
    }

    // FUNGSI NAVIGASI MENU
    function switchMenu(targetId, title) {
        document.querySelectorAll('#menu-list li').forEach(item => item.classList.remove('active'));
        document.getElementById('menu-' + targetId).classList.add('active');
        document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active-section'));
        document.getElementById('sec-' + targetId).classList.add('active-section');
        document.getElementById('top-title').innerText = title;

        // Render chart hanya jika menu laporan diklik
        if(targetId === 'laporan') { initChart(); }
    }

    function logout() {
        localStorage.removeItem("isLoggedIn");
        localStorage.removeItem("userRole");
        window.location.href = 'index.php'; 
    }

    // FUNGSI INVOICE
    let currentTrxId = ""; 
    function bukaInvoiceById(transactionId) {
        const trx = semuaTransaksiAdmin.find(function (item) {
            return Number(item.id) === Number(transactionId);
        });

        if (!trx) {
            Swal.fire({ icon: 'error', title: 'Transaksi Tidak Ditemukan', confirmButtonColor: '#d33' });
            return;
        }

        currentTransactionId = trx.id;
        currentTrxId = trx.trx_code;
        document.getElementById('inv-id').innerText = '#' + trx.trx_code;
        document.getElementById('inv-name').innerText = trx.donor_name;
        document.getElementById('inv-type').innerText = trx.program_title;
        document.getElementById('inv-nominal').innerText = formatRupiah(trx.amount);
        document.getElementById('inv-date').innerText = formatTanggal(trx.created_at);
        document.getElementById('inv-method').innerText = trx.payment_method;
        new bootstrap.Modal(document.getElementById('invoiceModal')).show();
    }

    function bukaInvoice(id, name, type, nominal, date, method) {
        currentTransactionId = null;
        currentTrxId = id; 
        document.getElementById('inv-id').innerText = '#' + id; 
        document.getElementById('inv-name').innerText = name; 
        document.getElementById('inv-type').innerText = type; 
        document.getElementById('inv-nominal').innerText = nominal; 
        document.getElementById('inv-date').innerText = date; 
        document.getElementById('inv-method').innerText = method;
        new bootstrap.Modal(document.getElementById('invoiceModal')).show();
    }

    async function updateTransactionStatus(newStatus) {
        if (!currentTransactionId) {
            Swal.fire({ icon: 'error', title: 'Transaksi database tidak ditemukan', confirmButtonColor: '#d33' });
            return;
        }

        try {
            const response = await fetch('./api/admin.php?action=verify', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    transaction_id: currentTransactionId,
                    new_status: newStatus
                })
            });
            const responseText = await response.text();
            const result = JSON.parse(responseText);

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal update transaksi');
            }

            bootstrap.Modal.getInstance(document.getElementById('invoiceModal')).hide();
            await loadAdminDashboardData();

            Swal.fire({
                icon: newStatus === 'success' ? 'success' : 'error',
                title: newStatus === 'success' ? 'Transaksi Diverifikasi!' : 'Transaksi Ditolak',
                text: result.message,
                confirmButtonColor: newStatus === 'success' ? '#059669' : '#d33'
            });
        } catch (error) {
            console.error('Verify error:', error);
            Swal.fire({ icon: 'error', title: 'Gagal Update', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    function aksiVerifikasi() { 
        updateTransactionStatus('success');
    }

    function aksiTolak() { 
        updateTransactionStatus('failed');
    }

    // FUNGSI SIMPAN PROGRAM BARU

    let currentEditProgramId = null;

    async function simpanProgram() {
        const form = document.getElementById('formProgramBaru');
        const formData = new FormData();
        // Ambil value dari input form
        const title = form.querySelector('input[type="text"]').value;
        const category = form.querySelector('select').value;
        const target_amount = form.querySelector('input[type="number"]').value;
        const description = form.querySelector('textarea').value;
        const imageInput = form.querySelector('input[type="file"]');
        let image_url = '';
        // Untuk demo, ambil nama file saja. Untuk produksi, harus upload file ke server dan simpan URL-nya
        if (imageInput && imageInput.files.length > 0) {
            image_url = imageInput.files[0].name;
        }

        formData.append('title', title);
        formData.append('category', category);
        formData.append('target_amount', target_amount);
        formData.append('description', description);
        formData.append('image_url', image_url);

        try {
            const response = await fetch('./api/create-program.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (!result.success) throw new Error(result.message);

            bootstrap.Modal.getInstance(document.getElementById('tambahProgramModal')).hide();
            Swal.fire({ icon: 'success', title: 'Program Diterbitkan!', text: result.message, confirmButtonColor: '#059669' });
            form.reset();
            // Reload data program
            const programs = await fetchAdmin('programs');
            renderPrograms(programs);
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    async function editProgram(programId) {
        try {
            const programs = await fetchAdmin('programs');
            const program = programs.find(p => p.id == programId);

            if (!program) throw new Error('Program tidak ditemukan');

            currentEditProgramId = programId;
            // Isi form edit dengan data program
            document.getElementById('editTitle').value = program.title;
            document.getElementById('editCategory').value = program.category;
            document.getElementById('editTargetAmount').value = program.target_amount;
            document.getElementById('editDescription').value = program.description;
            document.getElementById('editStatus').value = program.status;
            
            new bootstrap.Modal(document.getElementById('editProgramModal')).show();
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    async function simpanEditProgram() {
        const title = document.getElementById('editTitle').value;
        const category = document.getElementById('editCategory').value;
        const target_amount = parseFloat(document.getElementById('editTargetAmount').value);
        const description = document.getElementById('editDescription').value;
        const status = document.getElementById('editStatus').value;

        if (!title || !category || target_amount <= 0) {
            Swal.fire({ icon: 'warning', title: 'Data Tidak Lengkap', confirmButtonColor: '#d33' });
            return;
        }

        try {
            const response = await fetch('./api/edit-program.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    program_id: currentEditProgramId,
                    title: title,
                    category: category,
                    target_amount: target_amount,
                    description: description,
                    status: status
                })
            });
            const result = await response.json();

            if (!result.success) throw new Error(result.message);

            bootstrap.Modal.getInstance(document.getElementById('editProgramModal')).hide();
            Swal.fire({ icon: 'success', title: 'Program Diperbarui!', text: result.message, confirmButtonColor: '#059669' });
            
            // Reload data program
            const programs = await fetchAdmin('programs');
            renderPrograms(programs);
        } catch (error) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
        }
    }

    async function hapusProgram(programId, programTitle) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Program?',
            text: 'Apakah Anda yakin ingin menghapus program "' + programTitle + '"?',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch('./api/delete-program.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ program_id: programId })
                    });
                    const data = await response.json();

                    if (!data.success) throw new Error(data.message);

                    Swal.fire({ icon: 'success', title: 'Program Dihapus!', text: data.message, confirmButtonColor: '#059669' });
                    
                    // Reload data program
                    const programs = await fetchAdmin('programs');
                    renderPrograms(programs);
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: error.message, confirmButtonColor: '#d33' });
                }
            }
        });
    }

    // FUNGSI INISIALISASI GRAFIK CHART.JS (Pemasukan vs Pengeluaran)
    // FUNGSI INISIALISASI GRAFIK CHART.JS (Versi Baru)
    let myBarChart = null;
    let myDonutChart = null;

    function initChart() {
        if(myBarChart && myDonutChart) return; // Mencegah render ulang ganda

        // 1. Konfigurasi Bar Chart (Pemasukan vs Pengeluaran)
        const ctxBar = document.getElementById('barChart').getContext('2d');
        myBarChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Nov', 'Des', 'Jan', 'Feb', 'Mar', 'Apr'],
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: [5.8, 6.5, 7.2, 9.1, 7.3, 7.8], // Data jutaan
                        backgroundColor: '#059669', // Emerald
                        borderRadius: 4,
                        barPercentage: 0.6
                    },
                    {
                        label: 'Pengeluaran',
                        data: [2.5, 3.9, 3.6, 4.2, 3.9, 3.9], 
                        backgroundColor: '#ef4444', // Merah
                        borderRadius: 4,
                        barPercentage: 0.6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } },
                    tooltip: { callbacks: { label: function(context) { return "Rp " + context.raw + " Juta"; } } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Konfigurasi Donut Chart (Distribusi Kategori DonasiKu)
        const ctxDonut = document.getElementById('donutChart').getContext('2d');
        myDonutChart = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Bencana Alam', 'Pendidikan & Yatim', 'Kesehatan', 'Pangan & Sembako', 'Lainnya'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#ef4444', // Merah Bencana
                        '#0ea5e9', // Biru Pendidikan
                        '#10b981', // Hijau Kesehatan
                        '#f59e0b', // Oranye Pangan
                        '#64748b'  // Abu-abu Lainnya
                    ],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%', // Besaran lubang tengah
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: {size: 11} } }
                }
            }
        });
    }

    // Simulasi saat tombol "Terapkan" di klik
    function updateChartData() {
        Swal.fire({
            icon: 'info', title: 'Memuat Data...', text: 'Mengambil laporan untuk periode yang dipilih.',
            timer: 1000, showConfirmButton: false
        });
    }

    // FUNGSI SIMPAN PENGATURAN (Untuk Rekening & Admin)
    function simpanPengaturan(pesan) {
        // Tutup semua modal yang terbuka
        let modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            let instance = bootstrap.Modal.getInstance(modal);
            if (instance) { instance.hide(); }
        });

        // Munculkan notifikasi sukses
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: pesan,
            confirmButtonColor: '#059669'
        });
    }

    // FUNGSI HAPUS ADMIN DENGAN KONFIRMASI
    function hapusAdmin(nama) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Akses?',
            text: `Anda yakin ingin mencabut hak akses untuk admin ${nama}?`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Cabut Akses',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Dihapus!',
                    text: `Akses untuk ${nama} berhasil dicabut.`,
                    confirmButtonColor: '#059669'
                });
                // Note: Di sistem nyata, baris tabel akan dihapus via JS/Backend di sini
            }
        });
    }

    document.addEventListener('DOMContentLoaded', loadAdminDashboardData);
</script>
</body>
</html>
