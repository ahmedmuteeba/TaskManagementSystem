<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date_time',
        'assigned_user'
    ];

    const TODO = 'todo';
    const INPROGRESS = 'inprogress';
    const DONE = 'done';

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'assigned_user');
    }
}
