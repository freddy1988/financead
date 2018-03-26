<?php

namespace App\Http\Controllers;

use App\GocardlessPayment;
use App\Tenancy;
use App\YodleeTransaction;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;

class TenanciesController extends Controller
{
	public function index()
    {
		$tenancies = Tenancy::all();
		foreach ($tenancies as $tenancy) {
			$tenancy->err = false;
			$first = YodleeTransaction::where('tenancy_id',$tenancy->id)->max('date');
			$second = GocardlessPayment::where('tenancy_id',$tenancy->id)->max('charge_date');
			if($first>$second){
				$tenancy->last_paid = $first;
				$tenancy->match = 'Yodlee';
			}
			if($second>$first){
				$tenancy->last_paid = $second;
				$tenancy->match = 'GoCardLess';
			}
			$balance = $tenancy->getBalance();
			$tenancy->err = ($balance["strict"] < 0 && $balance["permissive"] < 0);
		}
        return view('tenancies.index', compact('tenancies'));
    }

    public function byDate(Request $request,$date)
    {
        $sd = explode("|",$date);
        $last=false;
        if($sd[0] == "*")
            $last = true;
        else
            $start_date = Carbon::createFromFormat("Y-m-d",$sd[0]);
        $end_date = Carbon::createFromFormat("Y-m-d",$sd[1]);
        if($last)
            $tenancies = Tenancy::query()
                ->where("next_payment_date","<=",$end_date)
                ->orderByRaw("next_payment_date")
                ->get();
        else
            $tenancies = Tenancy::query()
                ->where("next_payment_date",">",$start_date)
                ->where("next_payment_date","<=",$end_date)
                ->orderByRaw("next_payment_date")
                ->get();
//        dd($tenancies);
        foreach ($tenancies as $tenancy) {
            $tenancy->err = false;
            $first = YodleeTransaction::where('tenancy_id',$tenancy->id)->max('date');
            $second = GocardlessPayment::where('tenancy_id',$tenancy->id)->max('charge_date');
            if($first>$second){
                $tenancy->last_paid = $first;
                $tenancy->match = 'Yodlee';
            }
            if($second>$first){
                $tenancy->last_paid = $second;
                $tenancy->match = 'GoCardLess';
            }
            $balance = $tenancy->getBalance();
            $tenancy->err = ($balance["strict"] < 0 && $balance["permissive"] < 0);
        }

        return view('tenancies.index', compact('day'), compact('tenancies'));
    }

    public function edit(Tenancy $tenancy)
    {
        $yodlee_available = YodleeTransaction::where('tenancy_id',null)->pluck('id','id');
        $gocardless_available = GocardlessPayment::where('tenancy_id',null)->pluck('id','id');
        $payments = $tenancy->getPaymets();
        $tenancy->updateBalance();
        return view('tenancies.edit', compact('tenancy','yodlee_available','gocardless_available','payments'));
    }
    public function update(Tenancy $tenancy, Request $request)
    {
        try {
        	if($request->has('yodlee_act')){
	            foreach($request->yodlee_act as $payment){
	            	$yodlee = YodleeTransaction::find($payment);
	                $yodlee->tenancy_id = $tenancy->id;
            		$yodlee->save();
	            }
	        }
	        if($request->has('gocardless_act')){
	            foreach($request->gocardless_act as $payment){
	            	$gocardless = GocardlessPayment::find($payment);
	                $gocardless->tenancy_id = $tenancy->id;
            		$gocardless->save();
	            }
	        }
            flash(trans('messages.success'), 'success');
        } catch (\Exception $e) {
            flash(trans('messages.exception'), 'danger');
        }

        return back();
    }
    public function refresh(){
    	$tenanciesapi = new GuzzleClient([
          'base_uri' => 'https://admin.noagent.co.uk/api/v1/',
          'http_errors' => false,
        ]);
        $tenanciesPath = 'public/9MM6IUFV8QMQPAX1LX9Y7TVDSHAKHYDQ/tenancies?include[0]=property';
        try{
            $response = $tenanciesapi->get($tenanciesPath);
            $tenancies = json_decode(  $response->getBody());
            Tenancy::truncate();
      	}catch (RequestException $e) {

      	}

        foreach ($tenancies->data as $key=> $tenancy) {
        	if($tenancy->state->data->key_name == 'active'){
	            $db_tenancy = Tenancy::create(
					[	'id'  => $tenancy->id,
			          	"total_rent_amount" => $tenancy->total_rent_amount,
						"deposit" => $tenancy->deposit,
						"deposit_reference" => $tenancy->deposit_reference,
						"deposit_holder" => $tenancy->deposit_holder,
						"deposit_type" => $tenancy->deposit_type,
						"deposit_scheme" => $tenancy->deposit_scheme,
						"deposit_status" => $tenancy->deposit_status,
						"rent_payment_reference" => $tenancy->rent_payment_reference,
			            "deposit_payment_reference" => $tenancy->deposit_payment_reference,
			            "holding_deposit_payment_reference" => $tenancy->holding_deposit_payment_reference,
						"deposit_protected_date" => $tenancy->deposit_protected_date,
						"deposit_received_date" => $tenancy->deposit_received_date,
						"start_at" => $tenancy->start_at,
						"end_at" => $tenancy->end_at,
						"is_periodic" => $tenancy->is_periodic,
						"next_payment_date" => $tenancy->next_payment_date,
						"rent_term" => $tenancy->rent_term,
						"statement_term" => $tenancy->statement_term,
						"tenancy_term" => $tenancy->tenancy_term,
						"term" => $tenancy->term,
						"rent_frequency" => $tenancy->rent_frequency,
						"state" => json_encode($tenancy->state),
						"management_type" => json_encode($tenancy->management_type),
						"tenants" => json_encode($tenancy->tenants),
						"term_interval" => json_encode($tenancy->term_interval),
						"rent_frequency_interval" => json_encode($tenancy->rent_frequency_interval),
						"lead_tenant" => json_encode($tenancy->lead_tenant),
						"documents" => json_encode($tenancy->documents),
						"property_full_address" => $tenancy->property->data->full_address,
						"property" => json_encode($tenancy->property->data),
						"match" => null
			        ]
			    );
		    }
	    }
	    /*print 'RESULT<pre>';
        print_r(($tenancies));
        print '</pre>';*/
	    return view('admin.refresh');
    }

}