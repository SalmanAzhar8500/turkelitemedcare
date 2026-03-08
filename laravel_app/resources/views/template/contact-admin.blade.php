<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
</head>
<body style="margin:0;padding:0;background:#eef2ff;font-family:Arial,Helvetica,sans-serif;color:#1e293b;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef2ff;padding:28px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="680" cellpadding="0" cellspacing="0" style="width:100%;max-width:680px;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #dbeafe;">
                <tr>
                    <td style="background:linear-gradient(135deg,#1d4ed8 0%,#7c3aed 100%);padding:24px 28px;color:#ffffff;">
                        <p style="margin:0;font-size:12px;letter-spacing:1.4px;text-transform:uppercase;opacity:.9;">New Contact Lead</p>
                        <h1 style="margin:8px 0 0;font-size:24px;line-height:1.3;font-weight:700;">Contact Form Submission</h1>
                    </td>
                </tr>

                <tr>
                    <td style="padding:26px 28px 30px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #dbeafe;border-radius:10px;background:#f8fafc;">
                            <tr>
                                <td style="padding:18px 20px;">
                                    <p style="margin:0 0 10px;font-size:14px;"><strong>Name:</strong> {{ $submission->name ?: 'N/A' }}</p>
                                    <p style="margin:0 0 10px;font-size:14px;"><strong>Email:</strong> {{ $submission->email ?: 'N/A' }}</p>
                                    <p style="margin:0 0 10px;font-size:14px;"><strong>Phone:</strong> {{ $submission->phone ?: 'N/A' }}</p>
                                    <p style="margin:0 0 10px;font-size:14px;"><strong>Message:</strong> {!! nl2br(e($submission->message ?? 'N/A')) !!}</p>
                                    <p style="margin:0 0 10px;font-size:14px;"><strong>IP Address:</strong> {{ $submission->ip_address ?: 'N/A' }}</p>
                                    <p style="margin:0;font-size:14px;"><strong>Submitted:</strong> {{ $submission->created_at?->format('d M, Y h:i A') }}</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:18px 28px;background:#0f172a;color:#cbd5e1;font-size:12px;text-align:center;line-height:1.6;">
                        <div>{{ config('app.name') }} · Admin Contact Alerts</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
