<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - BSI Cyber Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="margin:0;padding:0;background:#262b36;font-family:'Segoe UI',Arial,sans-serif;min-height:100vh;">
    <div style="padding:24px 0 0 24px;">
        <a href="{{ route('backend.login') }}"
            style="display:inline-block;color:#cfd3da;border:1px solid #454b58;border-radius:6px;padding:8px 16px;text-decoration:none;font-size:14px;">
            &larr; Kembali ke Login
        </a>
    </div>

    <div style="display:flex;justify-content:center;padding:60px 20px;">
        <div style="width:100%;max-width:600px;">
            <hr style="border:none;border-top:1px solid #3a4050;margin-bottom:40px;">

            <p style="text-align:center;color:#e5e7eb;font-size:18px;margin:0 0 32px;">
                Buat password baru untuk akun kamu.
            </p>

            <form method="POST" action="{{ route('backend.reset-password.post') }}">
                @csrf

                <div style="display:flex;border-radius:4px;overflow:hidden;margin-bottom:16px;">
                    <span
                        style="background:#f2541c;color:#fff;display:flex;align-items:center;padding:0 18px;font-size:18px;">🔒</span>
                    <input type="password" name="password" placeholder="Password baru"
                        style="flex:1;border:none;padding:16px;font-size:16px;background:#e8eaf0;color:#1b1f27;">
                </div>
                @error('password')
                <p style="color:#f87171;font-size:13px;margin:-8px 0 16px;">{{ $message }}</p>
                @enderror

                <div style="display:flex;border-radius:4px;overflow:hidden;margin-bottom:8px;">
                    <span
                        style="background:#f2541c;color:#fff;display:flex;align-items:center;padding:0 18px;font-size:18px;">🔒</span>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi password baru"
                        style="flex:1;border:none;padding:16px;font-size:16px;background:#e8eaf0;color:#1b1f27;">
                </div>

                <hr style="border:none;border-top:1px solid #3a4050;margin:24px 0;">

                <button type="submit"
                    style="background:#1e88e5;color:#fff;border:none;padding:12px 28px;border-radius:4px;font-size:15px;cursor:pointer;">
                    Simpan Password Baru
                </button>
            </form>
        </div>
    </div>
</body>

</html>