<?php

namespace Mmstreet\Common\Exceptions;

use Barryvdh\Cors\Stack\CorsService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Handler extends ExceptionHandler
{
    /**
     * List of URIs for CORS.
     *
     * @var araray
     */
    protected $corsUris = [
        'api/*'
    ];

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        $response = parent::render($request, $e);
        foreach ($this->corsUris as $value) {
            if ($request->is($value)) {
                app(CorsService::class)->addActualRequestHeaders($response, $request);
            }
        }

        return $this->renderInAjax($e, $request, $response);
    }

    public function renderInAjax(Exception $e, $request, $response)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $res               = [];
            $res['status']     = 'error';
            $res['message']    = empty($e->getMessage()) ? $response::$statusTexts[$response->getStatusCode()] : $e->getMessage();
            $res['file']       = $e->getFile();
            $res['line']       = $e->getLine();
            $res['statusCode'] = $response->getStatusCode();
            $res['statusText'] = $response::$statusTexts[$response->getStatusCode()];
            $res['error']      = $res;

            return $response->setContent($res);
        } else {
            $whoops = (new Run())->pushHandler(new PrettyPageHandler);

            return $whoops->handleException($e);
        }
    }
}
