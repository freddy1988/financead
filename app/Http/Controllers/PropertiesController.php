<?php

namespace App\Http\Controllers;

use App\Property;
use GuzzleHttp\Client as GuzzleClient;

class PropertiesController extends Controller
{
    public function index()
    {
        $properties = Property::all();

        return view('properties.index', compact('properties'));
    }
    public function refresh(){
    	$replyapi = new GuzzleClient([
        	'base_uri' => 'https://admin.noagent.co.uk/api/v1/',
        	'http_errors' => false,
        ]);
        $campaignsPath = 'public/9MM6IUFV8QMQPAX1LX9Y7TVDSHAKHYDQ/properties';
        try{
        	$response = $replyapi->get($campaignsPath);
            $properties = json_decode(  $response->getBody());
        }catch (RequestException $e) {

        }
        foreach ($properties->data as $key=> $property) {
            $vai = $property->viewing_arrangement_information;
            if(strlen($vai) > 190)
                $vai = substr($vai, 0,186)."...";

            $db_campaign = Property::UpdateOrcreate(
				['id'  => $property->id],
		        [
		          	"full_address" => $property->full_address,
					"address_lines" => $property->address_lines,
					"building_name" => $property->building_name,
					"address_1" => $property->address_1,
					"address_2" => $property->address_2,
					"city" => $property->city,
					"post_code" => $property->post_code,
					"country" => $property->county,
					"country_code" => $property->country_code,
					"available_from" => $property->available_from,
					"viewing_arrangement_information" => $vai,
					"state" => json_encode ($property->state),
					"type" => json_encode ($property->type),
					"viewing_via" => json_encode ($property->viewing_via),
					"landlord" => json_encode ($property->landlord)
		        ]
		     );
	    }
	    return view('admin.refresh');
    }
}