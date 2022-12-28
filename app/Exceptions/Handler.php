<?php
declare(strict_types=1);
namespace App\Exceptions;

use App\Exceptions\AuthenticateException;
use App\Exceptions\ConflictException;
use PDOException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return new JsonResponse([
                'errors' => [$e->errors()]
            ], 400);
        }

        if ($e instanceof AuthenticateException) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], $e->getCode());
        }

        if ($e instanceof ConflictException) {
            return new JsonResponse([
                'error' => "common.message.save-conflict"
            ], 409);
        }

        if ($e instanceof NotFoundHttpException) {
            return new JsonResponse([
                'error' => "common.message.not-found"
            ], 404);
        }

        if ($e instanceof PDOException) {
            return new JsonResponse([
                'error' => "common.message.db-connection.failed"
            ], 500);
        }

        $response = parent::render($request, $e);
        $statusCode = $response->getStatusCode();

        if ($statusCode === Response::HTTP_INTERNAL_SERVER_ERROR) {
            return new JsonResponse([
                'error' => "common.message.system.error"
            ], 500);
        } else {
            // その他エラーについても、メッセージは500と同じにしておく.
            // (今後、ステータス個別のメッセージが必要になれば、処理を分ける)
            return new JsonResponse([
                'error' => "common.message.system.error"
            ], $statusCode);
        }

        return $response;
    }
}
