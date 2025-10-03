<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Your Orders</h1>

        @if ($orders->count())
            <div class="overflow-x-auto rounded-xl border border-white/10">
                <table class="min-w-full text-sm">
                    <thead class="bg-black/5 dark:bg-white/5">
                        <tr class="text-left">
                            <th class="px-4 py-3">Order #</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Total</th>
                            <th class="px-4 py-3">Placed</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="border-t border-white/10">
                                <td class="px-4 py-3 font-medium">#{{ $order->id }}</td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'px-2 py-1 rounded text-xs',
                                        'bg-emerald-500/10 text-emerald-400' => in_array($order->status, ['paid','completed']),
                                        'bg-amber-500/10 text-amber-400' => in_array($order->status, ['pending','processing']),
                                        'bg-rose-500/10 text-rose-400' => in_array($order->status, ['canceled','failed']),
                                        'bg-gray-500/10 text-gray-400' => ! in_array($order->status, ['paid','completed','pending','processing','canceled','failed']),
                                    ])>
                                        {{ ucfirst($order->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">${{ number_format(($order->total_cents ?? 0)/100, 2) }}</td>
                                <td class="px-4 py-3">{{ optional($order->created_at)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if (Route::has('orders.show'))
                                        <a href="{{ route('orders.show', $order) }}" class="text-amber-500 hover:underline">View</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @else
            <div class="rounded-xl border border-white/10 p-6 text-gray-500">
                You haven’t placed any orders yet.
                <a href="{{ route('store.index') }}" class="text-amber-500 hover:underline">Browse the shop →</a>
            </div>
        @endif
    </div>
</x-app-layout>
