<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Link;
use App\Plan;
use App\Space;
use App\Stat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // If the user previously selected a plan
        if (!empty(session('selected_plan'))) {
            return redirect()->route('checkout.index', ['id' => session('selected_plan')['id'], 'period' => session('selected_plan')['period']]);
        }

        $user = Auth::user();

        $links = Link::where('user_id', $user->id)->orderBy('id', 'desc')->limit(10)->get();

        $clicks = Stat::where('user_id', $user->id)->orderBy('created_at', 'desc')->limit(10)->get();

        $plan = Plan::where([['amount_month', '=', 0], ['amount_year', '=', 0]])->first();

        $subscriptions = [];
        // Get all the subscriptions the user is currently active on
        foreach ($user->subscriptions as $subscription) {
            if (($subscription->recurring() || $subscription->onTrial() || $subscription->onGracePeriod()) && !$subscription->hasIncompletePayment()) {
                $subscriptions[] = $subscription;
            }
        }

        return view('dashboard.content', ['user' => $user, 'plan' => $plan, 'links' => $links, 'clicks' => $clicks, 'subscriptions' => $subscriptions]);
    }
}
