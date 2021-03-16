<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateSpaceRequest;
use App\Http\Requests\API\UpdateSpaceRequest;
use App\Http\Resources\SpaceResource;
use App\Space;
use App\Traits\SpaceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpaceController extends Controller
{
    use SpaceTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->input('search');
        $sort = ($request->input('sort') == 'asc' ? 'asc' : 'desc');

        return SpaceResource::collection(Space::where('user_id', $user->id)
            ->when($search, function($query) use ($search) {
                return $query->searchName($search);
            })
            ->orderBy('id', $sort)
            ->paginate(config('settings.paginate'))
            ->appends(['search' => $search, 'sort' => $request->input('sort')]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSpaceRequest $request
     * @return SpaceResource|\Illuminate\Http\JsonResponse
     */
    public function store(CreateSpaceRequest $request)
    {
        $created = $this->spaceCreate($request);

        if ($created) {
            return SpaceResource::make($created);
        }

        return response()->json([
            'message' => 'Resource not found.',
            'status' => 404
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return SpaceResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = Auth::user();

        $link = Space::where([['id', '=', $id], ['user_id', $user->id]])->first();

        if ($link) {
            return SpaceResource::make($link);
        }

        return response()->json([
            'message' => 'Resource not found.',
            'status' => 404
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSpaceRequest $request
     * @param int $id
     * @return SpaceResource
     */
    public function update(UpdateSpaceRequest $request, $id)
    {
        $user = Auth::user();

        $space = Space::where([['id', '=', $id], ['user_id', '=', $user->id]])->firstOrFail();

        $updated = $this->spaceUpdate($request, $space);

        if ($updated) {
            return SpaceResource::make($updated);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $space = Space::where([['id', '=', $id], ['user_id', '=', $user->id]])->first();

        if ($space) {
            $space->delete();

            return response()->json([
                'id' => $space->id,
                'object' => 'space',
                'deleted' => true,
                'status' => 200
            ], 200);
        }

        return response()->json([
            'message' => 'Resource not found.',
            'status' => 404
        ], 404);
    }
}
