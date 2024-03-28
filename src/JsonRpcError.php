<?php

namespace Jukit\JsonRpcClient;

class JsonRpcError
{
    /**
     * 使用数值表示该异常的错误类型。 必须为整数。
     * @var int
     */
    private $code;

    /**
     * 对该错误的简单描述字符串。 该描述应尽量限定在简短的一句话。
     * @var string
     */
    private $message;

    /**
     * 包含关于错误附加信息的基本类型或结构化类型。该成员可忽略。 该成员值由服务端定义（例如详细的错误信息，嵌套的错误等）。
     * @var array
     */
    private $data;
}

