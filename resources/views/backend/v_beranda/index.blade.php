@extends('backend.v_layouts.app')

@section('content')

{{-- ===== SELAMAT DATANG ===== --}}
<div class="row">
    <div class="col-12">
        <div class="alert alert-success mb-4 d-flex justify-content-between align-items-center flex-wrap" role="alert">
            <div>
                <h4 class="alert-heading">Selamat Datang, {{ Auth::user()->nama }}!</h4>
                Anda login sebagai <b>
                    @if(Auth::user()->isSuperAdmin()) Super Admin
                    @elseif(Auth::user()->isAdmin()) Admin
                    @else Customer
                    @endif
                </b> — Aplikasi Web Programming. Studi Kasus Toko Online.
                <hr>
                <p class="mb-0">Kuliah..? BSI Aja !!!</p>
            </div>

            {{-- ===== JAM & TANGGAL REAL-TIME ===== --}}
            <div class="text-right ml-3">
                <div id="jamRealtime" style="font-size: 1.4rem; font-weight: bold; line-height: 1;"></div>
                <div id="tanggalRealtime" style="font-size: 0.85rem; color: #444;"></div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role !== '2')

{{-- ===== KARTU STATISTIK ===== --}}
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="card shadow h-100 py-2" style="border-left: 4px solid #4e73df;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pesanan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPesanan }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow h-100 py-2" style="border-left: 4px solid #1cc88a;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow h-100 py-2" style="border-left: 4px solid #36b9cc;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Produk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProduk }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6">
        <div class="card shadow h-100 py-2" style="border-left: 4px solid #f6c23e;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Customer</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomer }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== KARTU INFO TAMBAHAN ===== --}}
<div class="row mt-4">
    <div class="col-md-4">
        <div class="card shadow" style="border-left: 4px solid #e74a3b;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pesanan Hari Ini</div>
                    <div class="h5 mb-0 font-weight-bold">{{ $pesananHariIni }}</div>
                </div>
                <i class="fas fa-calendar-day fa-2x text-danger"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow" style="border-left: 4px solid #f6c23e;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Menunggu Pembayaran</div>
                    <div class="h5 mb-0 font-weight-bold">{{ $pesananPending }}</div>
                </div>
                <i class="fas fa-clock fa-2x text-warning"></i>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow" style="border-left: 4px solid #e74a3b;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stok Menipis</div>
                    <div class="h5 mb-0 font-weight-bold">{{ $stokMenipis }} Produk</div>
                </div>
                <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
            </div>
        </div>
    </div>
</div>

{{-- ===== WARNING STOK MENIPIS ===== --}}
@if($stokMenipisList->isNotEmpty())
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-danger shadow">
            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                <b><i class="fas fa-exclamation-triangle mr-2"></i> Peringatan! Stok Produk Menipis</b>
                <span class="badge badge-light">{{ $stokMenipisList->count() }} produk</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th class="text-center">Sisa Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stokMenipisList as $produk)
                        <tr>
                            <td>{{ $produk->nama_produk }}</td>
                            <td>{{ $produk->kategori->nama_kategori ?? '-' }}</td>
                            <td class="text-center">
                                @if($produk->stok == 0)
                                <span class="badge badge-danger">Habis</span>
                                @else
                                <span class="badge badge-warning text-dark">{{ $produk->stok }} tersisa</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('backend.produk.edit', $produk->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Update Stok
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ===== GRAFIK & TABEL ===== --}}
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <b><i class="fas fa-chart-line mr-2"></i> Grafik Pesanan (Real-Time)</b>
                <small>
                    <span class="badge badge-success"><i class="fas fa-circle" style="font-size:6px;"></i> Live</span>
                    diperbarui <span id="grafikUpdateJam">-</span>
                </small>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 320px;">
                    <canvas id="grafikPesanan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                <b><i class="fas fa-fire mr-2"></i> Produk Terlaris</b>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($produkTerlaris as $i => $produk)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><b>{{ $i + 1 }}.</b> {{ $produk->nama_produk }}</span>
                        <span class="badge badge-primary badge-pill">{{ $produk->pesanan_detail_count }} terjual</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted">Belum ada data</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ===== PESANAN TERBARU ===== --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-dark text-white d-flex justify-content-between">
                <b><i class="fas fa-list mr-2"></i> Pesanan Terbaru</b>
                <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Kode Pesanan</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesananTerbaru as $p)
                        <tr>
                            <td><b>{{ $p->kode_pesanan }}</b></td>
                            <td>{{ $p->user->nama ?? 'Guest' }}</td>
                            <td>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                @if(strtolower($p->status) == 'menunggu pembayaran')
                                <span class="badge badge-warning text-dark">Menunggu Pembayaran</span>
                                @elseif(strtolower($p->status) == 'diproses')
                                <span class="badge badge-primary">Diproses</span>
                                @elseif(strtolower($p->status) == 'dikirim')
                                <span class="badge badge-info">Dikirim</span>
                                @elseif(strtolower($p->status) == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                                @else
                                <span class="badge badge-danger">{{ $p->status }}</span>
                                @endif
                            </td>
                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada pesanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endif

@endsection

@push('scripts')
<script src="{{ asset('backend/libs/chartjs/chart.umd.min.js') }}"></script>
<script>
// ===== JAM & TANGGAL REAL-TIME =====
(function() {
    const jamEl = document.getElementById('jamRealtime');
    const tglEl = document.getElementById('tanggalRealtime');
    const hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
    const bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
        'Oktober', 'November', 'Desember'
    ];

    function updateJam() {
        const now = new Date();
        const jam = String(now.getHours()).padStart(2, '0');
        const menit = String(now.getMinutes()).padStart(2, '0');
        const detik = String(now.getSeconds()).padStart(2, '0');
        jamEl.textContent = `${jam}:${menit}:${detik}`;
        tglEl.textContent =
            `${hariList[now.getDay()]}, ${now.getDate()} ${bulanList[now.getMonth()]} ${now.getFullYear()}`;
    }

    updateJam();
    setInterval(updateJam, 1000);
})();

// ===== GRAFIK PESANAN REAL-TIME (polling tiap 15 detik) =====
const ctx = document.getElementById('grafikPesanan');
if (ctx) {
    const grafikPesananChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($grafikLabel) !!},
            datasets: [{
                label: 'Jumlah Pesanan',
                data: {!! json_encode($grafikData) !!},
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.15)',
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4e73df',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#4e73df',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#333',
                    bodyColor: '#333',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        color: '#eaecf4'
                    }
                }
            }
        }
    });

    const grafikUpdateJamEl = document.getElementById('grafikUpdateJam');

    function refreshGrafikPesanan() {
        fetch("{{ route('backend.beranda.grafik-pesanan') }}", {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                grafikPesananChart.data.labels = data.labels;
                grafikPesananChart.data.datasets[0].data = data.data;
                grafikPesananChart.update();
                if (grafikUpdateJamEl) grafikUpdateJamEl.textContent = data.jam;
            })
            .catch(err => console.error('Gagal memperbarui grafik pesanan:', err));
    }

    // Update pertama kali agar label "diperbarui jam" langsung terisi
    refreshGrafikPesanan();

    // Polling tiap 15 detik
    setInterval(refreshGrafikPesanan, 15000);
}
</script>
@endpush