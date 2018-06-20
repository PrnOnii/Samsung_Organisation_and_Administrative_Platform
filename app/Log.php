<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'category_id', 'action'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function user() {
        return $this->belongsTo("App\User", "user_id");
    }

    public function logCategory() {
        return $this->belongsTo("App\LogCategory", "category_id");
    }
}
