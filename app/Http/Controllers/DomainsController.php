<?php

namespace App\Http\Controllers;

use App\Domain;
use App\Http\Requests\CreateDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Traits\DomainTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainsController extends Controller
{
    use DomainTrait;

    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->input('search');
        $sort = ($request->input('sort') == 'asc' ? 'asc' : 'desc');

        $domains = Domain::where('user_id', $user->id)
            ->when($search, function($query) use ($search) {
                return $query->searchName($search);
            })
            ->orderBy('id', $sort)
            ->paginate(config('settings.paginate'))
            ->appends(['search' => $search, 'sort' => $request->input('sort')]);

        return view('domains.content', ['view' => 'list', 'domains' => $domains]);
    }

    public function domainsNew()
    {
        return view('domains.content', ['view' => 'new']);
    }

    public function domainsEdit($id)
    {
        $user = Auth::user();

        $domain = Domain::where([['id', '=', $id], ['user_id', '=', $user->id]])->firstOrFail();

        return view('domains.content', ['view' => 'edit', 'domain' => $domain]);
    }

    public function createDomain(CreateDomainRequest $request)
    {
        $this->domainCreate($request);

        return redirect()->route('domains')->with('success', __(':name has been created.', ['name' => str_replace(['http://', 'https://'], '', $request->input('name'))]));
    }

    public function updateDomain(UpdateDomainRequest $request, $id)
    {
        $user = Auth::user();

        $domain = Domain::where([['id', '=', $id], ['user_id', '=', $user->id]])->firstOrFail();

        $this->domainUpdate($request, $domain);

        return back()->with('success', __('Settings saved.'));
    }

    public function deleteDomain($id)
    {
        $user = Auth::user();

        $domain = Domain::where([['id', '=', $id], ['user_id', '=', $user->id]])->firstOrFail();

        $domain->delete();

        return redirect()->route('domains')->with('success', __(':name has been deleted.', ['name' => str_replace(['http://', 'https://'], '', $domain->name)]));
    }
}
