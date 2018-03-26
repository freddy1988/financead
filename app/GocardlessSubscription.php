<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GocardlessSubscription extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'id',
		'model_name',
		'amount',
        'created_at_api',
        'currency',
        'day_of_month',
        'end_date',
        'interval',
        'interval_unit',
        'links',
        'metadata',
        'month',
        'name',
        'payment_reference',
        'start_date',
        'status',
        'upcoming_payments',
    ];
}
