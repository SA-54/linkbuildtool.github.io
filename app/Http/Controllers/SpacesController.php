<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSpaceRequest;
use App\Http\Requests\UpdateSpaceRequest;
use App\Space;
use App\Traits\SpaceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpacesController extends Controller
{
    use SpaceTrait;

    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->input('search');
        $sort = ($request->input('sort') == 'asc' ? 'asc' : 'desc');

        $spaces = Space::where('user_id', $user->id)
            ->when($search, function($query) use ($search) {
                return $query->searchName($search);
            })
            ->orderBy('id', $sort)
            ->paginate(config('settings.paginate'))
            ->appends(['search' => $search, 'sort' => $request->input('sort')]);

        return view('spaces.content', ['view' => 'list', 'spaces' => $spaces]);
    }

    public function spacesNew()
    {
        return view('spaces.content', ['view' => 'new']);
    }

    public function spacesEdit($id)
    {
        $user = Auth::user();

        $space = Space::where([['id', '=', $id], ['user_id', '=', $user->id]])->firstOrFail();

        return view('spaces.content', ['view' => 'edit', 'space' => $space]);
    }

    public function createSpace(CreateSpaceRequest $request)
    {
        $this->spaceCreate($request);

        return redirect()->route('spaces')->with('success', __(':name has been created.', ['name' => $request->input('name')]));
    }

    public function updateSpace(UpdateSpaceRequest $request, $id)
    {
        $user = Auth::user();

        $space = Space::where([['id', '=', $id], ['user_id', '=', $user->id]])->firstOrFail();

        $this->spaceUpdate($request, $space);

        return back()->with('success', __('Settings saved.'));
    }

    public function deleteSpace($id)
    {
        $user = Auth::user();

        $space = Space::where([['id', '=', $id], ['user_id', '=', $user->id]])->firstOrFail();

        $space->delete();

        return redirect()->route('spaces')->with('success', __(':name has been deleted.', ['name' => $space->name]));
    }
}
