<?php


namespace App\Traits;

use App\Space;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait SpaceTrait
{
    /**
     * Store a new space
     *
     * @param Request $request
     * @return Space
     */
    protected function spaceCreate(Request $request)
    {
        $user = Auth::user();

        $space = new Space;

        $space->name = $request->input('name');
        $space->user_id = $user->id;
        $space->color = array_key_exists($request->input('color'), formatSpace()) ? $request->input('color') : 1;
        $space->save();

        return $space;
    }

    /**
     * Update the space
     *
     * @param Request $request
     * @param Model $space
     * @return Space|Model
     */
    protected function spaceUpdate(Request $request, Model $space)
    {
        if ($request->has('name')) {
            $space->name = $request->input('name');
        }

        if ($request->has('color')) {
            $space->color = $request->input('color');
        }

        $space->save();

        return $space;
    }
}