<aside class="w-64 shrink-0 border-r border-white/10 bg-white/60 dark:bg-gray-900/60 backdrop-blur">
    <div class="p-4">
        <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2">
            <span class="text-lg font-bold tracking-tight">Artsy</span>
        </a>
    </div>

    <nav class="px-2 pb-6 space-y-1 text-sm">
        {{-- Dashboard (now your order history) --}}
        <a href="{{ route('dashboard') }}"
           @class([
               'flex items-center gap-2 rounded px-3 py-2 transition',
               request()->routeIs('dashboard') ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'hover:bg-black/5 dark:hover:bg-white/5'
           ])>
            <span>🏠</span>
            <span>Dashboard</span>
        </a>

        {{-- Cart --}}
        <a href="{{ route('cart.show') }}"
           @class([
               'flex items-center gap-2 rounded px-3 py-2 transition',
               request()->routeIs('cart.show') ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'hover:bg-black/5 dark:hover:bg-white/5'
           ])>
            <span>🛒</span>
            <span>Cart</span>
        </a>

        {{-- Shop --}}
        <a href="{{ route('store.index') }}"
           @class([
               'flex items-center gap-2 rounded px-3 py-2 transition',
               request()->routeIs('store.*') ? 'bg-amber-500/10 text-amber-600 dark:text-amber-400' : 'hover:bg-black/5 dark:hover:bg-white/5'
           ])>
            <span>🖼️</span>
            <span>Shop</span>
        </a>
    </nav>
</aside>
