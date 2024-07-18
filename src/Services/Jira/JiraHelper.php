<?php

/*
 * Copyright (c) 2024. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira;

/** @api */
class JiraHelper
{
    public static function getKeyFromSmartLink(string $smartLink): ?string
    {
        $pattern = '/https:\/\/[a-zA-Z0-9.-]+\/browse\/([A-Z]+-\d+)|/';

        if (preg_match($pattern, $smartLink, $matches)) {
            return $matches[1];
        }

        return null;
    }
}