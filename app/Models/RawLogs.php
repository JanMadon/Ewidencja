<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RawLogs extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
    
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
