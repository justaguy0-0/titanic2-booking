<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Display user's orders.
     */
    public function orders(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    /**
     * Show specific order details.
     */
    public function showOrder(Request $request, $orderId): View
    {
        $order = $request->user()
            ->orders()
            ->with([
                // Исправлено: используем новые связи
                'orderItems.ticket.voyage.departurePlace',
                'orderItems.ticket.voyage.arrivalPlace',
                'orderItems.entertainment'
            ])
            ->findOrFail($orderId);

        return view('profile.order-details', compact('order'));
    }

    /**
     * Cancel user's order (if allowed).
     */
    public function cancelOrder(Request $request, $orderId): RedirectResponse
    {
        $order = $request->user()
            ->orders()
            ->findOrFail($orderId);

        if (!in_array($order->status, ['Новый', 'Обработан'])) {
            return Redirect::back()->with('error', 'Заказ нельзя отменить на текущем этапе.');
        }

        $order->update(['status' => 'Отменён']);

        return Redirect::route('profile.orders')->with('success', 'Заказ успешно отменён.');
    }
}