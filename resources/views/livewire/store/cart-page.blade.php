<div wire:key="cart-root" class="max-w-3xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Your Cart</h1>

    @if(!$cart || $cart->items->isEmpty())
        <div class="rounded-xl border border-white/10 p-6 text-gray-500">
            Your cart is empty.
            <a href="{{ route('store.index') }}" class="text-amber-500 hover:underline ms-1">
                Continue shopping →
            </a>
        </div>
    @else
        <div class="overflow-x-auto rounded-xl border border-white/10">
            <table class="w-full text-left text-sm">
                <thead class="bg-black/5 dark:bg-white/5">
                    <tr>
                        <th class="py-3 px-4">Product</th>
                        <th class="py-3 px-4">Price</th>
                        <th class="py-3 px-4">Qty</th>
                        <th class="py-3 px-4 text-right">Subtotal</th>
                        <th class="py-3 px-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart->items as $item)
                        <tr class="border-t border-white/10">
                            <td class="py-3 px-4 font-medium">{{ $item->product->name }}</td>
                            <td class="py-3 px-4">
                                ${{ number_format($item->unit_price_cents / 100, 2) }}
                            </td>
                            <td class="py-3 px-4">
                                <input
                                    type="number"
                                    min="1"
                                    value="{{ $item->quantity }}"
                                    class="w-20 rounded border border-white/10 bg-transparent px-2 py-1"
                                    wire:change="updateQty({{ $item->id }}, parseInt($event.target.value, 10))"
                                >
                            </td>
                            <td class="py-3 px-4 text-right">
                                ${{ number_format(($item->unit_price_cents * $item->quantity)/100, 2) }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button
                                    wire:click="removeItem({{ $item->id }})"
                                    class="text-rose-500 hover:underline"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end items-center gap-4">
            <div class="text-xl font-semibold">
                Total: ${{ number_format($cart->totalCents() / 100, 2) }}
            </div>
            <a href="{{ route('checkout.show') }}"
               class="px-4 py-2 rounded bg-emerald-600 text-white hover:bg-emerald-500 transition">
                Checkout
            </a>
        </div>
    @endif
</div>
