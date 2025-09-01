<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestBarang extends Model
{
     protected $fillable = [
        'barang_id',
        'user_id',
        'cabang_id',
        'date_request',
        'description',
        'status_approve',
    ];
       public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }
    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
}
