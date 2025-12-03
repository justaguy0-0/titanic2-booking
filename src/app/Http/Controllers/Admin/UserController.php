<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        // Поиск
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Сортировка
        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');
        $allowed = ['id', 'name', 'email', 'created_at'];
        if (!in_array($sortField, $allowed)) $sortField = 'id';
        if (!in_array($sortDirection, ['asc', 'desc'])) $sortDirection = 'desc';

        $users = $query->orderBy($sortField, $sortDirection)->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Пользователь {$user->name} обновлён");
    }

    public function destroy(User $user)
    {
        // Нельзя удалить самого себя
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя удалить самого себя!');
        }

        // Нельзя удалить последнего админа
        if ($user->hasRole('admin') && User::role('admin')->count() === 1) {
            return back()->with('error', 'Нельзя удалить последнего администратора!');
        }

        $user->delete();

        return back()->with('success', 'Пользователь удалён');
    }

    public function show(User $user)
    {
        // Подгружаем роли и заказы (с товарами внутри)
        $user->load(['roles', 'orders.orderItems.ticket.voyage', 'orders.orderItems.entertainment']);

        return view('admin.users.show', compact('user'));
    }
}
