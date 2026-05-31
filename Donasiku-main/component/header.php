<?php
// Mengambil nama file yang sedang aktif saat ini (misal: index.php, donasi.php, atau about.php)
$halaman_aktif = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DonasiKu - Berbagi Kebaikan</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f8f9fa; 
            padding-top: 76px; 
        }
        
        .text-emerald { color: #059669; }
        .bg-emerald { background-color: #059669; }
        .text-orange { color: #f59e0b; }
        
        /* Navbar */
        .navbar { background-color: #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .navbar-brand { font-size: 1.5rem; }
        
        /* Buttons */
        .btn-emerald { background-color: #059669; color: white; border-radius: 8px; transition: all 0.3s ease; }
        .btn-emerald:hover { background-color: #047857; color: white; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(5, 150, 105, 0.3); }
        .btn-orange { background-color: #f59e0b; color: white; transition: all 0.3s ease; border: none; }
        .btn-orange:hover { background-color: #d97706; color: white; transform: translateY(-3px); box-shadow: 0 5px 15px rgba(245, 158, 11, 0.3); }

        /* Efek Hover & Indikator Menu Navbar */
        .navbar-nav .nav-link {
            border-radius: 8px;
            padding: 8px 16px !important;
            margin: 0 4px;
            color: #475569 !important; /* Warna teks default abu-abu gelap */
            transition: all 0.3s ease-in-out;
        }

        /* 1. Efek ketika cursor berada di atas menu (Hover) */
        .navbar-nav .nav-link:hover {
            background-color: #059669; /* Bar warna hijau di belakang */
            color: #ffffff !important; /* Teks otomatis jadi putih agar kontras */
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);
        }

        /* 2. Indikator Halaman Aktif (Sedang berada di halaman ini) */
        .navbar-nav .nav-link.active-page {
            background-color: #059669; /* Tetap berwarna hijau */
            color: #ffffff !important; /* Teks tetap putih */
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);
        }

        /* Carousel & Cards Layout */
        .carousel-item img { height: 80vh; object-fit: cover; filter: brightness(0.6); }
        .carousel-caption { bottom: 25%; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .card-donasi { border: none; border-radius: 15px; transition: all 0.4s ease; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #ffffff; }
        .card-donasi:hover { transform: translateY(-15px); box-shadow: 0 15px 30px rgba(5,150,105,0.15); border-bottom: 4px solid #059669; }

        /* Style Program Cards */
        .adara-card { border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #ffffff; transition: all 0.3s ease; cursor: pointer; text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%; text-align: left; }
        .adara-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.06); border-color: #059669; }
        .adara-img-wrapper { position: relative; width: 100%; padding-top: 56.25%; overflow: hidden; background-color: #f1f5f9; }
        .adara-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; }
        .adara-category-badge { position: absolute; top: 12px; left: 12px; background: rgba(255, 255, 255, 0.95); color: #ef4444; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .adara-body { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
        .adara-title { font-size: 1.05rem; font-weight: 700; line-height: 1.4; margin-bottom: 10px; color: #1e293b; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-clamp: 2; }
        .adara-creator { font-size: 0.8rem; color: #64748b; margin-bottom: 18px; display: flex; align-items: center; }
        .adara-creator i { color: #0ea5e9; margin-right: 6px; font-size: 0.9rem; }
        .adara-progress { height: 6px; border-radius: 10px; background-color: #f1f5f9; margin-bottom: 12px; }
        .adara-progress .progress-bar { background-color: #ef4444; border-radius: 10px; } 
        .adara-footer { display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; }
        .adara-amount-label { font-size: 0.75rem; color: #64748b; display: block; margin-bottom: 2px; }
        .adara-amount { font-size: 1rem; font-weight: 700; color: #059669; }
        .adara-days { font-size: 0.8rem; font-weight: 600; color: #ef4444; background: #fef2f2; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 6px; }

        footer { background-color: #111827; color: #d1d5db; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-emerald" href="index.php">🎁 DonasiKu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 fw-semibold">
                    
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman_aktif == 'index.php' || $halaman_aktif == '') ? 'active-page' : ''; ?>" href="index.php">Beranda</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman_aktif == 'donasi.php') ? 'active-page' : ''; ?>" href="#" onclick="requireLogin(event)">Layanan Donasi</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($halaman_aktif == 'about.php') ? 'active-page' : ''; ?>" href="about.php">Tentang Kami</a>
                    </li>
                    
                </ul>
                <div class="ms-lg-3">
                    <a href="login.php" class="btn btn-emerald px-4 rounded-pill">Login / Daftar</a>
                </div>
            </div>
        </div>
    </nav>