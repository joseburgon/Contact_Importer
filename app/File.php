<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['file_name', 'url', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class)->select('name');
    }
    
}
