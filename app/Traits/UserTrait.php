<?php


namespace App\Traits;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

trait UserTrait
{
    /**
     * Update the user
     *
     * @param Request $request
     * @param Model $user
     * @param null $admin
     * @return User|Model
     */
    protected function userUpdate(Request $request, Model $user, $admin = null)
    {
        $user->name = $request->input('name');
        $user->timezone = $request->input('timezone');

        if ($user->email != $request->input('email')) {
            // If email registration site setting is enabled and the request is not from the Admin Panel
            if (config('settings.registration_verification') && $admin == null) {
                // Send send email validation notification
                $user->newEmail($request->input('email'));
            } else {
                $user->email = $request->input('email');
            }
        }

        if ($admin) {
            $user->role = $request->input('role');

            // Update the password
            if (!empty($request->input('password'))) {
                $user->password = Hash::make($request->input('password'));
            }

            // Update the email verified status
            if ($request->input('email_verified_at')) {
                $user->markEmailAsVerified();
            } else {
                $user->email_verified_at = null;
            }
        }

        $user->save();

        return $user;
    }
}