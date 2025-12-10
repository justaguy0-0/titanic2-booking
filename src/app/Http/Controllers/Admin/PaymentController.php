<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('order');

        $sortField = $request->get('sort', 'id');
        $sortDirection = $request->get('direction', 'desc');

        $allowedSorts = [
            'id',
            'amount',
            'provider',
            'status',
            'created_at',
            'updated_at',
        ];

        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'id';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        if (in_array($sortField, ['id', 'amount', 'provider', 'status', 'created_at', 'updated_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }

        if ($sortField === 'order_id') {
            $query->orderBy('order_id', $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $payments = $query->paginate(20)->appends($request->query());

        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $orders = Order::orderBy('id', 'desc')->pluck('id', 'id');
        return view('admin.payments.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'provider' => 'required|in:Visa,MasterCard,SBP,Tinkoff,Yandex',
            'transaction_id' => 'required|string|unique:payments,transaction_id|max:100',
            'status' => 'required|in:Успешно,Отклонено,В обработке',
        ]);

        Payment::create($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Платёж успешно добавлен.');
    }

    public function edit(Payment $payment)
    {
        $orders = Order::orderBy('id', 'desc')->pluck('id', 'id');
        return view('admin.payments.edit', compact('payment', 'orders'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'provider' => 'required|in:Visa,MasterCard,SBP,Tinkoff,Yandex',
            'transaction_id' => [
                'required',
                'string',
                'max:100',
                Rule::unique('payments')->ignore($payment->id),
            ],
            'status' => 'required|in:Успешно,Отклонено,В обработке',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')->with('success', 'Платёж обновлён.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Платёж удалён.');
    }
}
