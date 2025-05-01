<x-mail::message>
    # Order Confirmation

    Dear {{ $order->user->name ?? 'Customer' }},

    Thank you for your order. Your order number is {{ $order->id }}.

    <x-mail::button :url="$url">
        View Order
    </x-mail::button>

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
