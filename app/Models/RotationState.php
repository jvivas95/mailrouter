<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RotationState extends Model
{
    //
    protected $table = 'rotation_state';

    protected $fillable = [
        'current_index'
    ];
}
