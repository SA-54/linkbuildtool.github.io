<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Cashier\Subscription as CashierSubscription;

/**
 * Class Subscription
 *
 * @mixin Builder
 * @package App
 */
class Subscription extends CashierSubscription
{
    public function plan()
    {
        return $this->belongsTo('App\Plan', 'name', 'name')->where('name', $this->name)->withTrashed();
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeSearch(Builder $query, $value)
    {
        return $query->where('stripe_id', 'like', '%' . $value . '%');
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeStatus(Builder $query, $value) {
        return $query->where('stripe_status', '=', $value);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopePlan(Builder $query, $value) {
        return $query->where('name', '=', $value);
    }

    /**
     * @param Builder $query
     * @param $value
     * @return Builder
     */
    public function scopeUserId(Builder $query, $value)
    {
        return $query->where('user_id', '=', $value);
    }
}
