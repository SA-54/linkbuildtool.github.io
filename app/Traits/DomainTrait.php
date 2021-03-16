<?php


namespace App\Traits;

use App\Domain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait DomainTrait
{
    /**
     * Store a new domain
     *
     * @param Request $request
     * @param null $admin
     * @return Domain
     */
    protected function domainCreate(Request $request, $admin = null)
    {
        $user = Auth::user();

        $domain = new Domain;

        $domain->name = config('settings.short_protocol') . '://' . parse_url($request->input('name'))['host'];
        $domain->user_id = isset($admin) ? 0 : $user->id;
        $domain->index_page = $request->input('index_page');
        $domain->not_found_page = $request->input('not_found_page');
        $domain->save();

        return $domain;
    }

    /**
     * Update the domain
     *
     * @param Request $request
     * @param Model $domain
     * @return Domain|Model
     */
    protected function domainUpdate(Request $request, Model $domain)
    {
        if ($request->has('index_page')) {
            $domain->index_page = $request->input('index_page');
        }

        if ($request->has('not_found_page')) {
            $domain->not_found_page = $request->input('not_found_page');
        }

        $domain->save();

        return $domain;
    }
}