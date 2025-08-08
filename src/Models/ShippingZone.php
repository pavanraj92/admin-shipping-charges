<?php

namespace admin\shipping_charges\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class ShippingZone extends Model
{
    use HasFactory, Sortable;    

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'status',
    ];
}
