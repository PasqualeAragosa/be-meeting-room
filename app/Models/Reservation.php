<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'team_id', 'surname', 'date', 'timeFrom', 'timeTo', 'notes'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
