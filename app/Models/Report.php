<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // Sesuaikan 'reports' dengan nama tabel yang benar di database Anda
    protected $table = 'reports'; 

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}