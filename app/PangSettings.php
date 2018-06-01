<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PangSettings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "morning_early",
        "morning_start",
        "morning_late",
        "morning_end",
        "afternoon_start",
        "afternoon_leave",
        "afternoon_extra",
        "afternoon_end",
        "earning_pang",
        "losing_pang",
        "absent_loss"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
