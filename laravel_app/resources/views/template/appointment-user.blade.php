<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectPrefix }} - Received</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="width:100%;max-width:640px;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#0e7490 0%,#2563eb 100%);padding:26px 28px;color:#ffffff;">
                            <p style="margin:0;font-size:12px;letter-spacing:1.3px;text-transform:uppercase;opacity:.9;">Request Confirmation</p>
                            <h1 style="margin:8px 0 0;font-size:24px;line-height:1.3;font-weight:700;">{{ config('app.name') }}</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:28px;">
                            <h2 style="margin:0 0 10px;font-size:20px;color:#0f172a;">Dear {{ $appointment->name ?: 'Valued Client' }},</h2>
                            <p style="margin:0 0 20px;font-size:15px;line-height:1.75;color:#334155;">
                                Thank you for contacting us. Your request has been received successfully and our team will connect with you shortly.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e5e7eb;border-radius:10px;background:#f8fafc;">
                                <tr>
                                    <td style="padding:16px 18px;">
                                        <h3 style="margin:0 0 14px;font-size:14px;text-transform:uppercase;letter-spacing:.6px;color:#0e7490;">Request Details</h3>
                                        <p style="margin:0 0 8px;font-size:14px;"><strong>Request Type:</strong> {{ ucfirst($appointment->type ?? 'N/A') }}</p>
                                        <p style="margin:0 0 8px;font-size:14px;"><strong>Service:</strong> {{ $serviceName ?: 'N/A' }}</p>
                                        <p style="margin:0 0 8px;font-size:14px;"><strong>Phone:</strong> {{ $appointment->phone ?: 'N/A' }}</p>
                                        @if(!empty($appointment->appointment_date))
                                            <p style="margin:0 0 8px;font-size:14px;"><strong>Preferred Date:</strong> {{ \Illuminate\Support\Carbon::parse($appointment->appointment_date)->format('d M, Y') }}</p>
                                        @endif
                                        @if(!empty($appointment->message))
                                            <p style="margin:0;font-size:14px;"><strong>Message:</strong> {!! nl2br(e($appointment->message)) !!}</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:22px 0 0;font-size:14px;color:#475569;line-height:1.7;">
                                We appreciate your trust in us and look forward to assisting you.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 28px;background:#0f172a;color:#cbd5e1;font-size:12px;text-align:center;line-height:1.6;">
                            <div>This is an automated confirmation email from {{ config('app.name') }}.</div>
                            <div style="margin-top:4px;">Please do not reply to this email.</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>