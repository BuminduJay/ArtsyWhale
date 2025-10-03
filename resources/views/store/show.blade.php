<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 grid lg:grid-cols-2 gap-8">
        {{-- Main image --}}
        <div class="rounded-xl overflow-hidden border border-white/10">
            <img
                src="{{ $product->cover_url }}"
                alt="{{ $product->name }}"
                class="w-full h-auto object-cover"
                onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
        </div>

        {{-- Info --}}
        <div>
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>

            <div class="mt-2 flex items-center gap-3">
                <span class="text-2xl font-semibold">
                    ${{ number_format($product->price_cents / 100, 2) }}
                </span>
                @if(!$product->is_active || $product->stock < 1)
                    <span class="px-2 py-1 rounded bg-red-500/10 text-red-400 text-xs">Unavailable</span>
                @elseif($product->stock < 5)
                    <span class="px-2 py-1 rounded bg-amber-500/10 text-amber-400 text-xs">Low stock</span>
                @else
                    <span class="px-2 py-1 rounded bg-emerald-500/10 text-emerald-400 text-xs">In stock</span>
                @endif
            </div>

            <p class="mt-4 text-gray-300 leading-7">{{ $product->description }}</p>

            <div class="mt-6">
                <livewire:store.add-to-cart :product="$product" />
            </div>

            <div class="mt-8 text-sm text-gray-400">
                Category:
                <a href="{{ route('store.index') }}" class="hover:underline">
                    {{ optional($product->category)->name ?? 'Uncategorized' }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
