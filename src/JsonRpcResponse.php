<?php

namespace Jukit\JsonRpcClient;

class JsonRpcResponse
{
    /**
     * 指定JSON-RPC协议版本的字符串，必须准确写为“2.0”
     * @var string
     */
    private $jsonRpc = '2.0';

    /**
     * 该成员在成功时必须包含。
     * 当调用方法引起错误时必须不包含该成员。
     * 服务端中的被调用方法决定了该成员的值。
     * @var array
     */
    private $result = [];

    /**
     * 该成员在失败是必须包含。
     * 当没有引起错误的时必须不包含该成员。
     * 该成员参数值必须为5.1中定义的对象。
     * @var string
     */
    private $error = '';

    /**
     * 该成员必须包含。
     * 该成员值必须于请求对象中的id成员值一致。
     * 若在检查请求对象id时错误（例如参数错误或无效请求），则该值必须为空值。
     * @var int
     */
    private $id = 0;

    public function getJsonRpc(): string
    {
        return $this->jsonRpc;
    }

    public function setJsonRpc(string $jsonRpc): self
    {
        $this->jsonRpc = $jsonRpc;

        return $this;
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function setResult(array $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function setError(string $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'      => $this->id,
            'jsonrpc' => $this->jsonRpc,
            'error'   => $this->error,
            'result'  => $this->result,
        ];
    }

}
