<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penumpang extends Model
{
    use HasFactory;
    protected $table = 'penumpang';
    protected $guarded = [];

    public function Pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class(), 'id_pemesanan');
    }
}