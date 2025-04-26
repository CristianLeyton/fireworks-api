<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\WebhookService;

class ProductObserver
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function created(Product $product)
    {
        $this->webhookService->dispatch('product.created', $product);
    }

    public function updated(Product $product)
    {
        $this->webhookService->dispatch('product.updated', $product);
    }

    public function deleted(Product $product)
    {
        $this->webhookService->dispatch('product.deleted', $product);
    }
}
