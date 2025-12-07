@extends('head')

@section('title', 'Вход')

@section('main_content')
<div class="login-container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card" style="background: #1e293b; border: 2px solid #fbbf24; border-radius: 12px; padding: 3rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);">

                    <div class="text-center mb-4">
                        <h2 style="font-family: Georgia, serif; color: #fbbf24; letter-spacing: 3px; font-weight: bold; font-size: 2rem; margin-bottom: 0.5rem;">
                            ВХОД
                        </h2>
                        <p style="font-family: Georgia, serif; color: #fcd34d; font-style: italic; font-size: 0.9rem;">
                            Добро пожаловать на борт
                        </p>
                        <div style="width: 60px; height: 2px; background: #fbbf24; margin: 1rem auto;"></div>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-4" style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; color: #86efac; border-radius: 8px; padding: 1rem;">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4 position-relative">
                            <label for="email" style="color: #fcd34d; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                                Email
                            </label>
                            <i class="fas fa-envelope position-absolute" style="color: #fbbf24; top: 2.5rem; left: 1rem; font-size: 1rem;"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="form-control ps-4"
                                style="background: #0f172a; border: 2px solid #475569; color: #fcd34d; padding: 0.75rem 1rem 0.75rem 3rem; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;"
                                onfocus="this.style.borderColor='#fbbf24'"
                                onblur="this.style.borderColor='#475569'"
                            >
                            @error('email')
                                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4 position-relative">
                            <label for="password" style="color: #fcd34d; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                                Пароль
                            </label>
                            <i class="fas fa-lock position-absolute" style="color: #fbbf24; top: 2.5rem; left: 1rem; font-size: 1rem;"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="form-control ps-4"
                                style="background: #0f172a; border: 2px solid #475569; color: #fcd34d; padding: 0.75rem 1rem 0.75rem 3rem; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;"
                                onfocus="this.style.borderColor='#fbbf24'"
                                onblur="this.style.borderColor='#475569'"
                            >
                            @error('password')
                                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="form-check-input"
                                >
                                <label for="remember_me" style="color: #cbd5e1; font-size: 0.9rem; cursor: pointer;">
                                    Запомнить меня
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="color: #fbbf24; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;">
                                    Забыли пароль?
                                </a>
                            @endif
                            <button
                                type="submit"
                                class="btn"
                                style="background: #fbbf24; color: #1e293b; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.75rem 2rem; border: none; border-radius: 8px; transition: background 0.3s ease; font-size: 0.9rem;"
                                onmouseover="this.style.background='#f59e0b'"
                                onmouseout="this.style.background='#fbbf24'"
                            >
                                Войти
                            </button>
                        </div>

                        <div class="text-center pt-3" style="border-top: 1px solid #475569;">
                            <p style="color: #cbd5e1; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                Нет аккаунта?
                            </p>
                            <a href="{{ route('register') }}" style="color: #fbbf24; text-decoration: none; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem; transition: color 0.3s;">
                                Зарегистрироваться
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus {
        outline: none;
    }

    .form-check-input:checked {
        background-color: #fbbf24;
        border-color: #fbbf24;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(251, 191, 36, 0.25);
    }

    a:hover {
        color: #f59e0b !important;
    }

    @media (max-width: 576px) {
        .login-card { padding: 2rem; }
        .btn { width: 100%; }
    }
</style>
@endsection
