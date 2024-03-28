# PHP JSON RPC Client

## 项目简介

PHP JSON RPC Client 是一个用于与远程 JSON-RPC 服务进行通信的 PHP 库。

它采用轻量级的远程过程调用（RPC）协议，通过 TCP/IP 传输协议进行通信，使用 JSON（JavaScript Object Notation）作为数据交换格式。

旨在为用户提供在 PHP 应用中与远程 JSON-RPC 服务进行通信的简单而灵活的解决方案。

用户可以轻松地与远程服务进行交互，发送请求并获取响应，实现远程方法调用等功能。

PHP JSON RPC Client 是一个功能简单、性能优越的 PHP 客户端库，适用于各种 PHP 项目中与远程 JSON-RPC 服务进行通信的场景，为用户提供了一种简单可靠的解决方案。

## 功能特点

1. 基于流式传输：提供了高效可靠的通信机制，适用于各种网络环境下的数据传输需求。
2. 灵活配置：支持用户根据实际需求配置主机地址、端口、超时时间等参数。
3. 异常处理：对可能发生的异常情况进行了充分考虑和处理，提供了友好的异常信息和错误提示，保证了通信过程的稳定性和可靠性。
4. 简单易用：提供了简洁明了的 API 接口，用户无需深入了解 JSON-RPC 协议的细节。
5. 高性能：采用了高效的数据编码和解码方式，提高了通信效率，保证了客户端的高性能和稳定性。

## Socket & Stream 客户端

PHP JSON-RPC Socket & Stream 客户端是一个用于与远程 JSON-RPC 服务进行通信的 PHP 库，支持 Socket 和 Stream 两种不同的通信方式。

用户可以根据自己的需求选择适合的通信方式进行远程方法调用。

1. Socket 通信：通过 Socket 通信方式与远程 JSON-RPC 服务进行交互，实现远程方法调用等功能。
2. Stream 通信：通过 Stream 通信方式与远程 JSON-RPC 服务进行交互，实现远程方法调用等功能。
3. 连接管理：支持连接的建立、断开和重连等操作，保证了通信过程的稳定性和可靠性。

## 安装

````shell
composer require jukit/json-rpc-client
````

## 接入指南

````php
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
````

````php
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
````



