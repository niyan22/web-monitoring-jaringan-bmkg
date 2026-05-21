<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>
<body>

<div class="auth-container">

    <div class="logo">
        <img src="{{ asset('assets/image/logo.jpeg') }}" alt="Logo">
    </div>

    <div class="auth-card">
        <h2>Register</h2>
        @if ($errors->any())
            <div class="error-messages">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <input class="input" type="text" name="name" placeholder="Name" required>
            <input class="input" type="email" name="email" placeholder="Email" required>
            <input class="input" type="password" name="password" placeholder="Password" required>
            <input class="input" type="password" name="password_confirmation" placeholder="Confirm Password" required>

            <button class="btn">Register</button>
        </form>

        <div class="links">
            <a href="{{ route('login') }}">Already have an account?</a>
        </div>
    </div>

</div>

</body>
</html>
