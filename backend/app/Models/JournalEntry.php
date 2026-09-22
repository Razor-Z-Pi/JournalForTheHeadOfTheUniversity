<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'student_id',
        'discipline_id',
        'week_number',
        'missed_hours',
        'grade',
        'note',
    ];
}