<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\{Response, JsonResponse, Request};
use Illuminate\Validation\UnauthorizedException;
use GuzzleHttp\Exception\{ClientException, ConnectException, RequestException};
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\{AccessDeniedHttpException,
    BadRequestHttpException,
    ConflictHttpException,
    HttpException,
    MethodNotAllowedHttpException,
    NotFoundHttpException};
use Predis\PredisException;
use App\Modules\Slack;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  \Throwable  $exception
     * @return Response|JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $debug = env('APP_DEBUG');

        $response = parent::render($request, $exception);

        if (!$debug) {
            /**
             * default return object is : server error!!! be careful to catch all exceptions
             */
            $return_object = [
                'data' => [
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => trans('messages.custom.'.Response::HTTP_INTERNAL_SERVER_ERROR),
                    'code' => 101
                ],
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR
            ];

            if ($exception instanceof QueryException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => trans('messages.custom.error.query'),
                        'code' => 102
                    ],
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
            } elseif ($exception instanceof UnauthorizedException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_UNAUTHORIZED,
                        'messages' => trans('messages.custom.error.unauthorized'),
                        'code' => 103
                    ],
                    'status' => Response::HTTP_UNAUTHORIZED
                ];
            } elseif ($exception instanceof BadRequestHttpException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_BAD_REQUEST,
                        'messages' => $exception->getMessage(),
                        'code' => 115
                    ],
                    'status' => Response::HTTP_BAD_REQUEST
                ];
            } elseif ($exception instanceof AccessDeniedHttpException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_FORBIDDEN,
                        'message' => $exception->getMessage(),
                        'code' => 112
                    ],
                    'status' => Response::HTTP_FORBIDDEN
                ];
            } elseif ($exception instanceof PredisException) {
                $return_object = [
                    'data' => [
                        'status' => 503,
                        'message' => trans('messages.custom.error.query'),
                        'code' => 104
                    ],
                    'status' => 503
                ];
            } elseif ($exception instanceof ModelNotFoundException) {
                $model = str_replace('App\Models\\', '', $exception->getModel());
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => trans('messages.custom.error.model_not_found', [
                            'model' => $model
                        ]),
                        'code' => 105
                    ],
                    'status' => Response::HTTP_NOT_FOUND
                ];
            } elseif ($exception instanceof ConflictHttpException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_CONFLICT,
                        'message' => trans('messages.custom.'.Response::HTTP_CONFLICT),
                        'code' => 114
                    ],
                    'status' => Response::HTTP_CONFLICT
                ];
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                        'message' => trans('messages.custom.'.Response::HTTP_METHOD_NOT_ALLOWED),
                        'code' => 106
                    ],
                    'status' => Response::HTTP_METHOD_NOT_ALLOWED
                ];
            } elseif ($exception instanceof RequestRulesException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => trans('messages.custom.'.Response::HTTP_BAD_REQUEST),
                        'fields' => $exception->getFields(),
                        'code' => $exception->getErrorCode()
                    ],
                    'status' => Response::HTTP_BAD_REQUEST
                ];
            } elseif ($exception instanceof NoItemInRequestException) {
                $return_object = [
                    'data' => [
                        'status' => $exception->getCode(),
                        'message' => $exception->getMessage(),
                        'code' => 107
                    ],
                    'status' => $exception->getCode()
                ];
            } elseif ($exception instanceof RequestException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_BAD_REQUEST,
                        'message' => $exception->getMessage(),
                        'code' => 108
                    ],
                    'status' => Response::HTTP_BAD_REQUEST
                ];
            } elseif ($exception instanceof ConnectException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => $exception->getMessage(),
                        'code' => 109
                    ],
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
            } elseif ($exception instanceof ClientException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => $exception->getMessage(),
                        'code' => 110
                    ],
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR
                ];
            } elseif ($exception instanceof NotFoundHttpException) {
                $return_object = [
                    'data' => [
                        'status' => Response::HTTP_NOT_FOUND,
                        'message' => $exception->getMessage(),
                        'code' => 111
                    ],
                    'status' => Response::HTTP_NOT_FOUND
                ];
            }
            // if ($return_object['status'] >= 500) {
            //     $offline = env('OFFLINE');
            //     if ($offline) {
            //         $slack = new Slack();
            //         $slack->sendErrorLog($exception, $request, $response);
            //     } else {
            //         Log::error($exception->getMessage());
            //     }
            // }

            return response()
                ->json($return_object['data'], $return_object['status'])
                ->header('Access-Control-Allow-Origin', '*');
        }

        return $response;
    }

}
