<?php

/*
 * Copyright (c) 2024-2025. Encore Digital Group.
 * All Right Reserved.
 */

namespace EncoreDigitalGroup\Atlassian\Services\Jira;

/** @api */
class JiraHelper
{
    public static function getKeyFromSmartLink(string $smartLink): ?string
    {
        $pattern = '/https:\/\/[a-zA-Z0-9.-]+\/browse\/([A-Z]+-\d+)|/';
        $match = preg_match($pattern, $smartLink, $matches);

        if ($match && isset($matches[1])) {
            return $matches[1];
        }

        return null;
    }
}