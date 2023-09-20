<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Enums\OpenAIModel;
use Illuminate\Http\Request;
use App\Interfaces\AiInterface;
use Illuminate\Auth\Access\Response;
use App\Models\OpenAI as OpenAITable;

class OpenAIController extends Controller
{
    protected $ai;

    public function __construct(AiInterface $interface)
    {
        $this->ai = $interface;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, OpenAITable $openAITable)
    {
        $models = $this->ai->getModels();

        $data = $models['data'];

        $data = collect($data)->map(function ($model) {
            return $model['id'];
        });

        $data = $data->filter(function ($model) {

            $excludeVals = array("similarity","instruct", "search", "edit", "embedding");
            if(0 < count(array_intersect(array_map('strtolower', explode('-', $model)), $excludeVals))) {

                return false;
            }

            $includeVals = array("code", "text", "001");
            if(1 >= count(array_intersect(array_map('strtolower', explode('-', $model)), $includeVals))) {

                return false;
            }

            return true;
        });

        $data = array_values($data->toArray());

        $data = array_map(function ($modelType) {
            $model = explode('-', $modelType);

            $name = ucfirst($model[1]);
            $type = ucfirst($model[0]);

            $data = [
                'model' => 'text-davinci-003',
                'prompt' => "Explain what the {$name} {$type} model, but keep the explanation very very short. Just to one sentence.",
                'temperature' => 0,
                'max_tokens' => 256
            ];

            $result = $this->ai->create($data);

            return [
                'model' => $modelType,
                'title' => "{$name} {$type} Bot",
                'description' => preg_replace('/\s*\R\s*/', ' ', trim($result['choices'][0]['text']))
            ];
        }, $data);

        $chats = $openAITable->query()
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->user()->id !== auth()->id()) {
            return Response::deny('Unauthorized access to data'); // TODO -> Make a policy
        }

        return Inertia::render('Chat', [
            'chats' => $chats,
            'ai_models' => $data
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
            'prompt' => 'required|string',
            'model' => 'required|string'
        ], [], [
            'prompt' => 'Prompt',
            'model' => 'Model'
        ]);

        $defaultPrompt = 'can you tell me who created you and for what purpose? in a very detailed manner in paragraphs and tell me to ask you something?';

        $authUser = User::find(auth()->id());

        if ($request->user()->id !== $authUser->id) {
            return Response::deny('Unauthorized access to data'); // TODO -> Make a policy
        }


        if ($authUser->aiChats()->whereDate('created_at', Carbon::today())->count() >= $authUser->chat_limit) {
            return redirect()->back()->withErrors([
                'limit_reached' => 'Sorry, but you reached your chat limit for today, come back tomorrow to chat with the AI.'
            ]);
        }

        $data = [
            'model' => $request->input('model', 'text-davinci-001'),
            'prompt' => $request->input('prompt', $defaultPrompt),
            'max_tokens' => 256 * 2
        ];

        $result = $this->ai->create($data);

        $openAITable->create([
            'user_id' => auth()->id(),
            'prompt' => $request->input('prompt', 'What is your purpose?'),
            'model' => $request->input('model', 'text-davinci-001'),
            'answer' => $result['choices'][0]['text']
        ]);

        return Inertia::render('Chat', [
            'response' => $result['choices'][0]['text']
        ]);
    }
}
