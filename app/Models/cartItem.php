<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * modelo do carrinho para o banco de dados
 */
class cartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'product_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
