@extends('layouts.mechanic')

@section('title', 'Workshop Monitor')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-white font-weight-bold">Halo, {{ Auth::user()->name }}! 🔧</h1>
            <p class="text-gray-400 mb-0">Selamat bertugas. Tetap semangat dan utamakan keselamatan kerja.</p>
        </div>
        <div class="text-right">
            <span class="badge badge-success px-3 py-2 shadow-sm">
                <i class="fas fa-clock mr-1"></i> {{ now()->format('H:i') }} WIB
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sedang Dikerjakan</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['active'] }} Mobil</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-spin fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['today_done'] }} Unit</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Karir (Selesai)</div>
                            <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['finished'] }} Unit</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-trophy fa-2x text-gray-500 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow mb-4 h-100" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" 
                     style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-wrench mr-2 text-warning"></i>Pekerjaan Saat Ini
                    </h6>
                    @if($currentJob)
                        <span class="badge badge-warning px-3 py-1 font-weight-bold">IN PROGRESS</span>
                    @endif
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    @if($currentJob)
                        <div class="text-center mb-4">
                            <h1 class="display-4 font-weight-bold text-white mb-0">{{ $currentJob->vehicle->plate_number }}</h1>
                            <p class="text-gray-400 h5">{{ $currentJob->vehicle->brand }} {{ $currentJob->vehicle->model }}</p>
                        </div>
                        
                        <div class="bg-dark p-3 rounded border border-secondary mb-4">
                            <h6 class="font-weight-bold text-info mb-2">Daftar Perbaikan:</h6>
                            <ul class="mb-0 pl-3 text-gray-300">
                                @foreach($currentJob->services as $service)
                                    <li>{{ $service->name }}</li>
                                @endforeach
                            </ul>
                            <div class="mt-3 pt-2 border-top border-secondary small text-gray-400">
                                <i class="fas fa-user mr-1"></i> Pemilik: {{ $currentJob->customer->name }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('mechanic.jobs.show', $currentJob->id) }}" class="btn btn-primary btn-block font-weight-bold py-3">
                                    <i class="fas fa-info-circle mr-2"></i> Detail & Sparepart
                                </a>
                            </div>
                            <div class="col-6">
                                {{-- Form Cepat Selesai (Langsung update ke status 4) --}}
                                <form action="{{ route('mechanic.jobs.update-status', $currentJob->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="service_status_id" value="4"> <button type="submit" class="btn btn-success btn-block font-weight-bold py-3 shadow-lg" onclick="return confirm('Pastikan pekerjaan sudah beres total. Lanjutkan?')">
                                        <i class="fas fa-check mr-2"></i> Selesai Sekarang
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('img/undraw_empty.svg') }}" style="height: 150px; opacity: 0.5;" class="mb-4">
                            <h4 class="text-gray-500 font-weight-bold">Tidak ada pekerjaan aktif.</h4>
                            <p class="text-gray-600">Silakan ambil tugas baru dari antrian di samping.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            
            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-list-ol mr-2"></i>Antrian Tugas (Queue)</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($incomingJobs as $job)
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-bottom: 1px solid #334155;">
                                <div>
                                    <div class="font-weight-bold text-white">{{ $job->vehicle->plate_number }}</div>
                                    <div class="small text-gray-500">{{ $job->vehicle->brand }} | {{ $job->created_at->diffForHumans() }}</div>
                                </div>
                                <a href="{{ route('mechanic.jobs.show', $job->id) }}" class="btn btn-sm btn-outline-info">
                                    Ambil <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-gray-500 py-4" style="background-color: transparent;">
                                Antrian kosong. Santai dulu! ☕
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card shadow mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header py-3" style="background-color: #1e293b; border-bottom: 1px solid #334155;">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-chart-bar mr-2"></i>Performa 7 Hari Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
<script>
    // Chart Config Dark Mode
    Chart.defaults.global.defaultFontFamily = 'Nunito';
    Chart.defaults.global.defaultFontColor = '#858796';

    var ctx = document.getElementById("performanceChart");
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: "Mobil Selesai",
                backgroundColor: "#1cc88a",
                hoverBackgroundColor: "#17a673",
                borderColor: "#1cc88a",
                data: @json($chartValues),
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
            scales: {
                xAxes: [{ gridLines: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } }],
                yAxes: [{ ticks: { min: 0, maxTicksLimit: 5, padding: 10 }, gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } }],
            },
            legend: { display: false },
            tooltips: { backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", borderColor: '#dddfeb', borderWidth: 1, xPadding: 15, yPadding: 15, displayColors: false, caretPadding: 10 }
        }
    });
</script>
@endpush