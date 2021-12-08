<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['task_id', 'comment', 'commenter_id'];
    protected $guarded = ['id'];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function commenter()
    {
        return $this->belongsTo(User::class, 'commenter_id');
    }
}
