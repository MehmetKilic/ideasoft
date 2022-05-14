<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = "customers";

    protected $fillable = [
        "name_surname",
        "since",
        "revenue"
    ];

    protected $dates = [
        "since"
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
