@extends('layouts.app', ['title' => 'Export Data'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">📥 Download Report (Excel)</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('export.download') }}" method="GET">
                        <div class="row g-3">
                            <!-- Line Filter -->
                            <div class="col-md-6">
                                <label class="form-label">Production Line</label>
                                <select name="line" class="form-select">
                                    <option value="all">Semua Line</option>
                                    @for ($i = 1; $i <= 15; $i++)
                                        <option value="Line {{ $i }}">Line {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Component Filter -->
                            <div class="col-md-6">
                                <label class="form-label">Component Type</label>
                                <select name="component" class="form-select">
                                    <option value="all">Semua Komponen</option>
                                    <option value="wifi">Wifi</option>
                                    <option value="ram">RAM</option>
                                    <option value="ssd">SSD</option>
                                    <option value="baterai">Baterai</option>
                                    <option value="unit">Unit</option>
                                    <option value="mobo">Mobo</option>
                                    <option value="hesting">Hesting</option>
                                    <option value="speaker">Speaker</option>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-md-12">
                                <label class="form-label">Tanggal Scan (Optional)</label>
                                <input type="date" name="date" class="form-control">
                                <small class="text-muted">Biarkan kosong untuk download semua tanggal.</small>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="bi bi-file-earmark-excel"></i> Download Excel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection