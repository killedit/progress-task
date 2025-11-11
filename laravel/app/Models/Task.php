<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'assigned_to',
        'due_date',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'datetime:Y-m-d\TH:i:sP', // ISO 8601 with timezone offset
    ];


    /**
     * Get the user to whom the task is assigned.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created the task.
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
