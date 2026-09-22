<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subgroup extends Model
{
    protected $fillable = ['group_id', 'name'];

    public function group()
    {
        return $this -> belongsTo(Group::class);
    }

    public function students()
    {
        return $this -> hasMany(Student::class);
    }
}