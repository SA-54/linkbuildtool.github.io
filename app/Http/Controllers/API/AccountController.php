<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

    /**
     * Display the resource.
     *
     * @return AccountResource|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            return AccountResource::make($user);
        }

        return response()->json([
            'message' => 'Resource not found.',
            'status' => 404
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAccountRequest $request
     * @param int $id
     * @return AccountResource
     */
    public function update(UpdateAccountRequest $request, $id)
    {
        $user = Auth::user();

        $space = Account::where([['id', '=', $id], ['user_id', '=', $user->id]])->firstOrFail();

        $updated = $this->spaceUpdate($request, $space);

        if ($updated) {
            return AccountResource::make($updated);
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

        $space = Account::where([['id', '=', $id], ['user_id', '=', $user->id]])->first();

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
