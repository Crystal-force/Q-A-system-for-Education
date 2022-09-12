<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class SQuestion extends Model
{
    //
    public $fillable = [
        'sc_id',
        'title',
        'contents',
        'score',
        'attached_files',
        'video_url',
        'sanswer1',
        'sanswer2',
        'sanswer3',
        'sanswer4',
        'sanswer5',
        'right_answer',
    ];

    public function sCategory() {
        return $this->belongsTo('App\Models\Admin\SCategory', 'sc_id', 'id');
    }

    public function answers() {
        return $this->hasMany('App\Models\Simulate\SAnswer', 'q_id', 'id');
    }
}
