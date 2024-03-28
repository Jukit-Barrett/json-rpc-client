<?php

namespace Jukit\JsonRpcClientTest;

use Jukit\JsonRpcClient\Exceptions\JsonRpcClientException;
use Jukit\JsonRpcClient\JsonRpcRequest;
use Jukit\JsonRpcClient\RpcClient\JsonRpcSocketClient;
use Jukit\JsonRpcClient\RpcClient\JsonRpcStreamClient;
use PHPUnit\Framework\TestCase;

class JsonRpcTest extends TestCase
{
    public static function testRun()
    {
        try {
            $conf = [
                'host'           => '127.0.0.1',
                'port'           => '9502',
                'socket_timeout' => 1,
                'flags'          => STREAM_CLIENT_CONNECT,
            ];

            $jsonRpcClient = new JsonRpcStreamClient($conf);

            $jsonRpcClient->connect();

            $call = (new JsonRpcRequest())->setJsonRpc("2.0")
                ->setMethod("/calculator/add")
                ->setParams([800, 900])
                ->setId(uniqid('', true))
                ->setContext([]);

            $content = $jsonRpcClient->waitCall($call->toArray());
            var_dump($content);

            $call = (new JsonRpcRequest())->setJsonRpc("2.0")
                ->setMethod("/calculator/getCustomer")
                ->setParams([110])
                ->setId(uniqid('', true))
                ->setContext([]);

            $content = $jsonRpcClient->waitCall($call->toArray());
            var_dump($content);
        } catch (JsonRpcClientException $e) {
            echo $e->getMessage() . "\r\n";
        }
    }

    public static function testJsonRpcSocketClient()
    {
        $jsonRpcSocketClient = new JsonRpcSocketClient(
            [
                'host'                  => '127.0.0.1',
                'port'                  => '80',
                'package_length_type'   => 'N',
                'package_length_offset' => 0,
                'package_body_offset'   => 4,
            ]
        );

        $jsonRpcSocketClient->connect();

        $params = [
            'jsonrpc' => '2.0',
            'method'  => '/test',
            'params'  => [],
            'id'      => '5fd240b637539',
            'context' => [],
        ];

        $content = $jsonRpcSocketClient->waitCall($params);
        var_dump($content);

        $params = [
            'jsonrpc' => '2.0',
            'method'  => '/customer/getCustomer',
            'params'  => [110],
            'id'      => '5fd240b637539',
            'context' => [],
        ];

        $content = $jsonRpcSocketClient->waitCall($params);
        var_dump($content);

        $jsonRpcSocketClient->close();
    }
}
