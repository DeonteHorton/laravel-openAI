<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\OpenAI;
use Illuminate\Http\Request;

class OpenAIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OpenAI $openAITable)
    {
        $chats = $openAITable->query()
            ->where('user_id', auth()->id())
            ->get();

        return Inertia::render('Chat', [
            'chats' => $chats
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OpenAI $openAITable)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OpenAI  $openAI
     * @return \Illuminate\Http\Response
     */
    public function show(OpenAI $openAI)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OpenAI  $openAI
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OpenAI $openAI)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OpenAI  $openAI
     * @return \Illuminate\Http\Response
     */
    public function destroy(OpenAI $openAI)
    {
        //
    }
}
