<?php

namespace Jukit\JsonRpcClient\RpcClient;

use Jukit\JsonRpcClient\Exceptions\JsonRpcClientException;
use Jukit\JsonRpcClient\Exceptions\RpcClientException;
use Jukit\JsonRpcClient\JsonRpcResponse;

class JsonRpcStreamClient
{
    protected $socketClient;

    /**
     * @var array
     */
    protected $config;

    public function __construct(array $config)
    {
        if (!extension_loaded('sockets')) {
            throw new RpcClientException("Missing sockets extension.");
        }

        $this->config = $config;
    }

    protected function getConfig(): array
    {
        return $this->config;
    }

    protected function getAddress(string $host, int $port)
    {
        if (filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $address = "tcp://{$host}:{$port}";
        } else {
            $address = "tcp://[{$host}]:{$port}";
        }

        return $address;
    }

    public function getSocketClient()
    {
        if (is_null($this->socketClient)) {
            throw new JsonRpcClientException('Please connect socket.');
        }

        return $this->socketClient;
    }

    public function connect()
    {
        $config = $this->getConfig();

        $address = $this->getAddress($config['host'], $config['port']);

        $this->socketClient = stream_socket_client($address, $errno, $errstr, $config['socket_timeout'], $config['flags']);

        if ($errno != 0) {
            throw new JsonRpcClientException($errstr, $errno);
        }
    }

    protected function receive(): array
    {
        $content = fread($this->getSocketClient(), 8192);

        return $this->decode($content);
    }

    public function waitCall(array $pkg): JsonRpcResponse
    {
        $encodePkg = $this->encode($pkg);

        $written = fwrite($this->getSocketClient(), $encodePkg . "\r\n");

        if ($written === false) {
            throw new JsonRpcClientException('fwrite error');
        }

        $package = $this->receive();

        return (new JsonRpcResponse())->setJsonRpc($package['jsonrpc'])
            ->setResult($package['result'])
            ->setError($package['error'])
            ->setId($package['id']);
    }

    public function encode(array $pkg): string
    {
        $encodePkg = json_encode($pkg, JSON_UNESCAPED_UNICODE);

        if (json_last_error()) {
            throw new JsonRpcClientException(json_last_error_msg());
        }

        return $encodePkg;
    }

    public function decode(string $pkg): array
    {
        $pkg = json_decode($pkg, true);

        if (json_last_error()) {
            throw new JsonRpcClientException(json_last_error_msg());
        }

        return $pkg;
    }
}
