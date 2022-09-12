<?php

namespace App\Models\Simulate;

use Illuminate\Database\Eloquent\Model;

class SAnswer extends Model
{
    //
    public $fillable = [
        'u_id',
        'q_id',
        'a_no',
        'status',
        'score'
    ];

    public function user() {
        return $this->belongsTo('App\User', 'u_id', 'id');
    }
    
    public function question() {
        return $this->belongsTo('App\Models\Admin\SQuestion', 'q_id', 'id');
    }

}
