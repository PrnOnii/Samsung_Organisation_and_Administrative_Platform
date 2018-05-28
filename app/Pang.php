<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pang extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total', 'student_id',
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
        return $this->belongsTo("App\Student");
    }
}
