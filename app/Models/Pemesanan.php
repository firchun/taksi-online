<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemesanan extends Model
{
    use HasFactory;
    protected $table = 'pemesanan';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function mobil(): BelongsTo
    {
        return $this->belongsTo(Taksi::class, 'id_taksi');
    }
    public function asal(): BelongsTo
    {
        return $this->belongsTo(Rute::class, 'id_rute_asal');
    }
    public function tujuan(): BelongsTo
    {
        return $this->belongsTo(Rute::class, 'id_rute_tujuan');
    }
}
