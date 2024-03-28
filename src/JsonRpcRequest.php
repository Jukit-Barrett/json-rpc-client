<?php

namespace Jukit\JsonRpcClient;

class JsonRpcRequest
{
    /**
     * 指定JSON-RPC协议版本的字符串，必须准确写为“2.0”
     * @var string
     */
    private $jsonRpc = '2.0';

    /**
     * 包含所要调用方法名称的字符串，以rpc开头的方法名，用英文句号（U+002E or ASCII 46）连接的为预留给rpc内部的方法名及扩展名，且不能在其他地方使用。
     * @var string
     */
    private $method = '';

    /**
     * 调用方法所需要的结构化参数值，该成员参数可以被省略。
     * @var array
     */
    private $params = [];

    /**
     * 没有包含“id”成员的请求对象为通知， 作为通知的请求对象表明客户端对相应的响应对象并不感兴趣，本身也没有响应对象需要返回给客户端。服务端必须不回复一个通知，包含那些批量请求中的。
     * 由于通知没有返回的响应对象，所以通知不确定是否被定义。同样，客户端不会意识到任何错误（例如参数缺省，内部错误）。
     *
     * 已建立客户端的唯一标识id，值必须包含一个字符串、数值或NULL空值。如果不包含该成员则被认定为是一个通知。该值一般不为NULL[1]，若为数值则不应该包含小数[2]。
     * @var int
     */
    private $id = 0;

    /**
     * @var array 上下文
     */
    private $context = [];

    public function getJsonRpc(): string
    {
        return $this->jsonRpc;
    }

    public function setJsonRpc(string $jsonRpc): self
    {
        $this->jsonRpc = $jsonRpc;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): self
    {
        $this->params = $params;

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

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'      => $this->id,
            'jsonrpc' => $this->jsonRpc,
            'method'  => $this->method,
            'params'  => $this->params,
            'context' => $this->context,
        ];
    }

}
