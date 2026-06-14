<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:40px 20px;">
        <tr>
            <td align="center">
                <table width="520" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.06);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:32px 40px;text-align:center;">
                            <div style="font-size:36px;margin-bottom:8px;">&#128274;</div>
                            <h1 style="color:#ffffff;font-size:22px;font-weight:700;margin:0;">Verifikasi Akun Anda</h1>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding:36px 40px;">
                            <p style="color:#334155;font-size:15px;line-height:1.7;margin:0 0 16px;">Halo,</p>
                            <p style="color:#334155;font-size:15px;line-height:1.7;margin:0 0 24px;">Terima kasih telah mendaftar di <strong>Om Abonk</strong>! Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini:</p>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding:8px 0 28px;">
                                        <a href="<?= $verifyUrl ?>" style="display:inline-block;padding:14px 48px;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:#ffffff;font-size:16px;font-weight:700;text-decoration:none;border-radius:8px;letter-spacing:.3px;">
                                            &#9993; Verifikasi Email Saya
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#64748b;font-size:13px;line-height:1.6;margin:0 0 8px;">Atau copy link ini ke browser Anda:</p>
                            <p style="color:#4f46e5;font-size:12px;line-height:1.5;margin:0 0 24px;word-break:break-all;">
                                <a href="<?= $verifyUrl ?>" style="color:#4f46e5;"><?= $verifyUrl ?></a>
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="border-top:1px solid #e2e8f0;">
                                <tr>
                                    <td style="padding:20px 0 0;">
                                        <p style="color:#94a3b8;font-size:12px;line-height:1.6;margin:0;">
                                            Link ini akan kedaluwarsa dalam 24 jam. Jika Anda tidak mendaftar di Om Abonk, abaikan email ini.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background:#f8fafc;padding:20px 40px;text-align:center;border-top:1px solid #e2e8f0;">
                            <p style="color:#94a3b8;font-size:12px;margin:0;">
                                &copy; <?= $year ?> Om Abonk LMS. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
