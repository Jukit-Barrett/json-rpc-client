<?php

namespace Jukit\JsonRpcClient\RpcClient;

use Jukit\JsonRpcClient\Contracts\RpcServiceContract;
use Jukit\JsonRpcClient\Exceptions\RpcClientException;
use Jukit\JsonRpcClient\JsonRpcResponse;
use Jukit\JsonRpcClient\Packer\JsonPacker;

class JsonRpcSocketClient implements RpcServiceContract
{
    protected $socketClient;

    protected $config;

    protected $jsonPacker;

    public function __construct(array $config)
    {
        $this->config = $config;

        $this->jsonPacker = new JsonPacker();
    }

    protected function getJsonPacker(): JsonPacker
    {
        return $this->jsonPacker;
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getSocketClient()
    {
        if (is_null($this->socketClient)) {
            throw new RpcClientException('Socket Client is NULL.');
        }

        return $this->socketClient;
    }

    protected function assertSocket(): self
    {
        $errorCode = socket_last_error();

        if ($errorCode > 0) {
            throw new RpcClientException($errorCode, socket_strerror($errorCode));
        }

        return $this;
    }

    public function connect()
    {
        if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            $this->assertSocket();
        }

        $config = $this->getConfig();

        if (!socket_connect($socket, $config['host'], $config['port'])) {
            $this->assertSocket();
        }

        $this->socketClient = $socket;

        return $this;
    }

    public function reconnect()
    {
        socket_close($this->getSocketClient());

        $this->socketClient = null;

        $this->connect();

        return $this;
    }

    public function read()
    {
        $content               = '';
        $package_length_type   = $this->getConfig()['package_length_type'];
        $package_body_offset   = $this->getConfig()['package_body_offset'];
        $package_length_offset = $this->getConfig()['package_length_offset'];
        $format                = $package_length_type . '1';

        do {
            $buffer = socket_read($this->getSocketClient(), 8192);

            if ($buffer === false || strcmp($buffer, '') == 0) {
                $this->assertSocket();
            }

            $content .= $buffer;

            $lengthPackage = current(unpack($format, $content, $package_length_offset)) + $package_body_offset;
        } while (!($lengthPackage >= strlen($content)));

        return $content;
    }

    public function write(string $jsonEncodeString)
    {
        $length = strlen($jsonEncodeString);

        if (socket_write($this->getSocketClient(), $jsonEncodeString, $length) === false) {
            $this->assertSocket();
        }

        return $this;
    }

    public function waitCall(array $params): JsonRpcResponse
    {
        $jsonEncodeResult = $this->getJsonPacker()->setData($params)->pack();

        $this->write($jsonEncodeResult);

        $content = $this->read();

        $package = $this->getJsonPacker()->setData($content)->unpack();

        return (new JsonRpcResponse())->setJsonRpc($package['jsonrpc'])
            ->setResult($package['result'])
            ->setError($package['error'])
            ->setId($package['id']);
    }

    public function close(): void
    {
        socket_close($this->getSocketClient());
    }
}
