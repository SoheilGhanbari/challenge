<?php

namespace App\Http\Services;


use App\Models\Story;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class StoryService
{
    /**
     * Get all stories service
     *
     * @param array $data
     * @param string $user_id
     *
     * @return array
     */
    public static function index(array $data, string $user_id): array
    {
        $take = $data['$top'] ?? env('TAKE');
        $skip = $data['$skip'] ?? env('SKIP');
        $filter = $data['$filter'] ?? [];

        $stories = Story::indexStory($filter, $user_id, $take, $skip);
        return $stories;
    }
}