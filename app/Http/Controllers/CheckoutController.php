<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function create(Request $request, string $key): mixed
    {
        $package = config('packages.'.$key);

        if (! $package) {
            return redirect()->route('learner.packages');
        }

        return $request->user()->checkout([$package['stripe_price_id'] => 1], [
            'success_url' => route('learner.checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('learner.packages'),
            'metadata' => [
                'user_id' => $request->user()->id,
                'package_key' => $key,
            ],
        ]);
    }

    public function success(): RedirectResponse
    {
        return redirect()->route('learner.dashboard')
            ->with('message', __('learner.packages.purchase_pending'));
    }
}
