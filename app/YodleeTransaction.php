<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YodleeTransaction extends Model
{
    protected $fillable = [
        "id",
		"container",
		"amount",
		"baseType",
		"categoryType",
		"categoryId",
		"category",
		"categorySource",
		"createdDate",
		"lastUpdated",
		"description",
		"type",
		"subType",
		"isManual",
		"date",
		"transactionDate",
		"postDate",
		"status",
		"accountId",
		"runningBalance",
		"highLevelCategoryId",
		"tenancy_id",
    ];

    public function getParsedStatus()
    {
        $_status = [
            "POSTED" => "success"
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
        $status = !isset($this->tenancy_id)?$status['pending']:$status[$this->getParsedStatus()];
        return !isset($status)?$status['failed']:$status;
    }

    public function tenancy()
    {
        return $this->belongsTo('App\Tenancy');
    }
}