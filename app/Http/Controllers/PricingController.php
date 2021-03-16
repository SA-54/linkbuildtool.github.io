<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Plan;
use Illuminate\Support\Facades\Auth;

class PricingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $plans = Plan::where('visibility', 1)->get();

        $domains = [];
        foreach (Domain::where('user_id', '=', 0)->get() as $domain) {
            $domains[] = parse_url($domain->name)['host'];
        }

        return view('pricing.index', ['user' => $user, 'plans' => $plans, 'domains' => $domains]);
    }
}
