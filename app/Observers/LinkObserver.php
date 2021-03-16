<?php

namespace App\Observers;

use App\Link;

class LinkObserver
{
    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Link  $link
     * @return void
     */
    public function deleting(Link $link)
    {
        $link->stats()->delete();
    }
}
