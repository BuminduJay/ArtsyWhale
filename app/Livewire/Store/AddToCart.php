<?php

namespace App\Livewire\Store;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class AddToCart extends Component
{   

    public Product $product;
    public int $qty = 1;

    public function mount(Product $product, int $qty = 1)
    {
        $this->product = $product;
        $this->qty = $qty;
    }

    public function add(CartService $cart)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $cart->add($user, $this->product, $this->qty);

        $this->dispatch('cart-updated'); // notify other components
        session()->flash('success', 'Added to cart!');
    }


    public function render()
    {
        return view('livewire.store.add-to-cart');
    }
}
