<?php

namespace admin\shipping_charges\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class ShippingRate extends Model
{
    use HasFactory, Sortable;    

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'min_value',
        'max_value',
        'rate',
        'method_id',
        'based_on',
    ];

     protected $sortable = [
        'min_value',
        'max_value',
        'rate',
        'created_at',
    ];

    public function scopeFilter($query, $keyword)
    {
        if ($keyword) {
            return $query->where('min_value', 'like', '%' . $keyword . '%')
                         ->orWhere('max_value', 'like', '%' . $keyword . '%')
                         ->orWhere('rate', 'like', '%' . $keyword . '%');
        }
        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class, 'method_id');
    }
}
