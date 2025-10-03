<?php

namespace App\Services;

use App\Models\{Cart, CartItem, Product, User};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function forUser(User $user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id]);
    }

    public function add(User $user, Product $product, int $qty = 1): Cart
    {
        if (!$product->is_active) {
            throw ValidationException::withMessages(['product' => 'Product is not active.']);
        }
        if ($qty < 1) $qty = 1;

        return DB::transaction(function () use ($user, $product, $qty) {
            $cart = $this->forUser($user);

            /** @var CartItem $item */
            $item = $cart->items()->where('product_id', $product->id)->lockForUpdate()->first();

            $newQty = $qty + ($item?->quantity ?? 0);
            if ($newQty > $product->stock) {
                throw ValidationException::withMessages(['quantity' => 'Not enough stock.']);
            }

            if ($item) {
                $item->update(['quantity' => $newQty, 'unit_price_cents' => $product->price_cents]);
            } else {
                $cart->items()->create([
                    'product_id'        => $product->id,
                    'quantity'          => $qty,
                    'unit_price_cents'  => $product->price_cents,
                ]);
            }

            return $cart->fresh('items.product');
        });
    }

    public function updateQty(User $user, int $itemId, int $qty): Cart
    {
        return DB::transaction(function () use ($user, $itemId, $qty) {
            $cart = $this->forUser($user);

            $item = $cart->items()->whereKey($itemId)->with('product')->lockForUpdate()->firstOrFail();

            if ($qty < 1) {
                $item->delete();
                return $cart->fresh('items.product');
            }

            if ($qty > $item->product->stock) {
                throw ValidationException::withMessages(['quantity' => 'Not enough stock.']);
            }

            $item->update([
                'quantity'         => $qty,
                'unit_price_cents' => $item->product->price_cents,
            ]);

            return $cart->fresh('items.product');
        });
    }

    public function remove(User $user, int $itemId): Cart
    {
        $cart = $this->forUser($user);
        $cart->items()->whereKey($itemId)->delete();
        return $cart->fresh('items.product');
    }

    public function clear(User $user): void
    {
        $cart = $this->forUser($user);
        $cart->items()->delete();
    }
}
