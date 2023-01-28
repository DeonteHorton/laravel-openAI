<?php

namespace App\Http\Controllers;

use App\Enums\OpenAIModel;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Auth\Access\Response;
use App\Models\OpenAI as OpenAITable;

class OpenAIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, OpenAITable $openAITable)
    {
        $chats = $openAITable->query()
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->user()->id !== auth()->id()) {
            return Response::deny('Unauthorized access to data'); // TODO -> Make a policy
        }

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
    public function store(Request $request, OpenAITable $openAITable)
    {
        $request->validate([
            'prompt' => 'required|string'
        ], [], [
            'prompt' => 'Prompt'
        ]);

        $authUser = User::find(auth()->id());

        if ($request->user()->id !== $authUser->id) {
            return Response::deny('Unauthorized access to data'); // TODO -> Make a policy
        }

        // if ($authUser->aiChats()->whereDate('created_at', Carbon::today())->count() >= $authUser->chat_limit) {
        //     return redirect()->back()->withErrors([
        //         'limit_reached' => 'Sorry, but you reached your chat limit for today, come back tomorrow to chat with the AI.'
        //     ]);
        // }

        $result = OpenAI::completions()->create([
            'model' => OpenAIModel::davinci_updated,
            'prompt' => $request->prompt,
            'max_tokens' => 256 * 2
        ]);

        $openAITable->create([
            'user_id' => auth()->id(),
            'prompt' => $request->prompt,
            'answer' => $result['choices'][0]['text']
        ]);

        return Inertia::render('Chat', [
            'response' => $result['choices'][0]['text']
        ]);
    }
}
