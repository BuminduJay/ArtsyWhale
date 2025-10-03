<?php

namespace App\Livewire\Store;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')] // use Jetstream's main app layout safely
class CartPage extends Component
{
    public ?Cart $cart = null;

    public function mount(CartService $cart): void
    {
        $this->cart = $cart->forUser(Auth::user())->load('items.product');
    }

    #[On('cart-updated')]
    public function refreshCart(): void
    {
        $this->cart = app(CartService::class)->forUser(Auth::user())->load('items.product');
    }

    public function updateQty(CartService $cart, int $itemId, int $qty): void
    {
        $this->cart = $cart->updateQty(Auth::user(), $itemId, $qty);
        $this->dispatch('cart-updated');
    }

    public function removeItem(CartService $cart, int $itemId): void
    {
        $this->cart = $cart->remove(Auth::user(), $itemId);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.store.cart-page'); // Blade must have a single root element
    }
}
