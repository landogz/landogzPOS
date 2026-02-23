@php
    $loginUrl = url('/super-admin/login');
    $brandColor = '#4f46e5';
    $brandColorLight = '#eef2ff';
    $digits = str_split($code);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login verification – Landogz POS</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f1f5f9;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 440px;">
                    <!-- Card -->
                    <tr>
                        <td style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -2px rgba(0, 0, 0, 0.06); overflow: hidden;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <!-- Brand accent bar -->
                                <tr>
                                    <td style="height: 4px; background-color: {{ $brandColor }}; font-size: 0; line-height: 0;">&nbsp;</td>
                                </tr>
                                <!-- Header with branding -->
                                <tr>
                                    <td style="padding: 24px 32px; border-bottom: 1px solid #e2e8f0;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                @if(config('app.url') && file_exists(public_path('images/logo.png')))
                                                <td style="vertical-align: middle; padding-right: 12px;">
                                                    <img src="{{ config('app.url') }}/images/logo.png" alt="" width="40" height="40" style="display: block; width: 40px; height: 40px; border-radius: 8px;" />
                                                </td>
                                                @endif
                                                <td style="vertical-align: middle;">
                                                    <span style="font-size: 20px; font-weight: 700; color: {{ $brandColor }}; letter-spacing: -0.02em;">Landogz POS</span>
                                                    <br>
                                                    <span style="font-size: 13px; color: #64748b;">Login verification</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Body -->
                                <tr>
                                    <td style="padding: 32px;">
                                        <p style="margin: 0 0 20px 0; font-size: 15px; line-height: 1.6; color: #475569;">Use the code below to sign in to your Super Admin account.</p>
                                        <p style="margin: 0 0 12px 0; font-size: 13px; color: #64748b;">Your verification code</p>
                                        <!-- OTP: individual digit boxes, brand-tinted -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 28px;">
                                            <tr>
                                                @foreach($digits as $digit)
                                                <td style="background-color: {{ $brandColorLight }}; border: 2px solid {{ $brandColor }}; border-radius: 8px; width: 44px; height: 52px; text-align: center; vertical-align: middle; padding: 0 4px;">
                                                    <span style="font-size: 26px; font-weight: 800; letter-spacing: 0.05em; color: {{ $brandColor }}; font-variant-numeric: tabular-nums;">{{ $digit }}</span>
                                                </td>
                                                @if(!$loop->last)
                                                <td style="width: 6px;"></td>
                                                @endif
                                                @endforeach
                                            </tr>
                                        </table>
                                        <p style="margin: 0 0 24px 0; font-size: 13px; color: #64748b;">This code expires in <strong style="color: #475569;">{{ $expiresInMinutes }} minutes</strong>. Do not share it with anyone.</p>
                                        <!-- CTA: Open login page -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="border-radius: 8px; background-color: {{ $brandColor }};">
                                                    <a href="{{ $loginUrl }}" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 14px 28px; font-size: 15px; font-weight: 600; color: #ffffff; text-decoration: none;">Open login page</a>
                                                </td>
                                            </tr>
                                        </table>
                                        <p style="margin: 20px 0 0 0; font-size: 11px; color: #94a3b8; line-height: 1.5;">If you didn’t request this code, you can safely ignore this email or contact support if you have concerns.</p>
                                    </td>
                                </tr>
                                <!-- Footer -->
                                <tr>
                                    <td style="padding: 20px 32px 24px 32px; border-top: 1px solid #e2e8f0; background-color: #f8fafc;">
                                        <p style="margin: 0; font-size: 12px; color: #94a3b8;">&copy; {{ date('Y') }} Landogz POS. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 24px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">Secure POS for food &amp; retail in the Philippines</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
