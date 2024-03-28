<?php

namespace Jukit\JsonRpcClient\Packer;

use Jukit\JsonRpcClient\Contracts\PackerContract;

class JsonPacker implements PackerContract
{
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function setData($data) : JsonPacker
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function pack() : string
    {
        $data = json_encode($this->getData(), JSON_UNESCAPED_UNICODE);

        return pack('N', strlen($data)) . $data;
    }

    public function unpack()
    {
        $data = substr($this->getData(), 4);
        if ( !$data) {
            return null;
        }

        return json_decode($data, true);
    }
}
