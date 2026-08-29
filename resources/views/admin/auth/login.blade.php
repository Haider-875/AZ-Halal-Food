<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal Login · AZ Halal Marts</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #070E07;
            color: #F5F0E8;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-card {
            background-color: #0F1F0F;
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 8px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8), 0 0 25px rgba(212, 175, 55, 0.15);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }

        .gold-input {
            background-color: rgba(7, 14, 7, 0.85) !important;
            border: 1px solid rgba(212, 175, 55, 0.3) !important;
            color: #F5F0E8 !important;
            padding: 0.8rem 1.2rem;
            border-radius: 4px;
        }

        .gold-input:focus {
            border-color: #D4AF37 !important;
            box-shadow: 0 0 12px rgba(212, 175, 55, 0.3) !important;
        }

        .btn-gold {
            background: #D4AF37;
            color: #070E07;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 0.85rem;
            border: none;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .btn-gold:hover {
            background: #E8C84A;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="login-card p-4 p-md-5">
    <div class="text-center mb-4">
        <h2 class="text-uppercase fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: #D4AF37; letter-spacing: 0.2em;">AZ Halal</h2>
        <span class="text-uppercase small" style="color: rgba(212, 175, 55, 0.6); letter-spacing: 0.35em; font-size: 10px;">Admin Portal Login</span>
    </div>

    @if(session('success'))
    <div class="alert alert-success small py-2 bg-success bg-opacity-25 border-success text-white">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger small py-2 bg-danger bg-opacity-25 border-danger text-white">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger small py-2 bg-danger bg-opacity-25 border-danger text-white">
        {{ $errors->first() }}
    </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="email" class="form-label small text-uppercase fw-semibold" style="letter-spacing: 0.1em; color: rgba(245,240,232,0.7);">Email Address</label>
            <input type="email" name="email" id="email" class="form-control gold-input" value="{{ old('email', 'admin@azhalal.com') }}" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label small text-uppercase fw-semibold" style="letter-spacing: 0.1em; color: rgba(245,240,232,0.7);">Password</label>
            <input type="password" name="password" id="password" class="form-control gold-input" value="password123" required>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                <label class="form-check-label small" for="remember" style="color: rgba(245,240,232,0.7);">Remember Me</label>
            </div>
            <a href="{{ route('home') }}" class="small text-decoration-none" style="color: #D4AF37;">Back to Store</a>
        </div>

        <button type="submit" class="btn btn-gold w-100 mb-3">Sign In to Dashboard</button>
    </form>
</div>

</body>
</html>
