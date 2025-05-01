<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;

#[Title('Success -')]
class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
    {
        $latest_order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();

        if ($this->session_id) {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session_info = CheckoutSession::retrieve($this->session_id);

            if ($session_info->payment_status != 'paid') {
                $latest_order->payment_status = 'failed';
                $latest_order->save();
                return redirect()->route('cancel');
            } else if ($session_info->payment_status == 'paid') {
                $latest_order->payment_status = 'paid';
                $latest_order->save();

                // Send email to user
                // Mail::to(auth()->user()->email)->send(new OrderSuccessMail($latest_order));
            }
        }

        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
