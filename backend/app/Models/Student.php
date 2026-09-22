<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['full_name', 'is_active', 'group_id', 'subgroup_id'];

    public function group()
    {
        return $this -> belongsTo(Group::class);
    }

    public function subgroup()
    {
        return $this -> belongsTo(Subgroup::class);
    }
}