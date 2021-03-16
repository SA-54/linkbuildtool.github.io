<?php

namespace App\Http\Middleware;

use App\Traits\UserFeaturesTrait;
use Closure;
use Illuminate\Support\Facades\Auth;

class APIGuardMiddleware
{
    use UserFeaturesTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        $userFeatures = $this->getFeatures($user);

        if ($user->cannot('api', ['App\Link', $userFeatures['option_api']])) {
            return response()->json([
                'message' => __('You don\'t have access to this feature.'),
                'status' => 403
            ], 403);
        }

        return $next($request);
    }
}
