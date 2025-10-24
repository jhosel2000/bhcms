<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - {{ $prescription->patient->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            padding: 40px;
            background: #f5f5f5;
        }

        .prescription-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 2px solid #10b981;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #10b981;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #10b981;
            font-size: 32px;
            margin-bottom: 5px;
        }

        .header .subtitle {
            color: #666;
            font-size: 14px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .info-block {
            flex: 1;
        }

        .info-block h3 {
            color: #10b981;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .info-block p {
            margin: 5px 0;
            font-size: 14px;
        }

        .info-block strong {
            color: #111;
        }

        .prescription-details {
            margin: 30px 0;
            padding: 25px;
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            border-radius: 8px;
        }

        .prescription-details h2 {
            color: #10b981;
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .prescription-details h2::before {
            content: "℞";
            font-size: 32px;
            margin-right: 10px;
        }

        .detail-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #d1fae5;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #059669;
            min-width: 150px;
        }

        .detail-value {
            color: #111;
            flex: 1;
        }

        .instructions {
            margin: 30px 0;
            padding: 20px;
            background: #fff7ed;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
        }

        .instructions h3 {
            color: #f59e0b;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .instructions p {
            color: #78350f;
            line-height: 1.8;
        }

        .signature-section {
            margin-top: 50px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
        }

        .signature-block {
            text-align: center;
        }

        .signature-line {
            width: 250px;
            border-top: 2px solid #333;
            margin: 50px auto 10px;
        }

        .signature-block p {
            font-size: 14px;
            color: #666;
        }

        .signature-block strong {
            color: #111;
            font-size: 16px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-completed {
            background: #e5e7eb;
            color: #374151;
        }

        .status-pending {
            background: #fed7aa;
            color: #92400e;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .prescription-container {
                border: none;
                box-shadow: none;
                padding: 20px;
            }

            .no-print {
                display: none;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .print-button:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Print Prescription</button>

    <div class="prescription-container">
        {{-- Header --}}
        <div class="header">
            <h1>℞ PRESCRIPTION</h1>
            <p class="subtitle">Barangay Health Center Management System</p>
        </div>

        {{-- Patient and Doctor Information --}}
        <div class="info-section">
            <div class="info-block">
                <h3>Patient Information</h3>
                <p><strong>Name:</strong> {{ $prescription->patient->full_name }}</p>
                <p><strong>Patient ID:</strong> #{{ str_pad($prescription->patient->id, 6, '0', STR_PAD_LEFT) }}</p>
                @if($prescription->patient->date_of_birth)
                    <p><strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($prescription->patient->date_of_birth)->format('M d, Y') }}</p>
                @endif
            </div>

            <div class="info-block" style="text-align: right;">
                <h3>Prescription Details</h3>
                <p><strong>Prescription ID:</strong> #{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Date Issued:</strong> {{ $prescription->created_at->format('M d, Y') }}</p>
                <p>
                    <strong>Status:</strong> 
                    <span class="status-badge status-{{ $prescription->status === 'active' ? 'active' : ($prescription->status === 'completed' ? 'completed' : 'pending') }}">
                        {{ $prescription->status_label }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Prescription Details --}}
        <div class="prescription-details">
            <h2>Medication Details</h2>
            
            <div class="detail-row">
                <div class="detail-label">Medication Name:</div>
                <div class="detail-value"><strong>{{ $prescription->medication_name }}</strong></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Dosage:</div>
                <div class="detail-value">{{ $prescription->dosage }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Frequency:</div>
                <div class="detail-value">{{ $prescription->frequency }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Duration:</div>
                <div class="detail-value">{{ $prescription->duration }}</div>
            </div>
        </div>

        {{-- Instructions --}}
        @if($prescription->instructions)
            <div class="instructions">
                <h3>⚠️ Special Instructions</h3>
                <p>{{ $prescription->instructions }}</p>
            </div>
        @endif

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="signature-block">
                <div class="signature-line"></div>
                <p><strong>{{ $prescription->doctor->user->name ?? 'Doctor' }}</strong></p>
                <p>Prescribing Physician</p>
                <p>License No: {{ $prescription->doctor->license_number ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>This prescription is valid for 30 days from the date of issue.</p>
            <p>For any questions or concerns, please contact the health center.</p>
            <p style="margin-top: 10px; font-style: italic;">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>
    </div>

    <script>
        // Auto-print dialog on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>