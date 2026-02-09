<?php


namespace EncoreDigitalGroup\Atlassian\Helpers;

use EncoreDigitalGroup\StdLib\Exceptions\NullExceptions\ClassPropertyNullException;

class AuthHelper
{
    public static function validate(mixed $context): void
    {
        if (is_null($context->username)) {
            throw new ClassPropertyNullException('username');
        }

        if (is_null($context->token)) {
            throw new ClassPropertyNullException('token');
        }
    }
}