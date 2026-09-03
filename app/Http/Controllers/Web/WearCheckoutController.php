<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\Commerce\WearCartService;
use App\Services\Commerce\WearOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class WearCheckoutController extends Controller
{
    public function create(Request $request, WearCartService $cart): View|RedirectResponse
    {
        if ($cart->count($request) < 1) {
            return redirect()->route('wear.cart')->with('cart_status', 'Add an item before checkout.');
        }
        return view('pages.public.checkout', ['items' => $cart->items($request), 'subtotal' => $cart->subtotal($request)]);
    }

    public function store(Request $request, WearCartService $cart, WearOrderService $orders): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:190'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'delivery_city' => ['nullable', 'string', 'max:80'],
            'payment_method' => ['required', 'in:mobile_money,pay_on_delivery'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $order = $orders->place($request, $data, $cart);
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['customer_phone' => $e->getMessage()])->withInput();
        }

        session(['wear_order_' . $order->order_number => $order->order_number]);
        return redirect()->route('wear.order', $order->order_number)->with('order_status', 'Order received.');
    }

    public function show(Request $request, string $order): View
    {
        $record = \App\Models\WearOrder::query()->with('items')->where('order_number', $order)->firstOrFail();
        abort_unless($request->user()?->id === $record->user_id || session('wear_order_' . $record->order_number) === $record->order_number, 404);
        return view('pages.public.order-confirmation', ['order' => $record]);
    }
}
