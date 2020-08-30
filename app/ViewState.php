<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewState extends Model
{
    private $categories = [
        'now_playing',
        'upcoming',
        'popular',
        'my',
        'category'
        'upload',
    ];
}
