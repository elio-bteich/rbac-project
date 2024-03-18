<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
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
        'address_id'
    ];

    /**
     *  Get the address of the organization.
     *
     * @return BelongsTo
     */
    public function address() : BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     *  Get the contacts that belongs to the organization.
     *
     * @return HasMany
     */
    public function contacts() : HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
