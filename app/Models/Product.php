<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    //
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

}
