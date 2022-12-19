<?php

namespace MBolli\PhpGeobuf;

class GeobufException extends \Exception {
    public function __construct($message = '', $code = 0, \Throwable $previous = null) {
        $message = 'PhpGeobuf: ' . $message;
        parent::__construct($message, $code, $previous);
    }
}
