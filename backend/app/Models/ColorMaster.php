<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'hex_code',
        'gradient_start',
        'gradient_end',
        'gradient_type',
        'gradient_direction',
        'description',
        'category',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['css_value'];

    /**
     * Get CSS value for the color (solid or gradient)
     */
    public function getCssValueAttribute(): string
    {
        if ($this->type === 'solid') {
            return $this->hex_code ?? '#FFFFFF';
        }
        
        // Generate gradient CSS
        $start = $this->gradient_start ?? '#FFFFFF';
        $end = $this->gradient_end ?? '#000000';
        
        if ($this->gradient_type === 'radial') {
            return "radial-gradient(circle, {$start}, {$end})";
        }
        
        // Linear gradient
        $direction = $this->gradient_direction ?? 'to right';
        return "linear-gradient({$direction}, {$start}, {$end})";
    }

    /**
     * Scope: Get only active colors
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Scope: Get colors by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Get colors by category
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
