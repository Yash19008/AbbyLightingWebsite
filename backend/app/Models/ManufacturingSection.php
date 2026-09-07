<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManufacturingSection extends Model
{
    use HasFactory;
    
    protected $table = 'manufacturing_section';
    
    protected $guarded = ['id'];
}
