<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        "id", 
        "full_address", 
        "address_lines",
        "building_name",
        "address_1",
        "address_2",
        "city",
        "post_code",
        "county",
        "country_code",
		"available_from",
		"viewing_arrangement_information",
		"state",
		"type",
		"viewing_via",
		"landlord",
    ];
}
