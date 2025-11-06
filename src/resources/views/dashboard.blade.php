@extends('head')
@section('title', 'Dashboard')

@section('main_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            {{-- Приветствие --}}
            <div class="welcome-card mb-4">
                <div class="p-4">
                    <h3 class="welcome-title">Добро пожаловать, {{ Auth::user()->name }}!</h3>
                    <p class="welcome-subtitle">Рады видеть вас в личном кабинете</p>
                </div>
            </div>

            {{-- Статистика --}}
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-blue">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Всего заказов</div>
                            <div class="stat-value">{{ Auth::user()->orders()->count() }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-green">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Активные</div>
                            <div class="stat-value">{{ Auth::user()->orders()->whereIn('status', ['Новый', 'Обработан', 'Оплачен', 'Отправлен'])->count() }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-yellow">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-label">Сумма покупок</div>
                            <div class="stat-value">{{ number_format(Auth::user()->orders()->sum('total_price'), 0) }} ₽</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Быстрые действия --}}
            <div class="section-card mb-4">
                <div class="section-header">
                    <h5 class="mb-0">Быстрые действия</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <a href="/shop" class="action-card action-card-blue">
                                <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <div>
                                    <div class="action-title">Забронировать</div>
                                    <div class="action-subtitle">Рейс на айсберг</div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('profile.orders') }}" class="action-card action-card-indigo">
                                <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <div>
                                    <div class="action-title">Мои заказы</div>
                                    <div class="action-subtitle">История покупок</div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <a href="{{ route('profile.edit') }}" class="action-card action-card-green">
                                <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <div class="action-title">Профиль</div>
                                    <div class="action-subtitle">Настройки аккаунта</div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <a href="#" class="action-card action-card-yellow">
                                <svg class="action-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <div class="action-title">Поддержка</div>
                                    <div class="action-subtitle">Помощь</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Последние заказы --}}
            @php
                $recentOrders = Auth::user()->orders()->orderBy('created_at', 'desc')->limit(5)->get();
            @endphp

            @if($recentOrders->count() > 0)
                <div class="section-card mb-4">
                    <div class="section-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Последние заказы</h5>
                            <a href="{{ route('profile.orders') }}" class="action-link">Все заказы →</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Дата</th>
                                        <th>Сумма</th>
                                        <th>Статус</th>
                                        <th class="text-end">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr>
                                            <td class="order-id">#{{ $order->id }}</td>
                                            <td class="order-date">{{ $order->created_at->format('d.m.Y') }}</td>
                                            <td class="order-price">{{ number_format($order->total_price, 2) }} ₽</td>
                                            <td>
                                                <span class="status-badge 
                                                    @if($order->status === 'Новый') status-new
                                                    @elseif($order->status === 'Обработан') status-processing
                                                    @elseif($order->status === 'Оплачен') status-paid
                                                    @elseif($order->status === 'Отправлен') status-sent
                                                    @elseif($order->status === 'Отменён') status-cancelled
                                                    @endif">
                                                    {{ $order->status }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('profile.orders.show', $order->id) }}" class="action-link">
                                                    Подробнее
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="section-card mb-4">
                    <div class="card-body text-center py-5">
                        <svg class="empty-icon mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <h3 class="empty-title">Заказов пока нет</h3>
                        <p class="empty-text">Начните делать покупки прямо сейчас!</p>
                        <a href="/" class="btn-submit mt-3">Перейти к каталогу</a>
                    </div>
                </div>
            @endif

            {{-- Информация профиля --}}
            <div class="section-card">
                <div class="section-header">
                    <h5 class="mb-0">Информация профиля</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-label">Имя</div>
                                <div class="profile-info-value">{{ Auth::user()->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-label">Email</div>
                                <div class="profile-info-value">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-label">Дата регистрации</div>
                                <div class="profile-info-value">{{ Auth::user()->created_at->format('d.m.Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-info-item">
                                <div class="profile-info-label">Статус email</div>
                                <div class="profile-info-value">
                                    @if(Auth::user()->email_verified_at)
                                        <span class="status-verified">✓ Подтверждён</span>
                                    @else
                                        <span class="status-unverified">Не подтверждён</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* Приветственная карточка */
.welcome-card {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    color: white;
}

.welcome-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    opacity: 0.9;
    margin: 0;
}

/* Статистика */
.stat-card {
    background: #1e293b;
    border: 1px solid #334155;
    border-radius: 0.5rem;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(251, 191, 36, 0.2);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon svg {
    width: 1.5rem;
    height: 1.5rem;
}

.stat-icon-blue {
    background: rgba(99, 102, 241, 0.2);
    color: #6366f1;
}

.stat-icon-green {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.stat-icon-yellow {
    background: rgba(251, 191, 36, 0.2);
    color: #fbbf24;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.875rem;
    color: #94a3b8;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #fbbf24;
}

/* Быстрые действия */
.action-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.action-card-blue {
    background: rgba(59, 130, 246, 0.15);
    border: 1px solid rgba(59, 130, 246, 0.3);
}

.action-card-blue:hover {
    background: rgba(59, 130, 246, 0.25);
}

.action-card-indigo {
    background: rgba(99, 102, 241, 0.15);
    border: 1px solid rgba(99, 102, 241, 0.3);
}

.action-card-indigo:hover {
    background: rgba(99, 102, 241, 0.25);
}

.action-card-green {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.action-card-green:hover {
    background: rgba(16, 185, 129, 0.25);
}

.action-card-yellow {
    background: rgba(251, 191, 36, 0.15);
    border: 1px solid rgba(251, 191, 36, 0.3);
}

.action-card-yellow:hover {
    background: rgba(251, 191, 36, 0.25);
}

.action-icon {
    width: 2rem;
    height: 2rem;
    color: #fbbf24;
    flex-shrink: 0;
}

.action-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #fcd34d;
}

.action-subtitle {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 0.125rem;
}

/* Статус бейджи специфичные */
.status-new {
    background: #3b82f6;
    color: white;
}

.status-processing {
    background: #eab308;
    color: #1e293b;
}

.status-paid {
    background: #10b981;
    color: white;
}

.status-sent {
    background: #8b5cf6;
    color: white;
}

.status-cancelled {
    background: #ef4444;
    color: white;
}

/* Пустое состояние */
.empty-icon {
    width: 3rem;
    height: 3rem;
    color: #64748b;
}

.empty-title {
    font-size: 1rem;
    font-weight: 600;
    color: #fcd34d;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #94a3b8;
    font-size: 0.875rem;
}

/* Информация профиля */
.profile-info-item {
    padding: 0.75rem;
    background: #0f172a;
    border-radius: 0.375rem;
    border: 1px solid #334155;
}

.profile-info-label {
    font-size: 0.875rem;
    color: #94a3b8;
    margin-bottom: 0.25rem;
}

.profile-info-value {
    font-size: 1rem;
    font-weight: 600;
    color: #fcd34d;
}

.status-verified {
    color: #10b981;
}

.status-unverified {
    color: #eab308;
}

@media (max-width: 767.98px) {
    .stat-card {
        padding: 1rem;
    }

    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
    }

    .stat-value {
        font-size: 1.25rem;
    }

    .welcome-title {
        font-size: 1.25rem;
    }
}
</style>
@endsection