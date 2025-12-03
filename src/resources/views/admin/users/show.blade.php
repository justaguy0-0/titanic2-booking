@extends('admin.admin')
@section('title', "Пользователь #{$user->id}")

@section('content')
    <div class="container mx-auto px-4 py-6">

        <!-- Хлебные крошки -->
        <nav class="mb-6">
            <ol class="list-reset flex text-gray-600">
                <li>
                    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700">
                        Пользователи
                    </a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500">#{{ $user->id }}</li>
            </ol>
        </nav>

        <!-- Уведомления -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Заголовок + кнопка редактирования -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Пользователь #{{ $user->id }}</h1>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Редактировать
                </a>
            </div>
        </div>

        <!-- Основная информация -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Основная информация</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Левая колонка -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">#{{ $user->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Имя</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-sm">
                                <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                    {{ $user->email }}
                                </a>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Роли</label>
                            <p class="mt-1">
                            @if($user->roles->count())
                                <div class="flex flex-wrap gap-2">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                {{ $role->name === 'admin' ? 'bg-red-100 text-red-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                {{ $role->name }}
                                            </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 italic">нет ролей</span>
                                @endif
                                </p>
                        </div>
                    </div>

                    <!-- Правая колонка -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Создан</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $user->created_at->format('d.m.Y в H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Обновлён</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $user->updated_at->format('d.m.Y в H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Всего заказов</label>
                            <p class="mt-1 text-xl font-bold text-gray-900">
                                {{ $user->orders->count() }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">На общую сумму</label>
                            <p class="mt-1 text-xl font-bold text-gray-900">
                                {{ number_format($user->orders->sum('total_price'), 0, '', ' ') }} ₽
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Последние заказы пользователя -->
        @if($user->orders->count())
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Последние заказы</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($user->orders->sortByDesc('id')->take(10) as $order)
                            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                                <div class="flex-1">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="font-medium text-blue-600 hover:text-blue-800">
                                        Заказ #{{ $order->id }}
                                    </a>
                                    <span class="text-sm text-gray-500 ml-3">
                                        {{ $order->created_at->format('d.m.Y в H:i') }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">
                                        {{ number_format($order->total_price, 0, '', ' ') }} ₽
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium mt-1
                                        @switch($order->status)
                                            @case('Новый') bg-blue-100 text-blue-800 @break
                                            @case('Обработан') bg-yellow-100 text-yellow-800 @break                                            @case('Оплачен') bg-green-100 text-green-800 @break                                            @case('Отправлен') bg-purple-100 text-purple-800 @break                                            @case('Отменён') bg-red-100 text-red-800 @break                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800">Заказы</h2>
                </div>
                <div class="p-6 text-center text-gray-500">
                    У пользователя нет заказов
                </div>
            </div>
        @endif

        <!-- Кнопка возврата -->
        <div class="mt-6">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Вернуться к списку пользователей
            </a>
        </div>
    </div>
@endsection
