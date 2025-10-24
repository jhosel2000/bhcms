<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if($emailType === 'booking') Appointment Confirmation @else Appointment Reminder @endif - Barangay Health Center</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333333; margin: 0; padding: 0; background-color: #f8f9fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e0e0e0; }
        .header { background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); color: white; padding: 40px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 28px; font-weight: 300; letter-spacing: 1px; }
        .header .subtitle { font-size: 16px; opacity: 0.9; margin-top: 10px; }
        .content { padding: 40px 30px; }
        .greeting { font-size: 18px; margin-bottom: 25px; color: #2c3e50; }
        .appointment-details { background-color: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 25px; margin: 25px 0; }
        .appointment-details h3 { margin-top: 0; color: #2c3e50; font-size: 20px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .detail-row { display: table; width: 100%; margin-bottom: 12px; }
        .detail-label { display: table-cell; width: 140px; font-weight: 600; color: #495057; vertical-align: top; }
        .detail-value { display: table-cell; color: #212529; }
        .status-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; }
        .status-confirmed { background-color: #28a745; color: white; }
        .status-scheduled { background-color: #ffc107; color: #212529; }
        .status-completed { background-color: #17a2b8; color: white; }
        .status-cancelled { background-color: #dc3545; color: white; }
        .important-notice { background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 20px; margin: 25px 0; }
        .important-notice h4 { margin-top: 0; color: #856404; font-size: 16px; }
        .important-notice ul { margin: 10px 0 0 0; padding-left: 20px; }
        .important-notice li { margin-bottom: 5px; }
        .contact-section { background-color: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center; }
        .contact-section h4 { margin-top: 0; color: #1976d2; }
        .contact-info { margin: 15px 0; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #3498db; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 10px 5px; }
        .btn:hover { background-color: #2980b9; }
        .btn-secondary { background-color: #95a5a6; }
        .btn-secondary:hover { background-color: #7f8c8d; }
        .footer { background-color: #2c3e50; color: white; padding: 30px; text-align: center; }
        .footer h5 { margin: 0 0 15px 0; font-size: 16px; }
        .footer p { margin: 5px 0; font-size: 14px; opacity: 0.8; }
        .disclaimer { font-size: 12px; opacity: 0.7; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                @if($emailType === 'booking')
                    Appointment Confirmation
                @else
                    Appointment Reminder
                @endif
            </h1>
            <div class="subtitle">Barangay Health Center Monitoring & Scheduling System</div>
        </div>

        <div class="content">
            @if($recipientType === 'patient')
                <p class="greeting">Dear {{ $appointment->patient->full_name }},</p>

                @if($emailType === 'booking')
                    <p>We are pleased to confirm your appointment booking. Please find the details below:</p>
                @else
                    <p>This is a reminder of your upcoming appointment. Please find the details below:</p>
                @endif
            @else
                @php
                    $provider = null;
                    $title = '';
                    if ($recipientType === 'doctor' && $appointment->doctor) {
                        $provider = $appointment->doctor;
                        $title = 'Dr. ';
                    } elseif ($recipientType === 'midwife' && $appointment->midwife) {
                        $provider = $appointment->midwife;
                        $title = '';
                    } elseif ($recipientType === 'bhw' && $appointment->bhw) {
                        $provider = $appointment->bhw;
                        $title = '';
                    }
                @endphp

                @if($provider)
                    <p class="greeting">Dear {{ $title }}{{ $provider->user->name }},</p>

                    @if($emailType === 'booking')
                        <p>A new appointment has been scheduled for your patient. Please find the details below:</p>
                    @else
                        <p>This is a reminder of your upcoming appointment. Please find the details below:</p>
                    @endif
                @endif
            @endif

            <div class="appointment-details">
                <h3>Appointment Information</h3>
                <div class="detail-row">
                    <div class="detail-label">Patient:</div>
                    <div class="detail-value">{{ $appointment->patient->full_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date:</div>
                    <div class="detail-value">{{ $appointment->appointment_date->format('l, F j, Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Time:</div>
                    <div class="detail-value">{{ $appointment->appointment_time->format('g:i A') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Service Type:</div>
                    <div class="detail-value">{{ $appointment->reason }}</div>
                </div>

                @if($appointment->doctor)
                    <div class="detail-row">
                        <div class="detail-label">Healthcare Provider:</div>
                        <div class="detail-value">Dr. {{ $appointment->doctor->user->name }}</div>
                    </div>
                @elseif($appointment->midwife)
                    <div class="detail-row">
                        <div class="detail-label">Healthcare Provider:</div>
                        <div class="detail-value">{{ $appointment->midwife->user->name }} (Midwife)</div>
                    </div>
                @elseif($appointment->bhw)
                    <div class="detail-row">
                        <div class="detail-label">Healthcare Provider:</div>
                        <div class="detail-value">{{ $appointment->bhw->user->name }} (BHW)</div>
                    </div>
                @endif

                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value">
                        <span class="status-badge status-{{ $appointment->status }}">{{ ucfirst($appointment->status) }}</span>
                    </div>
                </div>
            </div>

            @if($recipientType === 'patient')
                <div class="important-notice">
                    <h4>Important Instructions</h4>
                    <ul>
                        <li>Please arrive 15 minutes prior to your scheduled appointment time</li>
                        <li>Bring your valid ID and any relevant medical records</li>
                        <li>Wear comfortable clothing suitable for medical examination</li>
                        <li>If you need to reschedule, please contact us at least 24 hours in advance</li>
                        <li>In case of emergency or inability to attend, please notify us immediately</li>
                    </ul>
                </div>

                <div class="contact-section">
                    <h4>Questions or Need to Make Changes?</h4>
                    <div class="contact-info">
                        <strong>Phone:</strong> (02) 123-4567<br>
                        <strong>Email:</strong> appointments@barangayhealthcenter.gov.ph<br>
                        <strong>Address:</strong> Barangay Health Center, [Barangay Name], [City/Municipality]
                    </div>
                    <a href="tel:+6321234567" class="btn">Call Us</a>
                    <a href="mailto:appointments@barangayhealthcenter.gov.ph" class="btn btn-secondary">Email Us</a>
                </div>

                <p>We appreciate your trust in our healthcare services and look forward to providing you with quality care.</p>
            @else
                <div class="important-notice">
                    <h4>Preparation Notes</h4>
                    <p>Please review the patient's medical records and prepare accordingly for the appointment. Ensure all necessary equipment and documentation are ready.</p>
                </div>
            @endif

            <p>Should you have any questions, please do not hesitate to contact our administrative office.</p>

            <p>Best regards,<br>
            <strong>Barangay Health Center Monitoring & Scheduling System </strong></p>
        </div>

        <div class="footer">
            <h5>Barangay Health Center</h5>
            <p>Committed to providing accessible and quality healthcare services to our community</p>
            <p>Operating Hours: Monday - Friday, 8:00 AM - 5:00 PM | Saturday, 8:00 AM - 12:00 PM</p>
            <div class="disclaimer">
                This is an automated message. Please do not reply to this email. For inquiries, use the contact information provided above.<br>
                &copy; 2024 Barangay Health Center Monitoring & Scheduling System. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>
