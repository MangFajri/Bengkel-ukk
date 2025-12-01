@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    {{-- Header: Ucapan Selamat Datang Dinamis --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-100 font-weight-bold">Dashboard Overview</h1>
            <p class="text-gray-400 small mb-0">Halo Admin, berikut ringkasan performa bengkel hari ini.</p>
        </div>
        
        {{-- Tombol Generate Report (Dummy) --}}
        <a href="{{ route('admin.reports.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm border-0" style="background-color: #4e73df;">
            <i class="fas fa-chart-line fa-sm text-white-50 mr-2"></i>Lihat Laporan Detail
        </a>
    </div>

    {{-- BARIS 1: 4 KARTU STATUS UTAMA --}}
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pendapatan (Lunas)</div>
                            <div class="h5 mb-0 font-weight-bold text-white">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Transaksi (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $monthlyTransactions }} <small class="text-gray-400">Order</small></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sedang Dikerjakan / Antri
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $pendingJobs }} <small class="text-gray-400">Mobil</small></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Pelanggan</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $totalCustomers }} <small class="text-gray-400">Orang</small></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 2: GRAFIK CHART --}}
    <div class="row">

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan {{ date('Y') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="myAreaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-info">Komposisi Pekerjaan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small text-gray-400">
                        <span class="mr-2"><i class="fas fa-circle text-primary"></i> Booking</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Menunggu</span>
                        <span class="mr-2"><i class="fas fa-circle text-info"></i> Proses</span>
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Selesai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- BARIS 3: TRANSAKSI TERBARU & SPAREPART --}}
    <div class="row">

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-white">Order Terbaru Masuk</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentTransactions as $trx)
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-bottom: 1px solid #334155;">
                                <div>
                                    <div class="font-weight-bold text-white">
                                        {{ $trx->vehicle->plate_number ?? 'N/A' }}
                                    </div>
                                    <div class="small text-gray-500">
                                        {{ $trx->customer->name ?? 'Guest' }} | {{ $trx->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                
                                {{-- Badge Logic --}}
                                @php
                                    $statusLabel = $trx->serviceStatus->label ?? '-';
                                    $badgeColor = 'secondary';
                                    if(Str::contains($statusLabel, 'Selesai')) $badgeColor = 'success';
                                    elseif(Str::contains($statusLabel, 'Kerja')) $badgeColor = 'info';
                                    elseif(Str::contains($statusLabel, 'Tunggu') || Str::contains($statusLabel, 'Booking')) $badgeColor = 'warning';
                                @endphp
                                <span class="badge badge-{{ $badgeColor }}">{{ $statusLabel }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-gray-500 py-4" style="background-color: transparent;">
                                Belum ada transaksi.
                            </li>
                        @endforelse
                    </ul>
                    <div class="card-footer text-center py-3" style="background-color: #1e293b; border-top: 1px solid #334155;">
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-light">Lihat Semua Transaksi</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-warning">Sparepart Terlaris</h6>
                </div>
                <div class="card-body">
                    @if(isset($topSpareparts) && count($topSpareparts) > 0)
                        @php $maxVal = $topSpareparts->first()->total_sold; @endphp
                        
                        @foreach($topSpareparts as $part)
                            <h4 class="small font-weight-bold text-gray-300">
                                {{ $part->name }} <span class="float-right text-warning">{{ $part->total_sold }} terjual</span>
                            </h4>
                            <div class="progress mb-4" style="height: 0.5rem; background-color: #0f172a;">
                                @php 
                                    $width = ($part->total_sold / $maxVal) * 100;
                                    $colors = ['bg-danger', 'bg-warning', 'bg-primary', 'bg-info', 'bg-success'];
                                    $color = $colors[$loop->index % 5];
                                @endphp
                                <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ $width }}%" aria-valuenow="{{ $width }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endforeach
                    @else
                         <div class="text-center text-gray-500 py-5">Belum ada data penjualan barang.</div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
{{-- Memuat Chart.js --}}
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<script>
    // --- KONFIGURASI UMUM CHART DARK MODE ---
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // 1. AREA CHART (PENDAPATAN)
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            datasets: [{
                label: "Pendapatan",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: @json($chartData), // Data dari Controller
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
                xAxes: [{
                    time: { unit: 'date' },
                    gridLines: { display: false, drawBorder: false },
                    ticks: { maxTicksLimit: 7 }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        callback: function(value) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(value); }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: { display: false },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleFontColor: '#6e707e',
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15, yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': Rp ' + new Intl.NumberFormat('id-ID').format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });

    // 2. PIE CHART (KOMPOSISI STATUS)
    var ctxPie = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: ["Booking", "Menunggu", "Proses", "Selesai", "Batal"],
            datasets: [{
                data: @json($pieData), // Data dari Controller
                backgroundColor: ['#4e73df', '#f6c23e', '#36b9cc', '#1cc88a', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#dda20a', '#2c9faf', '#17a673', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15, yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: { display: false },
            cutoutPercentage: 80,
        },
    });
</script>
@endpush