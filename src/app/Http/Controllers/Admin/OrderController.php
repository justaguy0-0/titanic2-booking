<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.orders.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|string|in:Новый,Обработан,Оплачен,Отправлен,Отменён',
        ]);

        Order::create($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно добавлен.');
    }

    public function show(Order $order)
    {
        //
    }

    public function edit(Order $order)
    {
        $users = User::all();
        return view('admin.orders.edit', compact('order', 'users'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric|min:0|max:99999999.99',
            'status' => 'required|string|in:Новый,Обработан,Оплачен,Отправлен,Отменён',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно обновлён.');
    }

    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно удалён.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ошибка удаления: возможно, связанные элементы или платежи.');
        }
    }
}
