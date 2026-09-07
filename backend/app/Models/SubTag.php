<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTag extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'sub_tags';
    protected $guarded = [
        'id'
    ];
    /**
     * Subtags this subtag is linked TO
     */
    public function linkedSubTags()
    {
        return $this->belongsToMany(
            SubTag::class,       // Related model
            'sub_tag_relations', // Pivot table
            'sub_tag_id',        // FK on pivot for this model
            'linked_sub_tag_id'  // FK on pivot for related model
        );
    }

    /**
     * Subtags that are linking TO this subtag
     */
    public function linkedBySubTags()
    {
        return $this->belongsToMany(
            SubTag::class,
            'sub_tag_relations',
            'linked_sub_tag_id', // FK on pivot for this model
            'sub_tag_id'         // FK on pivot for related model
        );
    }

    /**
     * Convenience accessor: list of linked names
     */
    public function getLinkedNamesAttribute()
    {
        return $this->linkedSubTags->pluck('display_name')->implode(', ');
    }
}
