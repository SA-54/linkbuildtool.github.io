<?php


namespace App\Traits;

use App\Plan;
use App\User;
use Illuminate\Support\Facades\Auth;

trait UserFeaturesTrait
{
    /**
     * @param $user
     * @return array
     */
    protected function getFeatures($user)
    {
        $subscriptions = $features = [];
        // Get all the subscriptions the user is currently active on
        if ($user) {
            foreach ($user->subscriptions as $subscription) {
                if (($subscription->recurring() || $subscription->onTrial() || $subscription->onGracePeriod()) && !$subscription->hasIncompletePayment()) {
                    $subscriptions[] = $subscription->name;
                }
            }
        }

        // Get the plans
        $plans = Plan::whereIn('name', $subscriptions)->orWhere([['amount_month', '=', 0], ['amount_year', '=', 0]])->get()->toArray();

        foreach ($plans as $plan) {
            foreach ($plan as $key => $value) {
                if (in_array($key, ['option_api', 'option_links', 'option_spaces', 'option_domains', 'option_stats', 'option_geo', 'option_platform', 'option_expiration', 'option_password', 'option_disabled', 'option_utm', 'option_global_domains', 'option_link_rotation', 'option_deep_links'])) {
                    if (!isset($features[$key])) {
                        $features[$key] = 0;
                    }

                    // If unlimited
                    if ($value == -1) {
                        $features[$key] = $value;
                    }
                    // If the plan option has a value, and is not -1, and is higher than what was previously se
                    elseif ($value > 0 && $features[$key] != -1 && $value > $features[$key]) {
                        $features[$key] = $value;
                    }
                }
            }
        }

        return $features;
    }
}