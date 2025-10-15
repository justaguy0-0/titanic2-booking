@extends('head')

@section('title', 'Вход')

@section('main_content')
<div class="login-container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card fade-in" style="background: linear-gradient(145deg, rgba(30, 41, 59, 0.95), rgba(51, 65, 85, 0.95)); border: 2px solid #fbbf24; border-radius: 15px; padding: 3rem; box-shadow: 0 10px 40px rgba(251, 191, 36, 0.2); animation: fadeIn 0.8s ease-in-out;">
                    
                    <!-- Заголовок -->
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

                    <!-- Форма -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
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
                                style="background: rgba(15, 23, 42, 0.6); border: 2px solid #475569; color: #fcd34d; padding: 0.75rem 1rem 0.75rem 3rem; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='#fbbf24'; this.style.boxShadow='0 0 0 3px rgba(251, 191, 36, 0.1)'"
                                onblur="this.style.borderColor='#475569'; this.style.boxShadow='none'"
                            >
                            @error('email')
                                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
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
                                style="background: rgba(15, 23, 42, 0.6); border: 2px solid #475569; color: #fcd34d; padding: 0.75rem 1rem 0.75rem 3rem; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='#fbbf24'; this.style.boxShadow='0 0 0 3px rgba(251, 191, 36, 0.1)'"
                                onblur="this.style.borderColor='#475569'; this.style.boxShadow='none'"
                            >
                            @error('password')
                                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="form-check-input"
                                    style="background: rgba(15, 23, 42, 0.6); border: 2px solid #475569; cursor: pointer;"
                                >
                                <label for="remember_me" style="color: #cbd5e1; font-size: 0.9rem; cursor: pointer;">
                                    Запомнить меня
                                </label>
                            </div>
                        </div>

                        <!-- Forgot Password & Submit -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" style="color: #fbbf24; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;">
                                    Забыли пароль?
                                </a>
                            @endif
                            <button
                                type="submit"
                                class="btn"
                                style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #1e293b; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.75rem 2rem; border: none; border-radius: 8px; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: all 0.3s ease; font-size: 0.9rem;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(245, 158, 11, 0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(245, 158, 11, 0.3)'"
                            >
                                Войти
                            </button>
                        </div>

                        <!-- Register Link -->
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
    /* Анимация fade-in */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .fade-in { animation: fadeIn 0.8s ease-in-out; }

    /* Улучшения для инпутов */
    .form-control:focus {
        outline: none;
    }

    /* Стиль для чекбокса */
    .form-check-input:checked {
        background-color: #fbbf24;
        border-color: #fbbf24;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(251, 191, 36, 0.25);
    }

    /* Анимация для ссылок */
    a:hover {
        color: #f59e0b !important;
    }

    /* Responsive для мобильных */
    @media (max-width: 576px) {
        .login-card { padding: 2rem; }
        .btn { width: 100%; }
    }
</style>
@endsection