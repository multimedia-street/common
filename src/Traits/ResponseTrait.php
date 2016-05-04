<?php

namespace Mmstreet\Common\Traits;

use Illuminate\Support\Facades\Request;

trait ResponseTrait
{
    /**
     * Create a data response.
     *
     * @param  string $status  success|failed
     * @param  string $message Message of the response
     * @param  array  $data    Data of the response
     *
     * @return array
     */
    protected function createDataResponse($status = 'success', $message = 'Successful', array $data = [])
    {
        $response = [];
        $response['status'] = $status;
        $response['message'] = $message;
        if (! empty($data)) {
            $response['data'] = $data;
        }
        $response[$status] = $response;

        return $response;
    }

    /**
     * Return a response in Json.
     *
     * @param  string    $status
     * @param  string    $message
     * @param  obj|array $data
     * @param  integer   $statusCode
     * @param  array     $headers
     * @param  string    $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseInJson($status, $message, $data, $statusCode = 200, $headers = [], $callback = 'callback')
    {
        return response()->jsonp(\Request::get($callback), $this->createDataResponse($status, $message, $data), $statusCode, $headers);
    }

    /**
     * Return a response in View.
     *
     * @param  obj|array        $data
     * @param  integer|string   $viewNameOrstatusCode View name or a status code
     *
     * @return \Illuminate\Http\Response|\Illuminate\Routing\ResponseFactory
     */
    public function responseInView($data, $viewNameOrstatusCode = 200)
    {
        if (is_int($viewNameOrstatusCode)) {
            if (view()->exists('errors.' . $viewNameOrstatusCode)) {
                return response()->view('errors.' . $viewNameOrstatusCode, ['data' => $data]);
            } else {
                return response($data, $viewNameOrstatusCode);
            }
        }
        if (view()->exists($viewNameOrstatusCode)) {
            return response()->view($viewNameOrstatusCode, ['data' => $data]);
        }

        return response($data);
    }

    /**
     * Return a SUCCESS response.
     *
     * @param  string|\Closure  $message
     * @param  array            $data
     * @param  integer          $statusCode
     * @param  string           $viewName
     * @param  array            $headers
     * @param  string           $callback
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Routing\ResponseFactory|\Closure
     */
    public function successResponse($message, $data = [], $statusCode = 200, $viewName = 'data', $headers = [], $callback = 'callback')
    {
        if ($message instanceof \Closure) {
            return $message();
        }

        return $this->__return('success', $message, $data, $statusCode, $viewName, $headers, $callback);
    }

    /**
     * Return an ERROR Response.
     *
     * @param  string|\Closure  $message
     * @param  array            $data
     * @param  integer          $statusCode
     * @param  integer          $viewName
     * @param  array            $headers
     * @param  string           $callback
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Routing\ResponseFactory|\Closure
     */
    public function errorResponse($message, $data = [], $statusCode = 500, $viewName = 500, $headers = [], $callback = 'callback')
    {
        if ($message instanceof \Closure) {
            return $message();
        }

        return $this->__return('error', $message, $data, $statusCode, $viewName, $headers, $callback);
    }

    /**
     * Return a response either view or json.
     *
     * @param  string    $status
     * @param  string    $message
     * @param  obj|array $data
     * @param  integer   $statusCode
     * @param  string    $viewName
     * @param  array     $headers
     * @param  string    $callback
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\Routing\ResponseFactory
     */
    private function __return($status, $message, $data, $statusCode = 200, $viewName = 'data', $headers = [], $callback = 'callback')
    {
        if (Request::ajax() || Request::wantsJson() || Request::isJson() || Request::acceptsJson()) {
            return $this->responseInJson($status, $message, $data, $statusCode, $headers, $callback);
        }

        return $this->responseInView(collect(['message' => $message, 'data' => $data]), $viewName);
    }
}
