<?php

namespace App\Enums;

use ReflectionClass;

class OpenAIModel
{
    const DAVINIC_TEXT_BOT = 'text-davinci-003';

    const CURIE_TEXT_BOT = 'text-curie-001';

    const BABBAGE_TEXT_BOT = 'text-babbage-001';

    const ADA_TEXT_BOT = 'text-ada-001';

    const DAVANIC_CODE_BOT = 'code-davinci-002';

    const CUSHMAN_CODE_BOT = 'code-cushman-001';

    static function getConstants() {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}