<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EditPang extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "student_id", "day", "quantity", "reason"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
