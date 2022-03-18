<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeskStoreRequest;
use App\Http\Resources\DeskResource;
use App\Models\Desk;
use Illuminate\Http\Response;
use function response;

class DeskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return DeskResource::collection(Desk::with('lists')->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DeskStoreRequest $request
     * @return DeskResource
     */
    public function store(DeskStoreRequest $request)
    {
        $createdDesk = Desk::create($request->validated());

        return new DeskResource($createdDesk);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return DeskResource
     */
    public function show($id)
    {
        return new DeskResource(Desk::with('lists')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DeskStoreRequest $request
     * @param  Desk  $desk
     * @return Desk
     */
    public function update(DeskStoreRequest $request, Desk $desk)
    {
        $desk->update($request->validated());
        return $desk;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Desk $desk)
    {
        $desk->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
