@extends('layouts.app', ['title' => 'Dashboard Overview'])

@section('content')
    <div class="row">
        <!-- Summary Cards -->
        @foreach ($stats as $component => $count)
            <div class="col-md-3 mb-4">
                <div class="card card-custom h-100">
                    <div class="card-body">
                        <h6 class="text-muted text-uppercase mb-2">{{ $component }}</h6>
                        <h2 class="mb-0 fw-bold">{{ number_format($count) }}</h2>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> Total Scanned</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📊 Performance per Line</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Line Name</th>
                                <th>Total Output</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lineStats as $stat)
                                <tr>
                                    <td>{{ $stat->line }}</td>
                                    <td class="fw-bold">{{ $stat->total }}</td>
                                    <td>
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: {{ min($stat->total, 100) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-header bg-white">
                    <h5 class="mb-0">🕒 Recent Activity</h5>
                </div>
                <div class="list-group list-group-flush">
                    @foreach ($recentScans as $rec)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $rec->component }}</h6>
                                <small>{{ $rec->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1 small text-muted">{{ $rec->barcode }}</p>
                            <small>{{ $rec->line }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection