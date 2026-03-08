<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectPrefix }} - Admin Notification</title>
</head>
<body style="margin:0;padding:0;background:#eef2ff;font-family:Arial,Helvetica,sans-serif;color:#1e293b;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef2ff;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="700" cellpadding="0" cellspacing="0" style="width:100%;max-width:700px;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #dbeafe;">
                    <tr>
                        <td style="background:linear-gradient(135deg,#1d4ed8 0%,#7c3aed 100%);padding:24px 28px;color:#ffffff;">
                            <p style="margin:0;font-size:12px;letter-spacing:1.4px;text-transform:uppercase;opacity:.9;">New Lead Alert</p>
                            <h1 style="margin:8px 0 0;font-size:24px;line-height:1.3;font-weight:700;">{{ $subjectPrefix }}</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:26px 28px 8px;">
                            <p style="margin:0;font-size:15px;line-height:1.8;color:#334155;">
                                A new request has been submitted. Please review the details below and follow up promptly.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:16px 28px 30px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #dbeafe;border-radius:10px;background:#f8fafc;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <h3 style="margin:0 0 14px;font-size:14px;text-transform:uppercase;letter-spacing:.6px;color:#1d4ed8;">Client Information</h3>
                                        <p style="margin:0 0 8px;font-size:14px;"><strong>Name:</strong> {{ $appointment->name ?: 'N/A' }}</p>
                                        <p style="margin:0 0 8px;font-size:14px;"><strong>Email:</strong> {{ $appointment->email ?: 'N/A' }}</p>
                                        <p style="margin:0 0 8px;font-size:14px;"><strong>Phone:</strong> {{ $appointment->phone ?: 'N/A' }}</p>
                                        <p style="margin:0 0 8px;font-size:14px;"><strong>Type:</strong> {{ ucfirst($appointment->type ?? 'N/A') }}</p>
                                        <p style="margin:0 0 8px;font-size:14px;"><strong>Service:</strong> {{ $serviceName ?: 'N/A' }}</p>
                                        @if(!empty($appointment->appointment_date))
                                            <p style="margin:0 0 8px;font-size:14px;"><strong>Preferred Date:</strong> {{ \Illuminate\Support\Carbon::parse($appointment->appointment_date)->format('d M, Y') }}</p>
                                        @endif
                                        @if(!empty($appointment->message))
                                            <p style="margin:0 0 8px;font-size:14px;"><strong>Message:</strong> {!! nl2br(e($appointment->message)) !!}</p>
                                        @endif
                                        @if(!empty($appointment->head_image))
                                            <p style="margin:0;font-size:14px;">
                                                <strong>Head Image:</strong>
                                                <a href="{{ asset('storage/' . ltrim((string) $appointment->head_image, '/')) }}" style="color:#2563eb;text-decoration:underline;" target="_blank">View Uploaded Image</a>
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 28px;background:#0f172a;color:#cbd5e1;font-size:12px;text-align:center;line-height:1.6;">
                            <div>{{ config('app.name') }} · Admin Notification Center</div>
                            <div style="margin-top:4px;">Generated on {{ now()->format('d M, Y h:i A') }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>