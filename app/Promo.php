<?php

namespace Soap;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function student()
    {
        return $this->hasMany("Soap\Student");
    }
}
