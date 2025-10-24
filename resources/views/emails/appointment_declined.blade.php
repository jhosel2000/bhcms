<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Update - Barangay Health Center</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f5f7fa; }
        .email-wrapper { width: 100%; background-color: #f5f7fa; padding: 40px 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .header { background: linear-gradient(135deg, #f56565 0%, #c53030 100%); color: white; padding: 40px 30px; text-align: center; }
        .header-icon { width: 70px; height: 70px; background-color: rgba(255, 255, 255, 0.2); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px; }
        .header h1 { font-size: 28px; font-weight: 600; margin-bottom: 8px; }
        .header p { font-size: 14px; opacity: 0.9; }
        .content { padding: 40px 30px; }
        .alert-badge { background-color: #fed7d7; color: #742a2a; padding: 12px 20px; border-radius: 8px; text-align: center; margin-bottom: 25px; font-weight: 600; font-size: 16px; }
        .greeting { font-size: 18px; color: #1a202c; margin-bottom: 20px; font-weight: 500; }
        .message { font-size: 15px; color: #4a5568; margin-bottom: 25px; line-height: 1.7; }
        .appointment-card { background-color: #fff5f5; border: 2px solid #feb2b2; border-radius: 12px; padding: 30px; margin: 30px 0; }
        .appointment-card h2 { font-size: 20px; margin-bottom: 20px; font-weight: 600; color: #742a2a; }
        .detail-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #fed7d7; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 14px; color: #742a2a; font-weight: 500; }
        .detail-value { font-size: 15px; font-weight: 600; text-align: right; color: #1a202c; }
        .status-badge { display: inline-block; padding: 6px 16px; background-color: #f56565; color: white; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .reason-box { background-color: #fffaf0; border-left: 4px solid: #ed8936; padding: 20px; margin: 25px 0; border-radius: 8px; }
        .reason-box h3 { font-size: 16px; color: #7c2d12; margin-bottom: 12px; font-weight: 600; }
        .reason-box p { color: #9c4221; font-size: 14px; line-height: 1.6; }
        .info-box { background-color: #ebf8ff; border-left: 4px solid #4299e1; padding: 20px; margin: 25px 0; border-radius: 8px; }
        .info-box h3 { font-size: 16px; color: #2c5282; margin-bottom: 12px; font-weight: 600; }
        .info-box ul { list-style: none; padding: 0; }
        .info-box li { padding: 8px 0; color: #2c5282; font-size: 14px; padding-left: 24px; position: relative; }
        .info-box li:before { content: "→"; position: absolute; left: 0; color: #4299e1; font-weight: bold; }
        .button-container { text-align: center; margin: 30px 0; }
        .button { display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px; box-shadow: 0 4px 6px rgba(66, 153, 225, 0.3); }
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
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                </div>
                <h1>Appointment Update</h1>
                <p>Regarding your appointment request</p>
            </div>

            <div class="content">
                <div class="alert-badge">
                    ⚠ Your appointment request could not be approved
                </div>

                <p class="greeting">Dear {{ $appointment->patient->full_name }},</p>
                
                <p class="message">
                    We regret to inform you that your appointment request for {{ $appointment->appointment_date->format('F j, Y') }} 
                    at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }} could not be approved at this time.
                </p>

                <div class="appointment-card">
                    <h2>📅 Appointment Details</h2>
                    <div class="detail-row">
                        <span class="detail-label">Patient Name</span>
                        <span class="detail-value">{{ $appointment->patient->full_name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Requested Date</span>
                        <span class="detail-value">{{ $appointment->appointment_date->format('l, F j, Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Requested Time</span>
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
                        <span class="detail-value"><span class="status-badge">Declined</span></span>
                    </div>
                </div>

                @if($appointment->declined_reason)
                <div class="reason-box">
                    <h3>📝 Reason</h3>
                    <p>{{ $appointment->declined_reason }}</p>
                </div>
                @endif

                <div class="info-box">
                    <h3>🔄 Next Steps</h3>
                    <ul>
                        <li>You can book a new appointment with a different date or time</li>
                        <li>Contact us directly to discuss alternative scheduling options</li>
                        <li>Our staff can help you find a suitable appointment slot</li>
                        <li>We apologize for any inconvenience this may cause</li>
                    </ul>
                </div>

                <div class="button-container">
                    <a href="{{ url('/patient/appointments/create') }}" class="button">Book New Appointment</a>
                </div>

                <div class="contact-box">
                    <h3>Need Assistance?</h3>
                    <div class="contact-info">
                        <strong>Phone:</strong> (02) 123-4567<br>
                        <strong>Email:</strong> appointments@barangayhealthcenter.gov.ph<br>
                        <strong>Hours:</strong> Monday - Friday, 8:00 AM - 5:00 PM
                    </div>
                </div>

                <p class="message">
                    We value your health and wellbeing. Please don't hesitate to reach out if you have any questions or concerns.
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
