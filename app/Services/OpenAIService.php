<?php

namespace App\Services;

use App\Interfaces\AiInterface;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAIService implements AiInterface
{
    public function getModels()
    {
        return OpenAI::models()->list();
    }

    public function create($data)
    {
        return OpenAI::completions()->create($data);
    }
}
