<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuteTaksi extends Model
{
    use HasFactory;
    protected $table = 'rute_taksi';
    protected $guarded = [];

    public function mobil(): BelongsTo
    {
        return $this->belongsTo(Taksi::class, 'id_taksi');
    }
    public function rute(): BelongsTo
    {
        return $this->belongsTo(Rute::class, 'id_rute');
    }
}
