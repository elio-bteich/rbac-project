<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'job',
        'organization_id',
        'comments'
    ];

    /**
     *  Get the organization to which this contact belongs
     *
     * @return BelongsTo
     */
    public function organization() : BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     *  Get the folders to which this contact belongs
     *
     * @return BelongsToMany
     */
    public function folders() : BelongsToMany
    {
        return $this->belongsToMany(Folder::class);
    }

}
