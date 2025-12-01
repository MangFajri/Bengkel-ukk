@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-white font-weight-bold font-display text-uppercase">Overview Bengkel</h1>
        
        <a href="#" onclick="alert('Fitur Export Excel/PDF akan kita buat nanti ya!')" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50 mr-2"></i>Generate Report
        </a>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155; border-left: 4px solid #4e73df !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pendapatan (Lunas)</div>
                            <div class="h5 mb-0 font-weight-bold text-white">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155; border-left: 4px solid #1cc88a !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Order Bulan Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $monthlyTransactions }} Service</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155; border-left: 4px solid #36b9cc !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Antrian / Proses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $pendingJobs }} Kendaraan</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wrench fa-2x text-gray-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155; border-left: 4px solid #f6c23e !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pelanggan Terdaftar</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $totalCustomers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-primary text-uppercase">Grafik Pemasukan {{ date('Y') }}</h6>
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
                    <h6 class="m-0 font-weight-bold text-primary text-uppercase">Order Terbaru</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($recentTransactions as $trx)
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-bottom: 1px solid #334155;">
                                <div>
                                    <div class="text-white font-weight-bold text-uppercase">
                                        {{ $trx->vehicle->plate_number ?? 'Tanpa Plat' }}
                                    </div>
                                    <div class="small text-gray-400">
                                        <i class="fas fa-user fa-xs mr-1"></i> {{ $trx->customer->name ?? 'User Hapus' }}
                                    </div>
                                </div>

                                @php
                                    $statusName = $trx->serviceStatus->name ?? 'Unknown';
                                    $badgeClass = 'secondary';
                                    if(str_contains(strtolower($statusName), 'selesai')) $badgeClass = 'success';
                                    elseif(str_contains(strtolower($statusName), 'kerja')) $badgeClass = 'info';
                                    elseif(str_contains(strtolower($statusName), 'pending')) $badgeClass = 'warning';
                                @endphp
                                <span class="badge badge-{{ $badgeClass }} px-2">{{ $statusName }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-gray-500 py-4" style="background-color: transparent;">
                                <i class="fas fa-folder-open mb-2"></i><br>Belum ada transaksi
                            </li>
                        @endforelse
                    </ul>
                    <div class="text-center p-3">
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-outline-primary">
                            Kelola Semua &rarr;
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-success text-uppercase">🏆 Sparepart Terlaris</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if(isset($topSpareparts))
                            @forelse($topSpareparts as $part)
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-bottom: 1px solid #334155;">
                                    <span class="text-white">{{ $part->name }}</span>
                                    <span class="badge badge-success badge-pill">{{ $part->total_sold }} Terjual</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-gray-500 p-3">Belum ada penjualan barang</li>
                            @endforelse
                        @else
                            <li class="list-group-item text-center text-danger p-3">
                                Variabel $topSpareparts belum dikirim dari Controller.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

<script>
    // Set font family dan warna teks default chart biar cocok sama Dark Mode
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Area Chart Code
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            datasets: [{
                label: "Pendapatan",
                lineTension: 0.3,
                // Warna Garis & Area (Biru Bengkel)
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
                data: @json($chartData), // DATA DINAMIS DARI CONTROLLER
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: { left: 10, right: 25, top: 25, bottom: 0 }
            },
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
                        callback: function(value, index, values) {
                            return 'Rp ' + number_format(value);
                        }
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
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': Rp ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
    
    // Helper Format Rupiah JS
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? '.' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? ',' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
@endpush