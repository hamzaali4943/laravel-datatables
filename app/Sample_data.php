<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sample_data extends Model
{
    //
    protected $table = 'sample_datas';
    protected $fillable = ['first_name', 'last_name'];
}
