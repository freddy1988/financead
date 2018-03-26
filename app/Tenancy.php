<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Tenancy extends Model
{
    public function payments_yodlee()
    {
        return $this->hasMany('App\YodleeTransaction');
    }

    public function payments_gocardless()
    {
        return $this->hasMany('App\GocardlessPayment');
    }

    public function updateBalance()
    {
        $now = Carbon::now();
        $fiscal_start = Carbon::createFromTimestamp(strtotime(env("FISCAL_START", "01/06/2018")));
        $fiscal_end = Carbon::createFromTimestamp(strtotime(env("FISCAL_END", "04/05/2018")));
        $contract_start = Carbon::createFromTimestamp(strtotime($this->start_at));
        $useOverflow = Carbon::shouldOverflowMonths();

        if(!$this->next($now)){
            $this->fiscal = [];
            return;
        }
        $payments = $this->getPaymets();
        Carbon::useMonthsOverflow(false);

        // Calculate total paid in current fiscal period
        $balance = 0;
        $balance_permissive = 0;
        foreach ($payments as $payment) {
            $paydate = Carbon::createFromTimestamp(strtotime($payment["date"]));
            if (
                $paydate->timestamp >= $fiscal_start->timestamp &&
//                $paydate->timestamp < $now->timestamp &&
                $paydate->timestamp < $fiscal_end->timestamp
            ) {
                $mount = preg_replace('/,/','',$payment['amount']);

                if($payment["status"] == "success")
                    $balance += number_format($mount,2,'.','');
                if($payment["status"] == "success" || $payment["status"] == "pending")
                    $balance_permissive += number_format($mount,2,'.','');
            }
        }
        //end


        // Getting first period to pay and build object for view
        $current = $contract_start->copy();
        $intervals = [];
        while ($current->timestamp < $now->timestamp
            && $current->timestamp < $fiscal_end->timestamp
        ) {
            if ($current->timestamp >= $fiscal_start->timestamp) {
                if (!isset($this->start_at_period))
                    $this->start_at_period = $current->toDateString();

                if (isset($this->start_at_period)) {
                    $balance += -number_format($this->total_rent_amount,2,'.','');
                    $balance_permissive += -number_format($this->total_rent_amount,2,'.','');

                    $status = ($balance >= 0 ? 1 : (abs($balance) < $this->total_rent_amount ? 0 : -1));
                    $paid_amount = ($status==0?($this->total_rent_amount + $balance):($status==1?$this->total_rent_amount:0));
                    $intervals[] = [
                        'status' => $status,
                        'date' => $current->toDateString(),
                        'amount' => number_format($paid_amount,2,'.',',')
                    ];
                }
            }
            $current = $this->next($current);
        }
        //end
        Carbon::useMonthsOverflow($useOverflow);
        $this->balance = $balance;
        $this->balance_permissive = $balance_permissive;
        $this->fiscal = $intervals;
    }

    public function getPaymets(){
        $payments = [];
        $payments_yodlee = $this->payments_yodlee()->get();
        $payments_gocardless = $this->payments_gocardless()->get();
        foreach ($payments_yodlee as $payment){
            $payments[] = [
                'id' => $payment->id,
                'amount' => number_format(json_decode($payment->amount)->amount,2,'.',','),
                'currency ' => json_decode($payment->amount)->currency,
                'date' => $payment->date,
                'origin' => "Yodlee",
                'status' => "success"
            ];
        }
        foreach ($payments_gocardless as $payment){
            $payments[] = [
                'id' => $payment->id,
                'amount' => number_format($payment->amount/100,2,'.',','),
                'currency ' => $payment->currency,
                'date' => $payment->charge_date,
                'origin' => "GoCardLess",
                'status' => $payment->getParsedStatus()
            ];
        }
        return $payments;
    }

    public function getBalance(){
        if(!isset($this->balance) || !isset($this->balance_permissive))
           $this->updateBalance();
        return [
            "strict" => $this->balance,
            "permissive" => $this->balance_permissive
        ];
    }

    /**
     * tiene un pago cerca de la fecha a buscar
     * @param Carbon $now
     * @param $payments
     */
    private function contains(Carbon $now, $payments)
    {

        foreach ($payments as $payment) {
            $dif = $now->getTimestamp() - $payment->getTimestamp();
            $dif = $dif / (3600 * 24);
            if ($this->validate($dif))
                return true;
        }
        return false;
    }

    /**
     * Valida si la diferencia entre el pago para q corresponda a una renta
     * @param $diff
     * @return bool
     */
    private function validate($diff)
    {
        switch ($this->rent_term) {
            case '1 quarter':
            case '10 months':
            case '12 months':
            case '2 months':
            case '3 months':
            case '6 months':
                return $diff > -30 && $diff < 30;
            case 'Fortnightly':
            case 'Monthly':
            case 'Weekly':
                return $diff > -5 && $diff < 5;
        }
        return false;
    }


    /**
     * Devuelve si esta para esta semana y en que dia de la semana le tocaria
     * @param $days dias entre la fecha actual y la maxima fecha a buscar
     * @return dia en el que le tocaria false si no le toca esta semana
     */
    public function hasToPay($days)
    {
        $now=Carbon::now();
        $lastDay=Carbon::now()->addDays($days);
        $contract_start = Carbon::createFromTimestamp(strtotime($this->start_at));

//        while($contract_start->)
        switch ($this->rent_term) {
            case '1 quarter':

            case '10 months':
            case '12 months':
            case '2 months':
            case '3 months':
            case '6 months':
//                return $diff > -30 && $diff < 30;
            case 'Fortnightly':
            case 'Weekly':
//                return $diff > -5 && $diff < 5;
            case 'Monthly':
            default:
                return false;
//                $contract_start->day >=$now->day
        }
        return false;
    }


    private function next(Carbon $start, $index = 1)
    {
        switch ($this->rent_term) {
            case '1 quarter':
                return $start->copy()->addMonths($index * 4);
                break;
            case '10 months':
                return $start->copy()->addMonths($index * 10);
                break;
            case '12 months':
                return $start->copy()->addYear($index);
                break;
            case '2 months':
                return $start->copy()->addMonths($index * 2);
                break;
            case '3 months':
                return $start->copy()->addMonths($index * 3);
            case '6 months':
                return $start->copy()->addMonths($index * 6);
            case 'Fortnightly':
                return $start->copy()->addDays($index * 15);
            case 'Monthly':
                return $start->copy()->addMonth($index);
            case 'Weekly':
                return $start->copy()->addWeek($index);
            default:
                return null;
        }
        return null;
    }

    protected $fillable = [
        "id",
        "total_rent_amount",
        "deposit",
        "deposit_reference",
        "deposit_holder",
        "deposit_type",
        "deposit_scheme",
        "deposit_status",
        "rent_payment_reference",
        "deposit_payment_reference",
        "holding_deposit_payment_reference",
        "deposit_protected_date",
        "deposit_received_date",
        "start_at",
        "end_at",
        "is_periodic",
        "next_payment_date",
        "rent_term",
        "statement_term",
        "tenancy_term",
        "term",
        "rent_frequency",
        "state",
        "management_type",
        "tenants",
        "term_interval",
        "rent_frequency_interval",
        "lead_tenant",
        "documents",
        "property_full_address",
        "property",
        "match",

    ];
}


