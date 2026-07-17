<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <main class="auth-page">
        <section class="auth-panel">
            <div class="auth-brand">
                <span class="brand-mark">LAS</span>
                <span class="brand-name" style="color: var(--text)">Laravel Admin Suite</span>
            </div>

            <div>
                <h1 class="auth-title">Welcome back</h1>
                <p class="auth-copy">Sign in to manage projects, assignments and your team.</p>

                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="form-group" style="margin-bottom: 16px">
                        <label class="form-label" for="email">Email address</label>
                        <input class="form-control" id="email" name="email" type="email" value="{{ old('email', 'admin@example.com') }}" autocomplete="email" required autofocus>
                    </div>
                    <div class="form-group" style="margin-bottom: 16px">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" name="password" type="password" value="password" autocomplete="current-password" required>
                    </div>
                    <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px; color: var(--muted); font-size: 12px">
                        <input name="remember" type="checkbox" value="1"> Keep me signed in
                    </label>
                    <button class="btn btn-primary btn-block" type="submit">Sign in to workspace</button>
                </form>

                <div class="auth-demo">
                    <strong>Demo access</strong><br>
                    admin@example.com · password
                </div>
            </div>
        </section>

        <section class="auth-visual">
            <div class="visual-card">
                <div class="visual-eyebrow">Built for focused teams</div>
                <h2>Everything your operation needs, in one clear workspace.</h2>
                <p>A polished Laravel demonstration covering authentication, CRUD workflows, reporting and role-based access.</p>
                <div class="visual-stats">
                    <div class="visual-stat"><strong>4</strong><span>Core modules</span></div>
                    <div class="visual-stat"><strong>3</strong><span>Access roles</span></div>
                    <div class="visual-stat"><strong>100%</strong><span>Responsive</span></div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>

