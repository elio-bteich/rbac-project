<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    /**
     * Get the permissions associated with this location.
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(FolderPermission::class);
    }

    /**
     *  Get the contacts that belong to this folder
     *
     * @return BelongsToMany
     */
    public function contacts() : BelongsToMany
    {
        return $this->belongsToMany(Contact::class);
    }

}
