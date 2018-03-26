<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GocardlessPayment extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'id',
		'model_name',
		'amount',
        'amount_refunded',
        'charge_date',
        'created_at_api',
        'currency',
        'description',
        'links',
        'metadata',
        'reference',
        'status',
        'tenancy_id',
    ];

    public function getParsedStatus()
    {
        $_status = [
            "confirmed" => "success",
            "paid_out" => "success",
            "submitted" => "pending",
            "pending_customer_approval" => "pending",
            "pending_submission" => "pending",
            "cancelled" => "failed",
            "charged_back" => "failed",
            "failed" => "failed",
        ];

        return isset($_status[$this->status]) ? $_status[$this->status] : $this->status;
    }

    public function getViewStatus(){
        $status = [
            "success" => [
                "icon" => "check",
                "color" => "success"
            ],
            "pending" => [
                "icon" => "clock-o",
                "color" => "warning"
            ],
            "failed" => [
                "icon" => "warning",
                "color" => "danger"
            ]
        ];
        $status = !isset($this->tenancy_id)?$status['failed']:$status[$this->getParsedStatus()];
        return !isset($status)?$status['failed']:$status;
    }

    public function tenancy()
    {
        return $this->belongsTo('App\Tenancy');
    }

}
