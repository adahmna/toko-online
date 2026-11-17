<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - BSI Cyber Store</title>
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

            <p style="text-align:center;color:#e5e7eb;font-size:18px;margin:0 0 8px;">
                Masukkan kode OTP yang sudah dikirim ke
            </p>
            <p style="text-align:center;color:#e5e7eb;font-size:18px;font-weight:600;margin:0 0 32px;">
                {{ $email }}
            </p>

            @if (session('success'))
            <p style="text-align:center;color:#4ade80;font-size:14px;margin-bottom:16px;">{{ session('success') }}</p>
            @endif

            <form method="POST" action="{{ route('backend.verify-otp.post') }}">
                @csrf
                <div style="display:flex;border-radius:4px;overflow:hidden;margin-bottom:8px;">
                    <span
                        style="background:#f2541c;color:#fff;display:flex;align-items:center;padding:0 18px;font-size:18px;">🔑</span>
                    <input type="text" name="otp" maxlength="6" inputmode="numeric"
                        placeholder="Masukkan 6 digit kode OTP"
                        style="flex:1;border:none;padding:16px;font-size:18px;letter-spacing:4px;background:#e8eaf0;color:#1b1f27;">
                </div>
                @error('otp')
                <p style="color:#f87171;font-size:13px;margin:4px 0 16px;">{{ $message }}</p>
                @enderror

                <hr style="border:none;border-top:1px solid #3a4050;margin:16px 0 24px;">

                <button type="submit"
                    style="background:#1e88e5;color:#fff;border:none;padding:12px 28px;border-radius:4px;font-size:15px;cursor:pointer;">
                    Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('password.email') }}" style="text-align:center;margin-top:20px;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                <button type="submit"
                    style="background:none;border:none;color:#8a93a6;text-decoration:underline;font-size:13px;cursor:pointer;">
                    Kirim ulang kode OTP
                </button>
            </form>
        </div>
    </div>
</body>

</html>