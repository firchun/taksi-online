<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Taksi extends Model
{
    use HasFactory;
    protected $table = 'taksi';
    protected $guarded = [];

    public function supir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
