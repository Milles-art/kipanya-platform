<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\WearProductVariant;
use App\Services\Commerce\WearCartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class WearCartController extends Controller
{
    public function index(Request $request, WearCartService $cart): View
    {
        return view('pages.public.cart', ['items' => $cart->items($request), 'subtotal' => $cart->subtotal($request)]);
    }

    public function store(Request $request, WearCartService $cart): RedirectResponse|JsonResponse
    {
        $data = $request->validate(['variant_id' => ['required', 'integer', 'exists:wear_product_variants,id'], 'quantity' => ['nullable', 'integer', 'min:1', 'max:20']]);
        $cart->add($request, WearProductVariant::with('product')->findOrFail($data['variant_id']), (int) ($data['quantity'] ?? 1));
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'message' => 'Added to your cart.',
                'cart_count' => $cart->count($request),
            ]);
        }

        return back()->with('cart_status', 'Added to your cart.');
    }

    public function update(Request $request, WearCartService $cart, int $variant): RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:20']]);
        $cart->setQuantity($request, $variant, (int) $data['quantity']);
        return back()->with('cart_status', 'Cart updated.');
    }

    public function remove(Request $request, WearCartService $cart, int $variant): RedirectResponse
    {
        $cart->remove($request, $variant);
        return back()->with('cart_status', 'Item removed.');
    }
}
