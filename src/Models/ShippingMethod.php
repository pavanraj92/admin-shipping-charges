<?php

namespace admin\shipping_charges\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class ShippingMethod extends Model
{
    use HasFactory, Sortable;    

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'carrier',
        'delivery_time',
        'base_rate',
        'zone_id',
        'status',
    ];

    protected $sortable = [
        'name',
        'carrier',
        'status',
        'created_at',
    ];

    public function scopeFilter($query, $keyword)
    {
        if ($keyword) {
            return $query->where(function ($q) use ($keyword) {
                $q->whereHas('zone', function ($zoneQuery) use ($keyword) {
                    $zoneQuery->where('name', 'like', '%' . $keyword . '%');
                })
                ->orWhere('name', 'like', '%' . $keyword . '%')
                ->orWhere('carrier', 'like', '%' . $keyword . '%');
            });
        }

        return $query;
    }
    /**
     * filter by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if (!is_null($status)) {
            return $query->where('status', $status);
        }

        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }
    public function shippingRates()
    {
        return $this->hasMany(ShippingRate::class, 'shipping_method_id');
    }

    public function zone()
    {
        return $this->belongsTo(ShippingZone::class, 'zone_id');
    }
}
