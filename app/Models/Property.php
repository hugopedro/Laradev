<?php

namespace LaraDev\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $table = 'properties';
    protected $fillable = ['title', 'name', 'description', 'rental_price', 'sale_price'];

    public $timestamps = false; // pra não bugar o ORM pois isso é um prototipo etc...

}
