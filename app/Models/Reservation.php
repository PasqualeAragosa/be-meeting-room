<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'surname', 'date', 'timeFrom', 'timeTo', 'notes'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
