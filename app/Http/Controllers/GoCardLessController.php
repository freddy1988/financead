<?php

namespace App\Http\Controllers;

use App\GocardlessPayment;
use App\GocardlessSubscription;
use App\Tenancy;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoCardLessController extends Controller
{
    /**
     * Show the users page.
     *
     * @return \Illuminate\Http\Response
     * @throws \InvalidArgumentException
     */
    public function index()
    {   
 	    $carbon=Carbon::now()->addDays(-90);
        $payments = GocardlessPayment::where("charge_date",">=",$carbon->format("Y-m-d"))->with('tenancy')->orderBy('charge_date',"desc")->get();
        return view('gocardless.index', compact('payments'));
    }
    public function deleteTenancy(GocardlessPayment $gocardless)
    {
        try {
            $gocardless->tenancy_id = null;
            $gocardless->save();
            flash(trans('messages.success'), 'success');
        } catch (\Exception $e) {
            flash(trans('messages.exception'), 'danger');
        }
        return back();
    }
    public function edit(GocardlessPayment $gocardless)
    {   
        $tenancies = Tenancy::pluck('id','id');
        return view('gocardless.edit', compact('gocardless','tenancies'));
    }
    public function update(GocardlessPayment $gocardless, Request $request)
    {
        try {
            $gocardless->tenancy_id = $request->get('tenancy');
            $gocardless->save();
            flash(trans('messages.success'), 'success');
        } catch (\Exception $e) {
            flash(trans('messages.exception'), 'danger');
        }

        return redirect(route('gocardless.index'));
    }
    public function refresh(){
        //subscriptions
        $all_subscriptions = [];
        $subscriptions = gocardless()->subscriptions()->list([
          "params" => ["limit" => "500"]
        ]);
        foreach ($subscriptions->records as $customer)
            $all_subscriptions [] = $customer;
        while(!empty($subscriptions->after)){
            $subscriptions = gocardless()->subscriptions()->list([
              "params" => ["limit" => "500","after" => $subscriptions->after]
            ]);
            foreach ($subscriptions->records as $customer)
                $all_subscriptions [] = $customer;
        }
        //payments
        $all_payments = [];
        $payments = gocardless()->payments()->list([
          "params" => ["limit" => "500"]
        ]);
        foreach ($payments->records as $customer)
            $all_payments [] = $customer;
        while(!empty($payments->after)){
            $payments = gocardless()->payments()->list([
              "params" => ["limit" => "500","after" => $payments->after]
            ]);
            foreach ($payments->records as $customer)
                $all_payments [] = $customer;
        }
        /*print 'RESULT<pre>';
        print_r(($all_payments));
        print '</pre>';*/

        foreach ($all_subscriptions as $subscription) {
            $db_gocardless_subscriptions = GocardlessSubscription::UpdateOrcreate(
                [   "id"  => $subscription->id],
                [
                    'model_name' => $subscription->model_name,
                    'amount' => $subscription->amount,
                    'created_at_api' => $subscription->created_at,
                    'currency' => $subscription->currency,
                    'day_of_month' => $subscription->day_of_month,
                    'end_date' => $subscription->end_date,
                    'interval' => $subscription->interval,
                    'interval_unit' => $subscription->interval_unit,
                    'links' => json_encode ($subscription->links),
                    'metadata' => json_encode ($subscription->metadata),
                    'month' => $subscription->month,
                    'name' => $subscription->name,
                    'payment_reference' => $subscription->payment_reference,
                    'start_date' => $subscription->start_date,
                    'status' => $subscription->status,
                    'upcoming_payments' => json_encode ($subscription->upcoming_payments)
                ]
             );
        }

        foreach ($all_payments as $payment) {
            $tenancy_id = null;
            $payType=null;
            if($payment->description){
                $tenancy_id = Tenancy::select('id')
                ->where('property_full_address', 'LIKE', '%'.$payment->description.'%')
                ->first();
                if($tenancy_id)
                    $payType="property_full_address";
            }
            if((!empty($payment->links->subscription))&&($tenancy_id==null)){
                $susc = GocardlessSubscription::select('id','metadata')->where('id',$payment->links->subscription)->first();
                $tenancy_id = Tenancy::select('id')->WhereRaw("? LIKE concat('%',rent_payment_reference,'%')", $susc->metadata)->first();
                if($tenancy_id)
                    $payType="subscription";
            }
            $db_gocardless_payments = GocardlessPayment::UpdateOrcreate(
                [   "id"  => $payment->id],
                [
                    'model_name' => $payment->model_name,
                    'amount' => $payment->amount,
                    'amount_refunded' => $payment->amount_refunded,
                    'charge_date' => $payment->charge_date,
                    'created_at_api' => $payment->created_at,
                    'currency' => $payment->currency,
                    'description' => $payment->description,
                    'links' => !empty($payment->links->subscription) ? $payment->links->subscription : null,
                    'metadata' => json_encode ($payment->metadata),
                    'reference' => $payment->reference,
                    'status' => $payment->status,
                    'tenancy_id' => $tenancy_id ? $tenancy_id->id : null,
                    'pay_type' => $payType
                ]
             );
        }


        return $this->localRefresh();
    }

    public function localRefresh(){

        $collection = GocardlessPayment::query()
            ->whereNotNull("tenancy_id")
            ->whereNull("pay_type")
            ->with("tenancy")
            ->get();

        $fields = ["property_full_address",
            ];

        foreach ($collection as $item) {
            $tenancy = $item->tenancy;
            if ($tenancy){
                foreach ($fields as $field) {
                    $value = strtolower($tenancy["$field"]);
                    if (!$value||empty($value)) {
                        continue;
                    }
                    $description = strtolower($item->description);
                    if (!$description||empty($description)) {
                        continue;
                    }
                    for ($i = 0; $i < 2; $i++) {
                        try{

                            if (strpos($description, $value) || strpos($value, $description)) {
                                $item->pay_type = $field;
                                break;
                            }
                        }catch (\Exception $exception){
                            dd($description,$value,$item,$tenancy);
                        }
                        $value = str_replace("-", "", $value);
                    }
                }
                if (!$item->pay_type && !empty($item->links)){
                    $susc = GocardlessSubscription::select('id','metadata')->where('id',$item->links)->first();
                    $tenancy_id = Tenancy::select('id')->WhereRaw("? LIKE concat('%',rent_payment_reference,'%')", $susc->metadata)->first();
                    if($tenancy_id)
                        $item->pay_type="subscription";
                }
            }

            if (!$item->pay_type) {
                $item->pay_type = "manual";
            }
            $item->update();

        }
        return view('admin.refresh');
    }
}

