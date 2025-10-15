@extends('head')

@section('title', 'Регистрация')

@section('main_content')
<div class="register-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="register-card" style="background: linear-gradient(145deg, rgba(30, 41, 59, 0.95), rgba(51, 65, 85, 0.95)); border: 2px solid #fbbf24; border-radius: 15px; padding: 3rem; box-shadow: 0 10px 40px rgba(251, 191, 36, 0.2);">
                    
                    <!-- Заголовок -->
                    <div class="text-center mb-4">
                        <h2 style="font-family: Georgia, serif; color: #fbbf24; letter-spacing: 3px; font-weight: bold; font-size: 2rem; margin-bottom: 0.5rem;">
                            РЕГИСТРАЦИЯ
                        </h2>
                        <p style="font-family: Georgia, serif; color: #fcd34d; font-style: italic; font-size: 0.9rem;">
                            Начните путешествие с нами
                        </p>
                        <div style="width: 60px; height: 2px; background: #fbbf24; margin: 1rem auto;"></div>
                    </div>

                    <!-- Форма -->
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" style="color: #fcd34d; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                                Имя
                            </label>
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus 
                                autocomplete="name"
                                class="form-control"
                                style="background: rgba(15, 23, 42, 0.6); border: 2px solid #475569; color: #fcd34d; padding: 0.75rem 1rem; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='#fbbf24'; this.style.boxShadow='0 0 0 3px rgba(251, 191, 36, 0.1)'"
                                onblur="this.style.borderColor='#475569'; this.style.boxShadow='none'"
                            >
                            @error('name')
                                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" style="color: #fcd34d; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                                Email
                            </label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="username"
                                class="form-control"
                                style="background: rgba(15, 23, 42, 0.6); border: 2px solid #475569; color: #fcd34d; padding: 0.75rem 1rem; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
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
                        <div class="mb-4">
                            <label for="password" style="color: #fcd34d; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                                Пароль
                            </label>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                                class="form-control"
                                style="background: rgba(15, 23, 42, 0.6); border: 2px solid #475569; color: #fcd34d; padding: 0.75rem 1rem; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='#fbbf24'; this.style.boxShadow='0 0 0 3px rgba(251, 191, 36, 0.1)'"
                                onblur="this.style.borderColor='#475569'; this.style.boxShadow='none'"
                            >
                            @error('password')
                                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" style="color: #fcd34d; font-weight: 600; font-size: 0.9rem; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 0.5rem;">
                                Подтвердите пароль
                            </label>
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                class="form-control"
                                style="background: rgba(15, 23, 42, 0.6); border: 2px solid #475569; color: #fcd34d; padding: 0.75rem 1rem; border-radius: 8px; font-size: 1rem; transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='#fbbf24'; this.style.boxShadow='0 0 0 3px rgba(251, 191, 36, 0.1)'"
                                onblur="this.style.borderColor='#475569'; this.style.boxShadow='none'"
                            >
                            @error('password_confirmation')
                                <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Already registered & Submit Button -->
                        <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                            <a href="{{ route('login') }}" style="color: #fbbf24; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;">
                                Уже зарегистрированы?
                            </a>

                            <button 
                                type="submit" 
                                class="btn"
                                style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #1e293b; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; padding: 0.75rem 2.5rem; border: none; border-radius: 8px; box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3); transition: all 0.3s ease; font-size: 0.9rem;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(245, 158, 11, 0.4)'"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(245, 158, 11, 0.3)'"
                            >
                                Регистрация
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="text-center pt-3" style="border-top: 1px solid #475569;">
                            <p style="color: #94a3b8; font-size: 0.85rem; margin: 0;">
                                Регистрируясь, вы соглашаетесь с нашими<br>
                                <a href="#" style="color: #fbbf24; text-decoration: none;">Условиями использования</a> и 
                                <a href="#" style="color: #fbbf24; text-decoration: none;">Политикой конфиденциальности</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Улучшения для инпутов */
    .form-control:focus {
        outline: none;
    }
    
    /* Анимация для ссылок */
    a:hover {
        color: #f59e0b !important;
    }
    
    /* Responsive для мобильных */
    @media (max-width: 576px) {
        .register-card { 
            padding: 2rem; 
        }
        .btn { 
            width: 100%; 
            margin-top: 1rem;
        }
        .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
    }
</style>
@endsection