<?php include 'component/header.php'; ?>

<style>
    .hero-catalog {
        background:
            linear-gradient(rgba(5, 150, 105, 0.7), rgba(5, 150, 105, 0.85)),
            url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=1200&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 80px 0;
        color: white;
        text-align: center;
    }

    .nav-pills .nav-link {
        color: #64748b;
        font-weight: 600;
        border-radius: 50px;
        padding: 8px 20px;
        margin: 5px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        cursor: pointer;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }

    .nav-pills .nav-link:hover {
        color: #059669;
        border-color: #059669;
    }

    .nav-pills .nav-link.active {
        background-color: #059669;
        color: white;
        border-color: #059669;
        box-shadow: 0 4px 10px rgba(5, 150, 105, 0.2);
    }

    .program-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: none;
    }

    .program-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .program-image {
        height: 200px;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .progress-container {
        padding: 15px;
    }

    .progress-bar {
        background-color: #059669;
    }

    .category-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    /* Style untuk Badge Timer Real-time */
    .timer-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        background-color: #fff3cd;
        color: #856404;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid #ffeeba;
    }
    .timer-badge.danger {
        background-color: #f8d7da;
        color: #842029;
        border-color: #f5c2c7;
    }

    .category-jariyah { background: #e0f2fe; color: #0369a1; }
    .category-yatim { background: #fef3c7; color: #b45309; }
    .category-pangan { background: #dcfce7; color: #15803d; }
    .category-darurat { background: #fee2e2; color: #b91c1c; }

    @keyframes fadeInScale {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }

    .program-item {
        animation: fadeInScale 0.4s ease-in-out;
    }

    .loading-spinner {
        text-align: center;
        padding: 40px;
    }
</style>

<!-- Tambahkan CDN Bootstrap Icon jika belum ada di header -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<header class="hero-catalog mb-4">
    <div class="container" data-aos="fade-down">
        <h2 class="fw-bold mb-2">Pilih Program Kebaikan</h2>
        <p class="lead mb-0">Temukan kampanye donasi yang ingin Anda bantu hari ini.</p>
    </div>
</header>

<div class="container mb-5">
    <!-- Filter Navigasi -->
    <div class="d-flex justify-content-center flex-wrap mb-5" data-aos="fade-up" id="filter-container">
        <ul class="nav nav-pills justify-content-center">
            <li class="nav-item">
                <button class="nav-link active filter-btn" data-filter="all">Semua Program</button>
            </li>
            <li class="nav-item">
                <button class="nav-link filter-btn" data-filter="jariyah">Sedekah Jariyah</button>
            </li>
            <li class="nav-item">
                <button class="nav-link filter-btn" data-filter="yatim">Anak Yatim</button>
            </li>
            <li class="nav-item">
                <button class="nav-link filter-btn" data-filter="pangan">Pangan</button>
            </li>
            <li class="nav-item">
                <button class="nav-link filter-btn" data-filter="darurat">Darurat</button>
            </li>
        </ul>
    </div>

    <!-- Status Loading -->
    <div id="loading" class="loading-spinner" style="display: none;">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Memuat program donasi...</p>
    </div>

    <!-- Status Jika Kosong -->
    <div id="empty-state" class="text-center py-5" style="display: none;">
        <i class="bi bi-inbox fs-1 text-muted"></i>
        <h5 class="mt-3 text-muted">Belum ada program donasi.</h5>
    </div>

    <!-- Container Program -->
    <div class="row" id="programs-container">
        <!-- Programs dirender di sini -->
    </div>
</div>

<?php include 'component/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let allPrograms = []; 
    let timerInterval; // Variabel penampung interval timer

    function formatCurrency(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
    }

    function getCategoryClass(category) {
        return 'category-' + (category || 'jariyah');
    }

    function getCategoryLabel(category) {
        const labels = {
            jariyah: 'Sedekah Jariyah',
            yatim: 'Anak Yatim',
            pangan: 'Pangan',
            darurat: 'Darurat'
        };
        return labels[category] || category || 'Lainnya';
    }

    // Fungsi Render HTML Kartu Donasi
    function createProgramCard(program) {
        const progress = program.target_amount > 0 
            ? Math.min(100, Math.round((program.collected_amount / program.target_amount) * 100))
            : 0;
            
        const imageUrl = program.image_url ? program.image_url : 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=600&q=80';
        
        // Ambil end_date dari database. Jika tidak ada, default +30 hari dari sekarang agar fitur timer tetap bisa didemonstrasikan.
        const fallbackDate = new Date(Date.now() + 30 * 24 * 60 * 60 * 1000).toISOString();
        const targetDate = program.end_date ? program.end_date : fallbackDate;

        return (
            '<div class="col-lg-4 col-md-6 mb-4 program-item">' +
                '<div class="card program-card h-100">' +
                    '<img src="' + imageUrl + '" class="card-img-top program-image" alt="' + program.title + '">' +
                    '<div class="card-body d-flex flex-column">' +
                        
                        // Bagian Badge Kategori & Timer
                        '<div class="d-flex justify-content-between align-items-start mb-2">' +
                            '<span class="category-badge ' + getCategoryClass(program.category) + '">' +
                                getCategoryLabel(program.category) +
                            '</span>' +
                            '<div class="timer-badge" data-enddate="' + targetDate + '">' +
                                '<i class="bi bi-clock"></i> <span class="time-text">Menghitung...</span>' +
                            '</div>' +
                        '</div>' +

                        '<h5 class="card-title fw-bold mt-1">' + program.title + '</h5>' +
                        '<p class="card-text text-muted small flex-grow-1">' +
                            (program.description ? program.description.substring(0, 100) + '...' : 'Program donasi untuk kebaikan bersama.') +
                        '</p>' +
                        '<div class="mt-auto">' +
                            '<div class="d-flex justify-content-between small mb-1">' +
                                '<span class="text-muted">Terkumpul</span>' +
                                '<span class="fw-bold text-success">' + progress + '%</span>' +
                            '</div>' +
                            '<div class="progress mb-2" style="height: 8px;">' +
                                '<div class="progress-bar" role="progressbar" style="width: ' + progress + '%"></div>' +
                            '</div>' +
                            '<div class="d-flex justify-content-between">' +
                                '<small class="fw-bold">' + formatCurrency(program.collected_amount || 0) + '</small>' +
                                '<small class="text-muted">Target: ' + formatCurrency(program.target_amount || 0) + '</small>' +
                            '</div>' +
                            
                            '<a href="detail-donasi.php?id=' + program.id + '" class="btn btn-success w-100 mt-3 fw-bold d-block text-center text-decoration-none">' +
                                'Berikan Donasi' +
                            '</a>' +

                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>'
        );
    }

    // Fungsi Update Countdown Secara Real-time
    function startCountdown() {
        if (timerInterval) clearInterval(timerInterval);

        timerInterval = setInterval(() => {
            const timerElements = document.querySelectorAll('.timer-badge');
            
            timerElements.forEach(badge => {
                const endDateStr = badge.getAttribute('data-enddate');
                const timeText = badge.querySelector('.time-text');
                
                const countDownDate = new Date(endDateStr).getTime();
                const now = new Date().getTime();
                const distance = countDownDate - now;

                // Jika waktu sudah habis
                if (distance < 0) {
                    timeText.innerHTML = "Berakhir";
                    badge.classList.add('danger'); // Mengubah warna jadi merah
                    return;
                }

                // Kalkulasi waktu
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Format tampilan teks
                if (days > 0) {
                    timeText.innerHTML = days + " Hari " + hours + " Jam";
                } else if (hours > 0) {
                    timeText.innerHTML = hours + " Jam " + minutes + " Mnt";
                } else {
                    timeText.innerHTML = minutes + " Mnt " + seconds + " Dtk";
                    badge.classList.add('danger'); // Ubah merah jika sisa waktu < 1 jam
                }
            });
        }, 1000); // Update setiap 1 detik (1000 ms)
    }

    // Tampilkan Program ke HTML
    function displayPrograms(programs) {
        const container = document.getElementById('programs-container');
        const emptyState = document.getElementById('empty-state');
        
        if (programs.length === 0) {
            container.innerHTML = '';
            emptyState.style.display = 'block';
            return;
        }
        
        emptyState.style.display = 'none';
        container.innerHTML = programs.map(createProgramCard).join('');
        
        // Memulai hitung mundur setiap kali kartu selesai dicetak
        startCountdown();
    }

    function showError(message) {
        document.getElementById('loading').style.display = 'none';
        Swal.fire({ icon: 'error', title: 'Gagal Memuat Data', text: message });
    }

    async function loadPrograms() {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('programs-container').innerHTML = '';
        
        try {
            const response = await fetch('./api/programs.php?action=list');
            const result = await response.json();
            
            document.getElementById('loading').style.display = 'none';
            
            if (response.ok && result.success) {
                allPrograms = result.data || [];
                displayPrograms(allPrograms); 
            } else {
                showError(result.message || 'Gagal mengambil data program.');
            }
        } catch (error) {
            console.error('Error fetching programs:', error);
            showError('Terjadi kesalahan koneksi saat memuat data dari server.');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadPrograms(); 
        
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                
                if (filterValue === 'all') {
                    displayPrograms(allPrograms);
                } else {
                    const filteredPrograms = allPrograms.filter(p => p.category === filterValue);
                    displayPrograms(filteredPrograms);
                }
            });
        });
    });
</script>