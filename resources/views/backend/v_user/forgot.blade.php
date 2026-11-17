<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul ?? 'Recover Password' }}</title>
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

    .login-box {
        background: #4e545c;
        padding: 30px;
        border-radius: 8px;
        width: 400px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

    .input-group {
        display: flex;
        margin: 15px 0;
        border-radius: 4px;
        overflow: hidden;
    }

    .input-group-addon {
        background: #e05c34;
        padding: 10px 15px;
        display: flex;
        align-items: center;
    }

    input[type="email"] {
        width: 100%;
        padding: 10px;
        border: none;
        outline: none;
        font-size: 14px;
    }

    .btn-box {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-back {
        background: #2ebd59;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
    }

    .btn-recover {
        background: #2e59d9;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
    }

    .alert {
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 14px;
        text-align: left;
    }

    .alert-success {
        background: #2ebd59;
        color: white;
    }

    .alert-danger {
        background: #ff6b6b;
        color: white;
    }
    </style>
</head>

<body>

    <div class="login-box">
        <p>Enter your e-mail address below and we will send you instructions how to recover a password.</p>

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->has('email'))
            <div class="alert alert-danger">
                {{ $errors->first('email') }}
            </div>
            @endif

            <div class="input-group">
                <span class="input-group-addon" style="color:white;">✉</span>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" required>
            </div>

            <div class="btn-box">
                <a href="{{ route('backend.login') }}" class="btn-back">Back To Login</a>
                <button type="submit" class="btn-recover">Recover</button>
            </div>
        </form>
    </div>

</body>

</html>
