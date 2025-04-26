<?php

namespace App\Observers;

use App\Models\Categorie;
use App\Services\WebhookService;

class CategorieObserver
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    public function created(Categorie $categorie)
    {
        $this->webhookService->dispatch('categorie.created', $categorie);
    }

    public function updated(Categorie $categorie)
    {
        $this->webhookService->dispatch('categorie.updated', $categorie);
    }

    public function deleted(Categorie $categorie)
    {
        $this->webhookService->dispatch('categorie.deleted', $categorie);
    }
}
