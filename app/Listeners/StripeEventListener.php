<?php

namespace App\Listeners;

use App\Models\Purchase;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    public function handle(WebhookReceived $event): void
    {
        if ($event->payload['type'] !== 'checkout.session.completed') {
            return;
        }

        $session = $event->payload['data']['object'];

        if ($session['payment_status'] !== 'paid') {
            return;
        }

        $metadata = $session['metadata'] ?? [];
        $userId = $metadata['user_id'] ?? null;
        $packageKey = $metadata['package_key'] ?? null;

        if (! $userId || ! $packageKey) {
            return;
        }

        $package = config('packages.'.$packageKey);
        if (! $package) {
            return;
        }

        Purchase::firstOrCreate(
            ['stripe_session_id' => $session['id']],
            [
                'user_id' => $userId,
                'package_key' => $packageKey,
                'sessions_total' => $package['sessions_count'],
                'sessions_remaining' => $package['sessions_count'],
                'status' => 'completed',
            ]
        );
    }
}
