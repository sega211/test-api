<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
       
            'name',
            'quantity',
            'supplier_article',
            'tech_size',
            'barcode',
            'price',
            'warehouse_name',
            'in_way_to_client',
            'in_way_from_client',
            'nm_id',
            'subject',
            'category',
            'brand',
            'sc_code',
            'discount'
        
    ];
}
