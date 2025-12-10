@extends('head')
@section('title', 'Dashboard')

@section('main_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <div class="welcome-card mb-4">
                <div class="p-4">
                    <h3 class="welcome-title">Добро пожаловать, {{ Auth::user()->name }}!</h3>
                    <p class="welcome-subtitle">Рады видеть вас в личном кабинете</p>
                </div>
            </div>

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

            
            @php
                
                $totalTickets = Auth::user()->orders()->where('status', 'Оплачен')->sum('ticket_count');
                
                if ($totalTickets >= 10) {
                    $loyaltyInfo = [
                        'level' => 3,
                        'discount' => 20,
                        'next_level_tickets' => null,
                        'progress' => 100
                    ];
                } elseif ($totalTickets >= 5) {
                    $nextLevelTickets = 10 - $totalTickets;
                    $progress = (($totalTickets - 5) / 5) * 100;
                    $loyaltyInfo = [
                        'level' => 2,
                        'discount' => 10,
                        'next_level_tickets' => $nextLevelTickets,
                        'progress' => min($progress, 100)
                    ];
                } else {
                    $nextLevelTickets = 5 - $totalTickets;
                    $progress = ($totalTickets / 5) * 100;
                    $loyaltyInfo = [
                        'level' => 1,
                        'discount' => 0,
                        'next_level_tickets' => $nextLevelTickets,
                        'progress' => min($progress, 100)
                    ];
                }
            @endphp

            <div class="section-card mb-4">
                <div class="section-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Ваша система лояльности</h5>
                        <span class="badge" style="background: linear-gradient(45deg, #fbbf24, #f59e0b); color: white; padding: 0.5rem 1rem; border-radius: 20px;">
                            Уровень {{ $loyaltyInfo['level'] }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                        <div class="mb-3">
                            <div class="text-center mb-1">
                                <span class="text-sm">Ваш прогресс </span>
                                
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 5px; background-color: #e5e7eb;">
                                <div class="progress-bar" role="progressbar" 
                                    style="width: {{ $loyaltyInfo['progress'] }}%; background: linear-gradient(45deg, #fbbf24, #f59e0b); border-radius: 5px;"
                                    aria-valuenow="{{ $loyaltyInfo['progress'] }}" 
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                            
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="text-xs text-gray-500">Билетов куплено</div>
                                    <div class="font-weight-bold" style="color: #f59e0b;">{{ Auth::user()->total_tickets ?? 0 }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-xs text-gray-500">Текущая скидка</div>
                                    <div class="font-weight-bold" style="color: #10b981;">{{ $loyaltyInfo['discount'] }}%</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-xs text-gray-500">Следующий уровень</div>
                                    <div class="font-weight-bold" style="color: #8b5cf6;">
                                        @if($loyaltyInfo['next_level_tickets'])
                                            {{ $loyaltyInfo['next_level_tickets'] }} билетов
                                        @else
                                            Максимум
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="bg-gray-800 p-4 rounded-lg">
                                <h6 class="text-white mb-3">Уровни лояльности</h6>
                                <div class="space-y-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle mr-2" style="width: 20px; height: 20px; background: {{ $loyaltyInfo['level'] >= 1 ? '#f59e0b' : '#4b5563' }};"></div>
                                            <span class="{{ $loyaltyInfo['level'] >= 1 ? 'text-yellow-300' : 'text-gray-400' }}">Уровень 1</span>
                                        </div>
                                        <span class="{{ $loyaltyInfo['level'] >= 1 ? 'text-yellow-300' : 'text-gray-400' }}">0-4 билетов • 0%</span>
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle mr-2" style="width: 20px; height: 20px; background: {{ $loyaltyInfo['level'] >= 2 ? '#f59e0b' : '#4b5563' }};"></div>
                                            <span class="{{ $loyaltyInfo['level'] >= 2 ? 'text-yellow-300' : 'text-gray-400' }}">Уровень 2</span>
                                        </div>
                                        <span class="{{ $loyaltyInfo['level'] >= 2 ? 'text-yellow-300' : 'text-gray-400' }}">5-9 билетов • 10%</span>
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle mr-2" style="width: 20px; height: 20px; background: {{ $loyaltyInfo['level'] >= 3 ? '#f59e0b' : '#4b5563' }};"></div>
                                            <span class="{{ $loyaltyInfo['level'] >= 3 ? 'text-yellow-300' : 'text-gray-400' }}">Уровень 3</span>
                                        </div>
                                        <span class="{{ $loyaltyInfo['level'] >= 3 ? 'text-yellow-300' : 'text-gray-400' }}">10+ билетов • 20%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($loyaltyInfo['next_level_tickets'])
                        <div class="mt-3 text-center">
                            <p class="text-sm text-gray-400">
                                <i class="fas fa-info-circle mr-1"></i>
                                До уровня {{ $loyaltyInfo['level'] + 1 }} осталось купить {{ $loyaltyInfo['next_level_tickets'] }} билетов
                            </p>
                        </div>
                    @endif
                </div>
            </div>

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
                            <a href="{{ route('support') }}" class="action-card action-card-yellow">
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

@endsection