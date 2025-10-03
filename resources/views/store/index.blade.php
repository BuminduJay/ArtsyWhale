<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">
        {{-- Optional heading --}}
        <h1 class="text-2xl font-bold mb-6">New & Trending</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($products as $product)
                <div class="rounded-xl border border-white/10 overflow-hidden hover:shadow-lg transition">
                    <a href="{{ route('store.show', $product->slug) }}" class="block aspect-[4/3] bg-black/5">
                        <img
                            src="{{ $product->cover_url }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover"
                            onerror="this.src='{{ asset('images/placeholder-product.jpg') }}'">
                    </a>

                    <div class="p-4">
                        <a href="{{ route('store.show', $product->slug) }}" class="font-semibold text-lg hover:underline">
                            {{ $product->name }}
                        </a>

                        <div class="mt-1 text-gray-400">
                            ${{ number_format($product->price_cents / 100, 2) }}
                        </div>

                        <div class="mt-4">
                            <livewire:store.add-to-cart :product="$product" :key="'add-'.$product->id" />
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-gray-500">No products yet.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
