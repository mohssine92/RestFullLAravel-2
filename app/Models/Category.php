<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

class Category extends Model
{
   use HasFactory, SoftDeletes;


   // decirle el attributes como tal debe ser tratado como una fecha
   protected $dates = ['deleted_at'];

   protected $fillable = [  // son basicamente attributes que podran ser asignados de manera masiva .
       'name',
       'description'
   ];

   protected $hidden = [
    /* 97- REmoviendo , occultando elemento pivote (tabal pivote ) de la salida - respuesta una fecha
       se implementa en lo modelos involucrados en relacion de mucho a mucho , mucho a ucho
     */
    'pivot'
   ];



    /*
     * una categoria tiene ralacion de muchos a mucho con products
    */
   public function products()
   {
      return $this->belongsToMany(Product::class);
   } // lo que suceda aqui un acategoria como electronica pude tener varios productos : iphone , tele , tables , computer





}
