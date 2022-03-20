<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardStoreRequest;
use App\Http\Requests\CardUpdateRequest;
use App\Http\Resources\CardResource;
use App\Http\Resources\DeskListResource;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return CardResource
     */
    public function store(CardStoreRequest $request)
    {
        $createdDesk = Card::create($request->validated());

        return new CardResource($createdDesk);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return CardResource
     */
    public function show(Card $card)
    {
        return new CardResource($card);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CardUpdateRequest $request
     * @param Card $card
     * @return CardResource
     */
    public function update(CardUpdateRequest $request, Card $card)
    {
        $card->update($request->validated());
        return new CardResource($card);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $card->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
