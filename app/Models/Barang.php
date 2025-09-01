<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{

    protected $primaryKey = 'id';
    public $incrementing = false;     // penting!
    protected $keyType = 'string';    // penting!
    protected $fillable = ['id', 'name', 'merk', 'model', 'serial_number', 'category_id', 'stock', 'kondisi', 'cabang_id', 'status', 'description'];
    public function getRouteKeyName()
    {
        return 'id';
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class);
    }

    public function requests()
    {
        return $this->hasMany(RequestBarang::class);
    }
}
