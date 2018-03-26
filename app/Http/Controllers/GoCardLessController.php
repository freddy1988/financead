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
            if($payment->description){
                $tenancy_id = Tenancy::select('id')
                ->where('property_full_address', 'LIKE', '%'.$payment->description.'%')
                ->first();      
            }
            if((!empty($payment->links->subscription))&&($tenancy_id==null)){
                $susc = GocardlessSubscription::select('id','metadata')->where('id',$payment->links->subscription)->first();
                $tenancy_id = Tenancy::select('id')->WhereRaw("? LIKE concat('%',rent_payment_reference,'%')", $susc->metadata)->first();
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
                    'tenancy_id' => $tenancy_id ? $tenancy_id->id : null
                ] 
             );
        }
        return view('admin.refresh');
    }
}

