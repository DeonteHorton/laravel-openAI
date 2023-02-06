<?php

namespace App\Enums;

use ReflectionClass;

class OpenAIModel
{
    const DAVINIC_LATEST = 'text-davinci-003';

    const CURIE = 'text-curie-001';

    const BABBAGE = 'text-babbage-001';

    const ADA = 'text-ada-001';

    const DAVANIC_OLD = 'text-davinic-002';

    const CUSHMAN = 'text-cushman-001';

    static function getConstants() {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}