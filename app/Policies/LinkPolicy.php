<?php

namespace App\Policies;

use App\Traits\UserFeaturesTrait;
use App\User;
use App\Link;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class LinkPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any links.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function view(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can create links.
     *
     * @param \App\User $user
     * @param $limit
     * @return mixed
     */
    public function create(User $user, $limit)
    {
        if ($limit == -1) {
            return true;
        } elseif($limit > 0) {
            // Set the count for multi links counter
            $mCount = 0;

            // If the request is for a multi links creation
            if (request()->input('multi_link')) {
                // Get the links
                $links = preg_split('/\n|\r/', request()->input('urls'), -1, PREG_SPLIT_NO_EMPTY);

                // If the request contains more than one link
                if (count(preg_split('/\n|\r/', request()->input('urls'), -1, PREG_SPLIT_NO_EMPTY)) > 1) {

                    // Get the links count, and subtract 1 value, the remaining will be used to emulate the total links count against the limit
                    $mCount = (count($links)-1);
                }
            }

            $count = Link::where('user_id', '=', $user->id)->count();

            // If the total links count (including multi links, if any in the request) exceeds the limits
            if (($count+$mCount) < $limit) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function update(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can delete the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function delete(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can restore the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function restore(User $user, Link $link)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the link.
     *
     * @param  \App\User  $user
     * @param  \App\Link  $link
     * @return mixed
     */
    public function forceDelete(User $user, Link $link)
    {
        //
    }

    public function domains(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function spaces(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function stats(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function disabled(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function geo(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function platform(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function utm(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function password(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function expiration(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function globalDomains(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function deepLinks(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function linkRotation(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }

    public function api(User $user, $limit)
    {
        if ($limit) {
            return true;
        }

        return false;
    }
}
