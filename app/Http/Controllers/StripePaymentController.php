<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class StripePaymentController extends Controller
{
    /**
     * Display the payment form.
     *
     * @return \Illuminate\View\View
     */
    public function stripe()
    {
        return view('stripe');
    }

    /**
     * Handle the Stripe payment processing.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function stripePost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stripeToken' => 'required|string',
        ]);

        try {
            // Set Stripe API key
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Create the charge
            Charge::create([
                "amount" => 10 * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from Laravel Stripe Integration.",
            ]);

            return back()->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
