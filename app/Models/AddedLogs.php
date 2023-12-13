<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddedLogs extends Model
{
    use HasFactory;
    // protected $fillable = ['id'];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class,'id', 'employee_id');
    }
}
