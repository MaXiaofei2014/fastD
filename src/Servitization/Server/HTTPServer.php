<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Servitization\Server;


use Exception;
use FastD\Http\Response;
use FastD\Http\SwooleServerRequest;
use FastD\Swoole\Server\HTTP;
use Psr\Http\Message\ServerRequestInterface;
use swoole_http_request;
use swoole_http_response;

class HTTPServer extends HTTP
{
    /**
     * @param swoole_http_request $swooleRequet
     * @param swoole_http_response $swooleResponse
     */
    public function onRequest(swoole_http_request $swooleRequet, swoole_http_response $swooleResponse)
    {
        $request = SwooleServerRequest::createServerRequestFromSwoole($swooleRequet);

        try {
            $response = $this->doRequest($request);
            foreach ($response->getHeaders() as $key => $header) {
                $swooleResponse->header($key, $response->getHeaderLine($key));
            }
            foreach ($response->getCookieParams() as $key => $cookieParam) {
                $swooleResponse->cookie($key, $cookieParam);
            }
        } catch (Exception $e) {
            $response = app()->handleException($e);
        }

        app()->shutdown($request, $response);
        $swooleResponse->status($response->getStatusCode());
        $swooleResponse->end((string) $response->getBody());
    }

    /**
     * @param ServerRequestInterface $serverRequest
     * @return Response
     */
    public function doRequest(ServerRequestInterface $serverRequest)
    {
        return app()->handleRequest($serverRequest);
    }
}