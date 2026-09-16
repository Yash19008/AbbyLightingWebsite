<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeCatalogueSection extends Model
{
    use HasFactory;

    protected $table = 'home_catalogue_section';

    protected $guarded = ['id'];
}
