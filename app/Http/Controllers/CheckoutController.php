<?php

namespace App\Http\Controllers;

use App\Models\SessionPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function create(Request $request, SessionPackage $package): mixed
    {
        if (! $package->is_active) {
            return redirect()->route('learner.packages');
        }

        return $request->user()->checkout([$package->stripe_price_id => 1], [
            'success_url' => route('learner.checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('learner.packages'),
            'metadata' => [
                'user_id' => $request->user()->id,
                'package_id' => $package->id,
            ],
        ]);
    }

    public function success(): RedirectResponse
    {
        return redirect()->route('learner.dashboard')
            ->with('message', __('learner.packages.purchase_pending'));
    }
}
