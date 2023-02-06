<?php

namespace App\Enums;

use ReflectionClass;

class OpenAIModel
{
    const DAVINIC_TEXT_BOT = [
        'model' => 'text-davinci-003',
        'description' => 'This is the most capable AI text model in the series, The model can perform a wide variety of tasks'
    ];

    const CURIE_TEXT_BOT = [
        'model' => 'text-curie-001',
        'description' => 'This AI text model is faster than Davinci text model but not as capable.'
    ];

    const BABBAGE_TEXT_BOT = [
        'model' => 'text-babbage-001',
        'description' => 'This AI text model is can perform straight forward tasks and is very fast.'
    ];

    const ADA_TEXT_BOT = [
        'model' => 'text-ada-001',
        'description' => 'This AI model can perform very simple task and is usually the fastest text AI model.'

    ];

    const DAVANIC_CODE_BOT = [
        'model' => 'code-davinci-002',
        'description' => 'This AI Coding model is the most capable AI coding model in the series, The model can understand and generate code and has the ability to understand natural language and turn it into code.'
    ];

    const CUSHMAN_CODE_BOT = [
        'model' => 'code-cushman-001',
        'description' => 'This AI Coding model is almost as cable as the Danvinci Coding model but is slightly faster.'
    ];

    static function getConstants() {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}