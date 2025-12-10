@extends('admin.admin')
@section('title', 'Редактировать пользователя')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Редактировать пользователя</h1>
            <p class="text-gray-600 mt-2">
                ID: {{ $user->id }} | Создан: {{ $user->created_at->format('d.m.Y H:i') }}
            </p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-8 max-w-2xl">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Имя</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Новый пароль <span class="text-gray-500 text-xs">(оставьте пустым, чтобы не менять)</span>
                        </label>
                        <input type="password" name="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <input type="password" name="password_confirmation" placeholder="Повторите пароль"
                               class="mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg">
                        @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Роли</label>
                        <div class="space-y-2">
                            @foreach($roles as $role)
                                <label class="flex items-center gap-3">
                                    <input type="checkbox"
                                           name="roles[]"
                                           value="{{ $role->name }}"
                                           {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                           class="rounded text-indigo-600 focus:ring-indigo-500">
                                    <span class="{{ $role->name === 'admin' ? 'text-red-700 font-semibold' : 'text-gray-700' }}">
                                        {{ $role->name }}
                                        @if($role->name === 'admin')
                                            <span class="text-xs text-red-600">(полный доступ)</span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit"
                            class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                        Сохранить изменения
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="px-8 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition">
                        Отмена
                    </a>
                </div>
            </form>

            @if($user->id !== auth()->id())
                <div class="mt-10 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-red-700 mb-4">Опасная зона</h3>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Удалить пользователя «{{ addslashes($user->name) }}» навсегда?')"
                                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                            Удалить пользователя
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
