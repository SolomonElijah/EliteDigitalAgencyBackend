<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Elite Digital Agency</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}
.login-card {
    background: #fff;
    border-radius: 16px;
    padding: 2.5rem;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 25px 50px rgba(0,0,0,0.4);
}
.brand-logo {
    width: 56px; height: 56px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 1rem;
}
.btn-login {
    background: linear-gradient(135deg, #302b63, #24243e);
    color: white;
    border: none;
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    width: 100%;
    font-size: 0.95rem;
    transition: opacity 0.2s;
}
.btn-login:hover { opacity: 0.9; color: white; }
.form-control:focus { border-color: #302b63; box-shadow: 0 0 0 3px rgba(48,43,99,0.15); }
</style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand-logo">⚡</div>
        <h4 class="fw-700 mb-1">Elite Digital Agency</h4>
        <p class="text-muted small">Sign in to your dashboard</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger small py-2">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-500 small">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                placeholder="admin@eliteagency.com" required autofocus>
            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>
        <div class="mb-4">
            <label class="form-label fw-500 small">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-login">Sign In →</button>
    </form>
</div>
</body>
</html>
