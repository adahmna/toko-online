<!DOCTYPE html>
<html lang="en">

<head>
    <title>Verifikasi Ganti Password</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #343a40;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .box {
        background: #4e545c;
        padding: 30px;
        border-radius: 8px;
        width: 350px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
    }

    input {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
        border: none;
        box-sizing: border-box;
    }

    button {
        width: 100%;
        padding: 10px;
        background: #28a745;
        border: none;
        color: white;
        font-weight: bold;
        cursor: pointer;
        border-radius: 4px;
    }

    .error {
        color: #ff6b6b;
        font-size: 13px;
    }
    </style>
</head>

<body>
    <div class="box">
        <h3>Verifikasi Reset Password</h3>

        @if(session('success'))
        <p style="color: #2ebd59;">{{ session('success') }}</p>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="email" value="{{ session('reset_email') ?? old('email') }}">

            @if(!session('reset_email'))
            <input type="email" name="email" placeholder="Konfirmasi Email Anda" required>
            @endif

            <label>Masukkan 6 Digit Kode dari Gmail:</label>
            <input type="text" name="code" placeholder="Contoh: XyZ71W" required>
            @error('code') <span class="error">{{ $message }}</span> @enderror

            <label>Password Baru:</label>
            <input type="password" name="password" placeholder="Password Baru" required>
            @error('password') <span class="error">{{ $message }}</span> @enderror

            <label>Ulangi Password Baru:</label>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>

            <button type="submit">Perbarui Password</button>
        </form>
    </div>
</body>

</html>
