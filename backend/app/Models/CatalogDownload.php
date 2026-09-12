<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogDownload extends Model
{
    use HasFactory;

    protected $table = 'catalog_downloads';

    protected $guarded = [
        'id'
    ];

    public function catalogue()
    {
        return $this->belongsTo(Catalogue::class, 'catalogue_id');
    }
}

