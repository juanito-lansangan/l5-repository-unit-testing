<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     basePath="/api/v1/",
 *     @SWG\Info(
 *          version="1.0.0",
 *          title="API playground",
 *          description="Laravel for training purposes to improve my laravel skills",
 *         @SWG\Contact(
 *              name="Jplans",
 *              email="juanito.lansangan@gmail.com"
 *         )
 *     ),
 * )
 *
 */
class BaseController extends Controller
{
    use Helpers;

    /**
     * Metadata
     * @var array
     */
    protected $meta;

    /**
     * Header
     * @var array
     */
    protected $headers;

    /**
     * Send response with metadata and data
     * @SWG\Definition(
     *   definition="ResponseModel",
     *   type="object",
     *   required={"status", "self"},
     *   @SWG\Property(
     *        property="meta",
     *        type="object",
     *        required={"status", "self"},
     *        @SWG\Property(
     *             property="status",
     *             type="integer"
     *        ),
     *        @SWG\Property(
     *             property="self",
     *             type="string"
     *        )
     *   ),
     *   @SWG\Property(
     *        property="data",
     *        type="object"
     *   ),
     * )
     *
     * @param     $data
     * @param int $status
     *
     * @return Response
     */
    public function sendResponse($data, $status = Response::HTTP_OK)
    {
        $response = [
            'meta' => $this->createMeta($status),
            'data' => $data,
        ];

        return response($response, $status, $this->getHeaders());
    }

    /**
     * Return metadata with status
     * @SWG\Definition(
     *   definition="MetaModel",
     *   type="object",
     *   required={"status", "self"},
     *   @SWG\Property(
     *        property="status",
     *        type="integer"
     *   ),
     *   @SWG\Property(
     *        property="self",
     *        type="string"
     *   ),
     *   @SWG\Property(
     *       property="code",
     *       type="string"
     *   ),
     *   @SWG\Property(
     *       property="message",
     *       type="string"
     *   ),
     *   @SWG\Property(
     *       property="errors",
     *       type="array"
     *   ),
     *   @SWG\Property(
     *       property="trace",
     *       type="array"
     *   )
     * )
     *
     * @param integer $statusCode
     *
     * @return array headers
     */
    private function createMeta($statusCode)
    {
        $request = request();
        $meta = $this->meta;
        $meta['status'] = $statusCode;
        $meta['self'] = url($request->path());

        return $meta;
    }

    /**
     * Return header data
     * @return array
     */
    private function getHeaders()
    {
        return $this->headers ?? [];
    }

    /**
     * Set header data
     *
     * @param array $headers
     */
    public function setHeaders($headers = [])
    {
        $this->headers = $headers;
    }

    /**
     * Send error response
     * @SWG\Definition(
     *         definition="ErrorModel",
     *         type="object",
     *         required={"status", "message", "code"},
     *         @SWG\Property(
     *             property="meta",
     *             required={"status", "self", "message", "code"},
     *             @SWG\Schema(ref="#/definitions/MetaModel")
     *         )
     *     )
     *
     * @param $message
     * @param $status
     * @param $code
     * @param $errors
     * @param $trace
     *
     * @return Response
     */
    public function sendError($message, $status, $code = 'NAN', $errors = null, $trace = null)
    {
        // single or multiple messages
        $msg = is_array($message) ? 'messages' : 'message';

        // set meta data for error
        $meta = [
            $msg => $message,
            'code' => $code,
            'errors' => $errors,
        ];

        // debug trace if debug is active
        if ($trace) {
            $meta['trace'] = $trace;
        }

        // set meta for response
        $this->setMeta($meta);

        // response data
        $response = [
            'meta' => $this->createMeta($status),
            'messages' => $message,
            'data' => $errors
        ];

        return response($response, $status, $this->getHeaders());
    }

    /**
     * Set metadata
     *
     * @param array $meta
     */
    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * Return validated request
     * returns error if invalid
     *
     * @param Request $request
     * @param array   $rules
     * @param string  $message
     *
     * @return mixed
     */
    public function validateRequest(Request $request, $rules, $message = null)
    {
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            throw new StoreResourceFailedException($message ?: 'Invalid request', $validator->errors());
        }

        return $request->all();
    }

    /**
     * @param $message
     * @param $statusCode
     *
     * @throws Exception
     */
    public function error($message, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $this->response->error($message, $statusCode);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorNotFound($message)
    {
        $this->response->errorNotFound($message);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorBadRequest($message)
    {
        $this->response->errorBadRequest($message);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorForbidden($message)
    {
        $this->response->errorForbidden($message);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorInternal($message)
    {
        $this->response->errorInternal($message);
    }

    /**
     * @param $message
     *
     * @throws Exception
     */
    public function errorUnauthorized($message)
    {
        $this->response->errorUnauthorized($message);
    }
}
