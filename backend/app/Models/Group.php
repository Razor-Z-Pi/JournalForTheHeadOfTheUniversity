<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name'];

    public function subgroups()
    {
        return $this -> hasMany(Subgroup::class);
    }

    public function students()
    {
        return $this -> hasMany(Student::class);
    }
}