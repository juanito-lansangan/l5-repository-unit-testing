<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Http\Controllers\BaseController;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionServiceProvider extends ServiceProvider
{
    use Helpers;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app('Dingo\Api\Exception\Handler')->register(function (HttpException $exception) {
            return $this->exceptionLogic($exception);
        });
    }


    private function exceptionLogic(\Exception $exception)
    {

        $message = $exception->getMessage();
        $stack = null;
        $errors = null;

        if ($exception instanceof UnauthorizedHttpException) {
            $message = $exception->getHeaders()['WWW-Authenticate'] ?? $message;
        }

        if ($exception instanceof ResourceException) {
            $errors = $exception->getErrors();
        }

        if (config('api.debug')) {
            $stack = [
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'class' => get_class($exception),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        $baseController = new BaseController();
        if (method_exists($exception, 'getHeaders')) {
            $baseController->setHeaders($exception->getHeaders() ?? []);
        }

        if (method_exists($exception, 'getStatusCode')) {
            $status = $exception->getStatusCode();
        } else {
            $status = 500;
        }

        return $baseController->sendError($message, $status, md5(uniqid()), $errors, $stack);
    }
}
