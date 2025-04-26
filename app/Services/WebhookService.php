<?php

namespace App\Services;

use App\Models\Webhook;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WebhookService
{
    public function dispatch($event, $data)
    {
        $webhooks = Webhook::where('is_active', true)
            ->where(function ($query) use ($event) {
                $query->whereNull('events')
                    ->orWhereJsonContains('events', $event);
            })
            ->get();

        foreach ($webhooks as $webhook) {
            $this->sendWebhook($webhook, $event, $data);
        }
    }

    protected function sendWebhook(Webhook $webhook, $event, $data)
    {
        $payload = [
            'event' => $event,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'X-Webhook-Signature' => $this->generateSignature($webhook, $payload),
        ];

        try {
            Http::withHeaders($headers)
                ->post($webhook->url, $payload);
        } catch (\Exception $e) {
            Log::error("Error sending webhook to {$webhook->url}: {$e->getMessage()}");
        }
    }

    protected function generateSignature(Webhook $webhook, $payload)
    {
        if (!$webhook->secret) {
            return null;
        }

        $payloadString = json_encode($payload);
        return hash_hmac('sha256', $payloadString, $webhook->secret);
    }
}
