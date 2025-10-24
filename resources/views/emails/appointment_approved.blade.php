<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Approved - Barangay Health Center</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f7fa; }
        .email-wrapper { width: 100%; background-color: #f5f7fa; padding: 40px 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .header { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 40px 30px; text-align: center; }
        .header-icon { width: 70px; height: 70px; background-color: rgba(255, 255, 255, 0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px; }
        .header h1 { font-size: 28px; font-weight: 600; margin-bottom: 8px; }
        .header p { font-size: 14px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .success-badge { background-color: #c6f6d5; color: #22543d; padding: 12px 20px; border-radius: 8px; text-align: center; margin-bottom: 25px; font-weight: 600; font-size: 16px; }
        .greeting { font-size: 18px; color: #1a202c; margin-bottom: 20px; font-weight: 500; }
        .message { font-size: 15px; color: #4a5568; margin-bottom: 25px; line-height: 1.7; }
        .appointment-card { background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); border-radius: 12px; padding: 30px; margin: 30px 0; color: white; }
        .appointment-card h2 { font-size: 20px; margin-bottom: 20px; font-weight: 600; }
        .detail-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.2); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 14px; opacity: 0.9; }
        .detail-value { font-size: 15px; font-weight: 600; text-align: right; }
        .status-badge { display: inline-block; padding: 6px 16px; background-color: rgba(255, 255, 255, 0.3); border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .info-box { background-color: #f0fff4; border-left: 4px solid #48bb78; padding: 20px; margin: 25px 0; border-radius: 8px; }
        .info-box h3 { font-size: 16px; color: #22543d; margin-bottom: 12px; font-weight: 600; }
        .info-box ul { list-style: none; padding: 0; }
        .info-box li { padding: 8px 0; color: #2f855a; font-size: 14px; padding-left: 24px; position: relative; }
        .info-box li:before { content: "✓"; position: absolute; left: 0; color: #48bb78; font-weight: bold; }
        .button-container { text-align: center; margin: 30px 0; }
        .button { display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; box-shadow: 0 4px 6px rgba(72, 187, 120, 0.3); }
        .contact-box { background-color: #f7fafc; border-radius: 8px; padding: 25px; margin: 25px 0; text-align: center; }
        .contact-box h3 { font-size: 16px; color: #2d3748; margin-bottom: 15px; font-weight: 600; }
        .contact-info { font-size: 14px; color: #4a5568; line-height: 1.8; }
        .contact-info strong { color: #2d3748; }
        .footer { background-color: #2d3748; color: #cbd5e0; padding: 30px; text-align: center; }
        .footer h4 { font-size: 16px; margin-bottom: 10px; color: white; }
        .footer p { font-size: 13px; margin: 5px 0; }
        .footer-divider { height: 1px; background-color: #4a5568; margin: 20px 0; }
        .disclaimer { font-size: 11px; color: #a0aec0; margin-top: 15px; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <div class="header-icon">
                    <svg width="40" height="40" fill="white" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                </div>
                <h1>Appointment Approved!</h1>
                <p>Your appointment has been confirmed</p>
            </div>

            <div class="content">
                <div class="success-badge">
                    ✓ Your appointment request has been approved
                </div>

                <p class="greeting">Dear {{ $appointment->patient->full_name }},</p>
                
                <p class="message">
                    Great news! Dr. {{ $appointment->doctor->user->name }} has <strong>approved</strong> your appointment request. 
                    Your appointment is now confirmed and scheduled.
                </p>

                <div class="appointment-card">
                    <h2>📅 Confirmed Appointment Details</h2>
                    <div class="detail-row">
                        <span class="detail-label">Patient Name</span>
                        <span class="detail-value">{{ $appointment->patient->full_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date</span>
                        <span class="detail-value">{{ $appointment->appointment_date->format('l, F j, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Time</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Reason for Visit</span>
                        <span class="detail-value">{{ $appointment->reason }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Doctor</span>
                        <span class="detail-value">Dr. {{ $appointment->doctor->user->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status</span>
                        <span class="detail-value"><span class="status-badge">Approved</span></span>
                    </div>
                </div>

                <div class="info-box">
                    <h3>📋 Important Reminders</h3>
                    <ul>
                        <li>Please arrive 15 minutes before your scheduled time</li>
                        <li>Bring a valid government-issued ID</li>
                        <li>Bring any relevant medical records or test results</li>
                        <li>Wear comfortable clothing suitable for examination</li>
                        <li>If you need to cancel or reschedule, notify us 24 hours in advance</li>
                    </ul>
                </div>

                <div class="button-container">
                    <a href="{{ url('/patient/appointments') }}" class="button">View My Appointments</a>
                </div>

                <div class="contact-box">
                    <h3>Need to Make Changes?</h3>
                    <div class="contact-info">
                        <strong>Phone:</strong> (02) 123-4567<br>
                        <strong>Email:</strong> appointments@barangayhealthcenter.gov.ph<br>
                        <strong>Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM
                    </div>
                </div>

                <p class="message">
                    We look forward to seeing you on {{ $appointment->appointment_date->format('F j, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}.
                </p>

                <p style="margin-top: 20px; font-size: 14px; color: #718096;">
                    Best regards,<br>
                    <strong>Barangay Health Center Team</strong>
                </p>
            </div>

            <div class="footer">
                <h4>Barangay Health Center</h4>
                <p>Committed to Quality Healthcare for Our Community</p>
                <div class="footer-divider"></div>
                <p class="disclaimer">
                    This is an automated email notification. Please do not reply directly to this email.
                    For inquiries, please use the contact information provided above.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
