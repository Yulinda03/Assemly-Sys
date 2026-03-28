@extends('layouts.app', ['title' => 'Scanning Station'])

@section('content')
    <div class="row justify-content-center">
        <!-- Scanner Panel -->
        <div class="col-md-5">
            <div class="card card-custom p-4 text-center">
                <h4 class="mb-4">🔍 Scan Barcode</h4>

                <div class="mb-3 text-start">
                    <label for="lineSelect" class="form-label">Production Line</label>
                    <select class="form-select form-select-lg" id="lineSelect">
                        @for ($i = 1; $i <= 15; $i++)
                            <option value="Line {{ $i }}" {{ session('selected_line') == "Line $i" ? 'selected' : '' }}>Station:
                                Line {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mb-3 text-start">
                    <label for="scannerName" class="form-label">Nama Operator / Scanner</label>
                    <input type="text" class="form-control form-control-lg" id="scannerName"
                        placeholder="Masukkan Nama Anda...">
                </div>

                <div class="mb-3">
                    <input type="text" id="barcodeInput" class="form-control form-control-lg text-center"
                        placeholder="Scan here..." style="height: 60px; font-size: 1.5rem;" autofocus>
                    <small class="text-muted">Arahkan scanner ke barcode komponen</small>
                </div>

                <!-- Feedback Area -->
                <div id="scanFeedback" class="alert d-none" role="alert"></div>

            </div>

            <div class="card card-custom p-3 mt-4">
                <h5>ℹ️ Komponen Prefix Rules</h5>
                <div class="row" style="font-size: 0.9rem;">
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li><code>HES</code> : Heatsink</li>
                            <li><code>WIF</code> : Wifi</li>
                            <li><code>RAM</code> : RAM</li>
                            <li><code>SSD</code> : SSD</li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="list-unstyled">
                            <li><code>UNT</code> : Unit</li>
                            <li><code>SPK</code> : Speaker</li>
                            <li><code>MOB</code> : Mobo</li>
                            <li><code>BAT</code> : Baterai</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Log -->
        <div class="col-md-7">
            <div class="card card-custom">
                <div class="card-header bg-white">
                    <h5 class="mb-0">📜 Scan History (Today)</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Time</th>
                                    <th>Line</th>
                                    <th>Scanner</th>
                                    <th>Component</th>
                                    <th>Barcode</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody id="scanHistoryBody">
                                @foreach ($recentScans as $scan)
                                    <tr>
                                        <td>{{ $scan->created_at->format('H:i:s') }}</td>
                                        <td>{{ $scan->line }}</td>
                                        <td>{{ $scan->scanner_name }}</td>
                                        <td><span class="badge bg-primary">{{ strtoupper($scan->component) }}</span></td>
                                        <td>{{ $scan->barcode }}</td>
                                        <td>✅ OK</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            const barcodeInput = $('#barcodeInput');
            const feedback = $('#scanFeedback');
            const historyBody = $('#scanHistoryBody');
            const lineSelect = $('#lineSelect');

            // Function to load scan history
            function loadScanHistory(line) {
                historyBody.html('<tr><td colspan="6" class="text-center">Loading...</td></tr>');

                $.ajax({
                    url: "{{ url('/api/scan/history') }}",
                    method: "GET",
                    data: { line: line },
                    success: function (response) {
                        historyBody.empty();
                        if (response.data.length === 0) {
                            historyBody.html('<tr><td colspan="6" class="text-center text-muted">Belum ada scan untuk ' + line + ' hari ini.</td></tr>');
                            return;
                        }

                        response.data.forEach(scan => {
                            let newRow = `
                                    <tr>
                                        <td>${new Date(scan.created_at).toLocaleTimeString()}</td>
                                        <td>${scan.line}</td>
                                        <td>${scan.scanner_name}</td>
                                        <td><span class="badge bg-primary">${scan.component.toUpperCase()}</span></td>
                                        <td>${scan.barcode}</td>
                                        <td>✅ OK</td>
                                    </tr>
                                `;
                            historyBody.append(newRow);
                        });
                    },
                    error: function () {
                        historyBody.html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat history.</td></tr>');
                    }
                });
            }

            // Load history on page load
            loadScanHistory(lineSelect.val());

            // Load history on line change
            lineSelect.on('change', function () {
                loadScanHistory($(this).val());
                barcodeInput.focus();
            });

            // Refocus input unless clicking on another form element/interactive item
            $(document).click(function (e) {
                if (!$(e.target).closest('select, input, textarea, button, a').length) {
                    barcodeInput.focus();
                }
            });

            barcodeInput.on('keypress', function (e) {
                if (e.which == 13) { // Enter Key
                    let barcode = $(this).val();
                    let line = lineSelect.val();
                    let scannerName = $('#scannerName').val();

                    if (!scannerName) {
                        alert("Harap isi Nama Operator terlebih dahulu!");
                        $('#scannerName').focus();
                        return;
                    }

                    if (barcode.length < 3) return;

                    // Send AJAX
                    $.ajax({
                        url: "{{ url('/api/scan') }}", // Or named route
                        method: "POST",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            barcode: barcode,
                            line: line,
                            scanner_name: scannerName
                        },
                        success: function (response) {
                            barcodeInput.val(''); // Clear input

                            // Show Success Feedback
                            feedback.removeClass('d-none alert-danger').addClass('alert-success').text(response.message).fadeIn();

                            // Add to table ONLY if the line matches the currently selected line
                            if (response.data.line === lineSelect.val()) {
                                // Remove "empty" message if it exists
                                if (historyBody.find('td[colspan="6"]').length > 0) {
                                    historyBody.empty();
                                }

                                let newRow = `
                                            <tr>
                                                <td>${new Date().toLocaleTimeString()}</td>
                                                <td>${line}</td>
                                                <td>${response.data.scanner_name}</td>
                                                <td><span class="badge bg-primary">${response.data.component.toUpperCase()}</span></td>
                                                <td>${response.data.barcode}</td>
                                                <td>✅ OK</td>
                                            </tr>
                                        `;
                                historyBody.prepend(newRow);
                            }

                            // Beep Sound (Optional)
                            // new Audio('/sounds/success.mp3').play();
                        },
                        error: function (xhr) {
                            barcodeInput.val(''); // Clear input
                            let msg = xhr.responseJSON ? xhr.responseJSON.message : "Error Unknown";

                            // Show Error Feedback
                            feedback.removeClass('d-none alert-success').addClass('alert-danger').text(msg).fadeIn();

                            // Error Sound (Optional)
                            // new Audio('/sounds/error.mp3').play();
                        }
                    });

                    // Save selected line to session logic if needed
                }
            });
        });
    </script>
@endsection