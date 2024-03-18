<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'street',
        'city',
        'postal_code'
    ];

    /**
     *  Get the organizations in this address
     *
     * @return HasMany
     */
    public function organizations() : HasMany
    {
        return $this->hasMany(Organization::class);
    }
}
