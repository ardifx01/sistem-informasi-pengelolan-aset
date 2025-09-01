<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferBarang extends Model
{
    protected $fillable = [
        'barang_id',
        'cabang_pengirim_id',
        'cabang_penerima_id',
        'description',
        'status_approve',
        'request_id'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function request()
    {
        return $this->belongsTo(RequestBarang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'cabang_penerima_id');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'cabang_pengirim_id');
    }
}
