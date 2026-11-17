<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Kode OTP Reset Password</title>
</head>

<body style="margin:0; padding:0; background:#fff5ef; font-family: 'Segoe UI', Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fff5ef; padding:40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:14px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06);">
                    <tr>
                        <td style="background:linear-gradient(135deg, #ff7a3d 0%, #f2541c 55%, #d63c0e 100%); padding:28px 32px;">
                            <span style="color:#fff; font-size:20px; font-weight:800;">BSI Cyber Store</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 32px;">
                            <p style="font-size:16px; color:#26201c; margin:0 0 6px;">Halo, <b>{{ $nama }}</b>!</p>
                            <p style="font-size:14px; color:#8a8078; margin:0 0 26px; line-height:1.6;">
                                Kami menerima permintaan reset password untuk akun kamu. Gunakan kode OTP di bawah
                                ini untuk melanjutkan. Kode berlaku selama <b>10 menit</b>.
                            </p>

                            <div style="text-align:center; margin:28px 0;">
                                <span
                                    style="display:inline-block; background:#fff5ef; border:2px dashed #f2541c; border-radius:12px; padding:16px 34px; font-size:32px; font-weight:800; letter-spacing:8px; color:#f2541c;">
                                    {{ $otp }}
                                </span>
                            </div>

                            <p style="font-size:13px; color:#b4aaa1; margin:26px 0 0; line-height:1.6;">
                                Jika kamu tidak merasa meminta reset password, abaikan saja email ini. Jangan
                                bagikan kode ini kepada siapa pun, termasuk pihak yang mengaku dari BSI Cyber Store.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px; background:#faf6f2; text-align:center;">
                            <span style="font-size:12px; color:#b4aaa1;">&copy; {{ date('Y') }} BSI Cyber Store. Semua hak dilindungi.</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>