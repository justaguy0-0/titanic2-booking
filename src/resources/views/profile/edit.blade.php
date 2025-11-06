@extends('head')

@section('title', 'Профиль')

@section('main_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="page-title mb-4">Профиль</h2>

            {{-- Информация профиля --}}
            <div class="profile-section mb-4">
                <div class="section-header">
                    <h5 class="mb-0">Информация профиля</h5>
                    <p class="section-subtitle">Обновите информацию вашего аккаунта и email адрес</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input type="text" 
                                   class="form-control custom-input" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control custom-input" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="alert alert-warning mt-2">
                                    Ваш email не подтвержден.
                                    <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-link">Отправить письмо повторно</button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn-submit">Сохранить</button>
                            @if (session('status') === 'profile-updated')
                                <span class="success-message">Сохранено!</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Изменение пароля --}}
            <div class="profile-section mb-4">
                <div class="section-header">
                    <h5 class="mb-0">Изменить пароль</h5>
                    <p class="section-subtitle">Убедитесь, что используете длинный надежный пароль</p>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Текущий пароль</label>
                            <input type="password" 
                                   class="form-control custom-input" 
                                   id="current_password" 
                                   name="current_password">
                            @error('current_password', 'updatePassword')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Новый пароль</label>
                            <input type="password" 
                                   class="form-control custom-input" 
                                   id="password" 
                                   name="password">
                            @error('password', 'updatePassword')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Подтвердите пароль</label>
                            <input type="password" 
                                   class="form-control custom-input" 
                                   id="password_confirmation" 
                                   name="password_confirmation">
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn-submit">Сохранить</button>
                            @if (session('status') === 'password-updated')
                                <span class="success-message">Сохранено!</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Удаление аккаунта --}}
            <div class="profile-section danger-section">
                <div class="section-header">
                    <h5 class="mb-0">Удалить аккаунт</h5>
                    <p class="section-subtitle">После удаления аккаунта все данные будут безвозвратно утеряны</p>
                </div>
                <div class="card-body">
                    <button type="button" class="btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        Удалить аккаунт
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Модальное окно удаления --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content custom-modal">
            <div class="modal-header">
                <h5 class="modal-title">Вы уверены?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>После удаления аккаунта все данные будут безвозвратно утеряны. Введите пароль для подтверждения.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
                    @csrf
                    @method('delete')
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Пароль</label>
                        <input type="password" 
                               class="form-control custom-input" 
                               id="delete_password" 
                               name="password" 
                               required>
                        @error('password', 'userDeletion')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="submit" form="deleteForm" class="btn-danger">Удалить аккаунт</button>
            </div>
        </div>
    </div>
</div>
@endsection