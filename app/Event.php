<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $visible = [
      'id', 'name', 'color', 'details','start', 'end',
    ];
}
