<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Donatur - DonasiKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            overflow-x: hidden;
        }

        .text-emerald {
            color: #059669;
        }

        .bg-emerald {
            background-color: #059669;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 100;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #059669;
            color: white;
            text-align: center;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 1em;
            display: block;
            color: #555;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
            cursor: pointer;
        }

        #sidebar ul li a:hover,
        #sidebar ul li.active>a {
            color: #059669;
            background: #eef2f0;
            border-right: 4px solid #059669;
        }

        /* Content Styling */
        #content {
            width: 100%;
            padding: 20px;
        }

        .top-navbar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            padding: 10px 20px;
            margin-bottom: 30px;
        }

        .dash-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
            transition: 0.3s;
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
        }

        .table-custom th,
        .table-custom td {
            padding: 15px;
            vertical-align: middle;
        }

        /* SPA Halaman Dinamis */
        .content-section {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }

        .content-section.active-section {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Foto Profil Edit */
        .profile-edit-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #059669;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .dash-avatar {
            object-fit: cover;
        }

        /* Definisi tombol emerald dan tombol kamera profil */
        .btn-emerald {
            background-color: #059669;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-emerald:hover {
            background-color: #047857;
            color: white;
        }

        .btn-camera-profile {
            background-color: #059669 !important;
            color: white !important;
            border: 3px solid #ffffff !important;
            position: absolute;
            bottom: 5px;
            right: 5px;
            z-index: 10;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Filter Riwayat Desain Baru ala Gambar */
        .filter-container {
            background: #ffffff;
            border-radius: 12px;
            padding: 6px;
            display: flex;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
            width: 100%;
            max-width: 600px;
            border: 1px solid #eef2f5;
        }

        .filter-btn-riwayat {
            border-radius: 8px;
            border: none;
            background: transparent;
            color: #64748b;
            font-weight: 600;
            flex: 1;
            padding: 12px 0;
            transition: 0.3s;
            font-size: 0.95rem;
        }

        .filter-btn-riwayat:hover {
            color: #059669;
        }

        .filter-btn-riwayat.active {
            background: #059669;
            color: white;
            box-shadow: 0 2px 5px rgba(5, 150, 105, 0.2);
        }

        /* Badge Status Khusus Tabel Riwayat */
        .badge-status-berhasil,
        .badge-status-pending {
            border-radius: 6px;
            padding: 6px 12px;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-status-berhasil {
            background-color: #059669;
            color: white;
        }

        .badge-status-pending {
            background-color: #facc15;
            color: #854d0e;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="d-none d-md-block">
            <div class="sidebar-header">
                <h3 class="fw-bold mb-0">DonasiKu</h3>
                <small>Panel Donatur</small>
            </div>

            <ul class="list-unstyled components" id="menu-list">
                <li class="active" id="menu-dashboard">
                    <a onclick="switchMenu('dashboard', 'Dashboard Saya')">
                        <i class="bi bi-house-door-fill me-2"></i>
                        Dashboard Saya
                    </a>
                </li>
                <li>
                    <a href="donasi.php">
                        <i class="bi bi-heart-fill me-2"></i>
                        Mulai Donasi
                    </a>
                </li>
                <li id="menu-riwayat">
                    <a onclick="switchMenu('riwayat', 'Riwayat Transaksi')">
                        <i class="bi bi-clock-history me-2"></i>
                        Riwayat Transaksi
                    </a>
                </li>
                <li id="menu-profil">
                    <a onclick="switchMenu('profil', 'Profil Akun')">
                        <i class="bi bi-person-fill me-2"></i>
                        Profil Akun
                    </a>
                </li>
            </ul>

            <div class="p-3 mt-5">
                <a href="#" onclick="logout()" class="btn btn-danger w-100 rounded-pill">
                    <i class="bi bi-box-arrow-left me-2"></i>
                    Keluar
                </a>
            </div>
        </nav>

        <div id="content">
            <div class="top-navbar d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-emerald" id="top-title">Dashboard Saya</h5>
                </div>

                <div class="d-flex align-items-center">
                    <span class="me-3 fw-semibold text-muted" id="dash-nama">Assalamu'alaikum, Pengguna</span>
                    <img
                        id="dash-avatar"
                        src=""
                        class="dash-avatar rounded-circle border border-2 border-success shadow-sm"
                        width="40"
                        height="40"
                        alt="Avatar"
                    >
                </div>
            </div>

            <div id="sec-dashboard" class="content-section active-section">
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card dash-card p-4 bg-emerald text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="fw-semibold text-white-50 mb-1">Total Donasi Saya</p>
                                    <h3 class="fw-bold mb-0" id="stat-total-donasi">Rp 0</h3>
                                </div>

                                <div class="icon-box bg-white bg-opacity-25 text-white">
                                    <i class="bi bi-award-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card dash-card p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted fw-semibold mb-1">Total Program Dibantu</p>
                                    <h3 class="fw-bold text-emerald mb-0" id="stat-total-program">0 Program</h3>
                                </div>

                                <div class="icon-box bg-success bg-opacity-10 text-success">
                                    <i class="bi bi-heart-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <a href="donasi.php" class="btn btn-emerald px-4 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-plus-circle me-2"></i>
                        Buat Donasi Baru
                    </a>
                </div>

                <h5 class="fw-bold mb-3">Transaksi Terbaru</h5>

                <div class="table-custom table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Program</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-dashboard-singkat">
                            </tbody>
                    </table>
                </div>
            </div>

            <div id="sec-riwayat" class="content-section">
                <div class="filter-container">
                    <button class="filter-btn-riwayat active" onclick="filterRiwayat('Semua', this)">Semua</button>
                    <button class="filter-btn-riwayat" onclick="filterRiwayat('E-Wallet', this)">E-Wallet</button>
                    <button class="filter-btn-riwayat" onclick="filterRiwayat('Transfer Bank', this)">Transfer Bank</button>
                </div>

                <div class="table-custom table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Program</th>
                                <th>Metode</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-riwayat-full">
                            </tbody>
                    </table>
                </div>
            </div>

            <div id="sec-profil" class="content-section">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card dash-card p-4 text-center">
                            <div class="position-relative d-inline-block mx-auto mb-3">
                                <img id="preview-avatar" src="" class="profile-edit-img" alt="Preview Profil">
                                <button
                                    class="btn btn-camera-profile rounded-circle shadow"
                                    onclick="document.getElementById('uploadFoto').click()"
                                >
                                    <i class="bi bi-camera"></i>
                                </button>
                                <input type="file" id="uploadFoto" accept="image/*" class="form-control d-none">
                            </div>

                            <h5 class="fw-bold mb-1" id="profil-nama-display">Memuat Nama...</h5>
                            <p class="text-muted small mb-0">Donatur Sejak Mar 2026</p>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card dash-card p-4 mb-4">
                            <h6 class="fw-bold mb-4">
                                <i class="bi bi-person-gear me-2"></i>
                                Data Pribadi
                            </h6>

                            <form id="formProfil">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Nama Lengkap</label>
                                        <input type="text" id="inputNama" class="form-control" placeholder="Nama Anda" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Nomor WhatsApp</label>
                                        <input type="text" class="form-control" value="0812345678xx" placeholder="08xxxx">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label small fw-bold">Alamat Email</label>
                                        <input type="email" id="inputEmail" class="form-control bg-light" value="" readonly>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-emerald px-4 rounded-pill fw-bold">
                                            Simpan Profil
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function () {
            let semuaRiwayat = [];
            let fileGambarBaru = null;

            function getBadgeClass(status) {
                return status.toLowerCase() === "berhasil" || status.toLowerCase() === "success"
                    ? "badge-status-berhasil"
                    : "badge-status-pending";
            }

            function getStatusLabel(status) {
                const labels = {
                    success: 'Berhasil',
                    pending: 'Pending',
                    failed: 'Gagal'
                };

                return labels[status] || status;
            }

            function getPaymentType(paymentMethod) {
                if (paymentMethod.toLowerCase().includes('wallet') || paymentMethod.toLowerCase().includes('qris')) {
                    return 'E-Wallet';
                }

                return 'Transfer Bank';
            }

            function formatRupiah(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(amount || 0));
            }

            function formatTanggal(tanggal) {
                return new Date(tanggal).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            }

            function normalizeDonation(donation) {
                const paymentMethod = donation.payment_method || '-';

                return {
                    id: donation.trx_code,
                    tanggal: formatTanggal(donation.created_at),
                    program: donation.program_title,
                    metode: paymentMethod,
                    tipe: getPaymentType(paymentMethod),
                    nominal: formatRupiah(donation.amount),
                    amount: Number(donation.amount || 0),
                    status: getStatusLabel(donation.status),
                    rawStatus: donation.status
                };
            }

            function createRowHTML(trx) {
                const badgeClass = getBadgeClass(trx.status);

                return (
                    '<tr>' +
                        '<td class="fw-bold text-dark">' + trx.id + '</td>' +
                        '<td class="text-muted">' + trx.tanggal + '</td>' +
                        '<td class="fw-bold">' + trx.program + '</td>' +
                        '<td class="text-muted">' + trx.metode + '</td>' +
                        '<td class="fw-bold text-dark">' + trx.nominal + '</td>' +
                        '<td><span class="' + badgeClass + '">' + trx.status + '</span></td>' +
                    '</tr>'
                );
            }

            function renderTabelRiwayat(filterTipe) {
                const tabelFull = document.getElementById("tabel-riwayat-full");
                let dataTampil = semuaRiwayat;

                tabelFull.innerHTML = "";

                if (filterTipe !== "Semua") {
                    dataTampil = semuaRiwayat.filter(function (trx) {
                        return trx.tipe === filterTipe;
                    });
                }

                if (dataTampil.length === 0) {
                    tabelFull.innerHTML =
                        '<tr>' +
                            '<td colspan="6" class="text-center text-muted py-4">' +
                                'Tidak ada transaksi ditemukan untuk filter <b>' + filterTipe + '</b>.' +
                            '</td>' +
                        '</tr>';
                    return;
                }

                dataTampil.forEach(function (trx) {
                    tabelFull.innerHTML += createRowHTML(trx);
                });
            }

            function renderTabelDashboard(data) {
                const tabelDash = document.getElementById("tabel-dashboard-singkat");

                tabelDash.innerHTML = "";

                if (data.length === 0) {
                    tabelDash.innerHTML =
                        '<tr>' +
                            '<td colspan="6" class="text-center text-muted py-4">Belum ada transaksi donasi.</td>' +
                        '</tr>';
                    return;
                }

                data.slice(0, 4).forEach(function (trx) {
                    tabelDash.innerHTML += createRowHTML(trx);
                });
            }

            function hitungStatistik(data) {
                let totalDonasi = 0;
                const programUnik = new Set();

                data.forEach(function (trx) {
                    if (trx.rawStatus === 'success' || trx.status.toLowerCase() === 'berhasil') {
                        totalDonasi += trx.amount;
                        programUnik.add(trx.program);
                    }
                });

                document.getElementById("stat-total-donasi").innerText = formatRupiah(totalDonasi);

                document.getElementById("stat-total-program").innerText =
                    programUnik.size + " Program";
            }

            async function loadDonationHistoryFromDatabase() {
                try {
                    const response = await fetch('./api/user-donasi.php');
                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Gagal memuat riwayat donasi');
                    }

                    semuaRiwayat = result.data.donations.map(normalizeDonation);

                    renderTabelRiwayat("Semua");
                    renderTabelDashboard(semuaRiwayat);
                    hitungStatistik(semuaRiwayat);
                } catch (error) {
                    console.error('Donation history error:', error);
                    semuaRiwayat = [];
                    renderTabelRiwayat("Semua");
                    renderTabelDashboard(semuaRiwayat);
                    hitungStatistik(semuaRiwayat);
                }
            }

            window.switchMenu = function (targetId, title) {
                document
                    .querySelectorAll('#menu-list li')
                    .forEach(function (item) {
                        item.classList.remove('active');
                    });

                document.getElementById('menu-' + targetId).classList.add('active');

                document
                    .querySelectorAll('.content-section')
                    .forEach(function (section) {
                        section.classList.remove('active-section');
                    });

                document.getElementById('sec-' + targetId).classList.add('active-section');
                document.getElementById('top-title').innerText = title;
            };

            window.filterRiwayat = function (tipeFilter, elemenTombol) {
                document
                    .querySelectorAll('.filter-btn-riwayat')
                    .forEach(function (btn) {
                        btn.classList.remove('active');
                    });

                elemenTombol.classList.add('active');
                renderTabelRiwayat(tipeFilter);
            };

            window.logout = function () {
                Swal.fire({
                    icon: 'question',
                    title: 'Keluar Akun?',
                    text: 'Anda yakin ingin keluar dari halaman donatur?',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal'
                }).then(function (result) {
                    if (result.isConfirmed) {
                        localStorage.removeItem("isLoggedIn");
                        localStorage.removeItem("userRole");
                        window.location.href = 'index.php';
                    }
                });
            };

            function applyProfile(profile) {
                const profileName = profile.name || "Pengguna";
                const profileEmail = profile.email || "";
                const storedAvatar = localStorage.getItem("avatarUrl");
                const avatarSrc = storedAvatar
                    ? storedAvatar
                    : "https://ui-avatars.com/api/?name=" + encodeURIComponent(profileName) + "&background=059669&color=fff";

                localStorage.setItem("userName", profileName);
                document.getElementById("dash-nama").innerText = "Assalamu'alaikum, " + profileName.split(' ')[0];
                document.getElementById("inputNama").value = profileName;
                document.getElementById("inputEmail").value = profileEmail;
                document.getElementById("profil-nama-display").innerText = profileName;
                document.getElementById("dash-avatar").src = avatarSrc;
                document.getElementById("preview-avatar").src = avatarSrc;
            }

            async function loadProfileFromDatabase() {
                try {
                    const response = await fetch('./api/update-profile.php');
                    const result = await response.json();

                    if (!response.ok || !result.success) {
                        throw new Error(result.message || 'Gagal memuat profil');
                    }

                    applyProfile(result.data);
                } catch (error) {
                    console.error('Profile error:', error);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Profil Tidak Terhubung',
                        text: 'Silakan login ulang agar data profil sesuai database.',
                        confirmButtonColor: '#059669'
                    }).then(function () {
                        window.location.href = 'login.php?redirect=user-dashboard.php';
                    });
                }
            }

            document.addEventListener("DOMContentLoaded", function () {
                const storedName = localStorage.getItem("userName") || "Pengguna";
                const storedAvatar = localStorage.getItem("avatarUrl");
                const avatarSrc = storedAvatar
                    ? storedAvatar
                    : "https://ui-avatars.com/api/?name=" + encodeURIComponent(storedName) + "&background=059669&color=fff";

                document.getElementById("dash-nama").innerText = "Assalamu'alaikum, " + storedName.split(' ')[0];
                document.getElementById("inputNama").value = storedName;
                document.getElementById("profil-nama-display").innerText = storedName;
                document.getElementById("dash-avatar").src = avatarSrc;
                document.getElementById("preview-avatar").src = avatarSrc;
                loadProfileFromDatabase();

                loadDonationHistoryFromDatabase();

                document.getElementById("uploadFoto").addEventListener("change", function (event) {
                    const file = event.target.files[0];

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            document.getElementById("preview-avatar").src = e.target.result;
                            fileGambarBaru = e.target.result;
                        };

                        reader.readAsDataURL(file);
                    }
                });

                document.getElementById("formProfil").addEventListener("submit", async function (event) {
                    event.preventDefault();

                    const namaBaru = document.getElementById("inputNama").value.trim();

                    if (!namaBaru) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Nama Wajib Diisi',
                            text: 'Nama lengkap tidak boleh kosong.',
                            confirmButtonColor: '#059669'
                        });
                        return;
                    }

                    try {
                        const response = await fetch('./api/update-profile.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                name: namaBaru
                            })
                        });

                        const result = await response.json();

                        if (!response.ok || !result.success) {
                            throw new Error(result.message || 'Gagal memperbarui profil');
                        }

                        if (fileGambarBaru) {
                            localStorage.setItem("avatarUrl", fileGambarBaru);
                        }

                        applyProfile(result.data);

                        if (fileGambarBaru) {
                            document.getElementById("dash-avatar").src = fileGambarBaru;
                            document.getElementById("preview-avatar").src = fileGambarBaru;
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Profil Diperbarui!',
                            text: 'Nama berhasil disimpan ke database.',
                            confirmButtonColor: '#059669',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } catch (error) {
                        console.error('Update profile error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: error.message,
                            confirmButtonColor: '#d33'
                        });
                    }
                });

                document.getElementById("inputNama").addEventListener("input", function () {
                    document.getElementById("profil-nama-display").innerText = this.value || "Nama Anda";
                });
            });
        })();
    </script>
</body>

</html>