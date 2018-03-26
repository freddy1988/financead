<?php

namespace App\Http\Controllers;

use App\GocardlessPayment;
use App\Tenancy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\Boolean;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $today = Carbon::now()->hour(0)->minute(0)->second(0);
        $days = $this->getWeekDays($today, env("HOLYDAYS_FORCE_LOCAL"), true);
        $limit = $days[count($days) - 1]->format("Y-m-d");
        $tenancies = Tenancy::query()
            ->select("id", "next_payment_date")
            ->where("next_payment_date", "<=", $limit)
            ->orderByRaw("next_payment_date")
            ->get();

        $i = 0;
        $yesterday = Carbon::yesterday();
        array_unshift($days, $yesterday);

        for ($c = 0; $c < count($days); $c++) {
            $ispending = $yesterday->timestamp == $days[$c]->timestamp;
            $key = ($ispending) ? "pendings" : $days[$c]->format("l d");
            $week[$key] = [
                "count" => 0,
                "end_date" => $days[$c]->format("Y-m-d"),
                "start_date" => $c==0?"*":$days[$c-1]->format("Y-m-d")
            ];
            for (; $i < count($tenancies); $i++) {
                $tenancy = $tenancies[$i];
                if (!isset($tenancy->next_payment_date))
                    continue;
                $tdate = Carbon::createFromFormat("Y-m-d", $tenancy->next_payment_date);
                if ($tdate->timestamp > $days[$c]->copy()->hour(23)->minute(59)->second(59)->timestamp)
                    break;
                else $week[$key]["count"]++;
            }
        }
        //if ($week["pendings"]["count"] <= 0)
            array_shift($week);

        $failure_payments = GocardlessPayment::query()
            ->where("status", "cancelled")
            ->orWhere("status", "charged_back")
            ->orWhere("status", "failed")
            ->orderBy("charge_date","desc")
            ->limit(10)
            ->get();
        return view('home', compact('week'), compact("failure_payments"));
    }

    public function getWeekDays(Carbon $start_date, $force_local = false)
    {
        $filename = env("HOLYDAYS_FILENAME", "holydays.json");
        $json = null;
        try {
            $json = file_get_contents($filename);
            $json = json_decode($json, true);
        } catch (\Exception $e) {
            // CREATE FILE IF NOT EXIST
        }
        if (!$force_local) {
            $json_last_updated = Carbon::createFromFormat("Y-m-d", $json["last_updated"]);
            if ($json_last_updated->diffInDays($start_date, true) >= 365) {
                $url = env("HOLYDAYS_API_URL", "http://www.work-day.co.uk/api.php") .
                    "?key=" . env("HOLYDAYS_API_KEY", "NONE") .
                    "&command=analyse" .
                    "&start_date=" . $start_date->format("Y") . "-01-01" .
                    "&end_date=" . $start_date->format("Y") . "-12-31" .
                    //"&holidays=010100100101011111".
                    "&weekend=100001";
                try {
                    $json_result = file_get_contents($url);
                    $json_result = json_decode($json, true);
                } catch (\Exception $e) {
                }
                if ($json_result) {
                    $json = [
                        "holydays" => $json_result["result"]["public_holydays"],
                        "last_updated" => $start_date->format("Y-m-d")
                    ];
                    file_put_contents($filename, json_encode($json));
                }
            };
        }
        $days = [];
        $holydays = array_keys($json["holydays"]);
        $current = $start_date->copy();
        for ($i = 0; $i < 6; $i++) {
            while ($current->isWeekend() || in_array($current->format("Y-m-d"), $holydays))
                $current->addDay();
            $days[] = $current->copy();
            $current->addDay();
        }
        return $days;
    }

}
