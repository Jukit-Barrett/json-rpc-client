<?php

namespace Jukit\JsonRpcClient\Contracts;

interface RpcServiceContract
{
    public function reconnect();

    public function connect();

    public function read();

    public function write(string $param);

    public function close();
}
