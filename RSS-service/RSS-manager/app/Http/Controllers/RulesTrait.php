<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Exceptions\RequestRulesException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\{
    Feed\FeedManagementController,
    Feed\Item\ItemManagementController,
    Story\StoryController};
use Illuminate\Validation\Rule;

trait RulesTrait
{
    protected static array $feed_filters = [
        'title',
        'tags',
        'date'
    ];
    protected static array $item_filters = [
        'is_favorite',
        'is_bookmarekd',
        'is_read'
    ];
    protected static array $job_filters = [
        'date'
    ];
    protected static string $top = 'numeric';
    protected static string $skip = 'numeric';
    protected static array $header_validation = [
        'x-user-id' => 'required|uuid'
    ];

    public static function rules(): array
    {
        return [
            
            FeedManagementController::class => [
                'index' =>  [
                    '$filter' => 'array',
                    '$filter.*.left' => [
                        Rule::in(self::$feed_filters)
                    ],
                    '$top' => self::$top,
                    '$skip' => self::$skip
                ],
                'show' => [
                    'feed_id' => 'required|numeric'
                ],
                'store' => [
                    'url' => 'string',
                    'tags' => 'array',
                    'title' => 'string',
                    'description' => 'string'
                ],
                'update' => [
                    'feed_id' => 'required|numeric',
                    'url' => 'string',
                    'tags' => 'array',
                    'title' => 'string',
                    'describtion' => 'string'
                ],
                'destroy' => [
                    'feed_id' => 'required|numeric'
                ]
            ],
            ItemManagementController::class => [
                'index' =>  [
                    '$filter' => 'array',
                    '$filter.*.left' => [
                        Rule::in(self::$item_filters)
                    ],
                    '$top' => self::$top,
                    '$skip' => self::$skip
                ],
                'show' => [
                    'item_id' => 'required|numeric'
                ],
                'update' => [
                    'item_id' => 'required|numeric',
                    'is_favorite' => 'boolean',
                    'is_bookmarekd' => 'boolean',
                    'is_read' => 'boolean',
                    'comments' => 'array',
                ],
                'destroy' => [
                    'item_id' => 'required|numeric'
                ]
                ],
            StoryController::class => [
                'index' =>  [
                    '$filter' => 'array',
                    '$filter.*.left' => [
                        Rule::in(self::$item_filters)
                    ],
                    '$top' => self::$top,
                    '$skip' => self::$skip
                ]
            ]
            
        ];
    }

    /**
     * @throws ValidationException
     * @throws RequestRulesException
     */
    public static function checkRules($data, $function, $code): array
    {

        $controller = __CLASS__;
        if (is_object($data)) {
            $validation = Validator::make(
                $data->all(),
                array_merge(self::rules()[$controller][$function], self::$header_validation)
            );
        } else {
            $validation = Validator::make(
                $data,
                array_merge(self::rules()[$controller][$function], self::$header_validation)
            );
        }
        if ($validation->fails()) {
            if (in_array('x-user-id', array_keys($validation->failed()))) {
                throw new UnauthorizedException();
            } else {
                throw new RequestRulesException($validation->errors()->getMessages(), $code);
            }
        }
        return $validation->validated();
    }
}
