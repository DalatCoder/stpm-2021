<?php

namespace Ninja;

use Throwable;

class NinjaException extends \Exception
{
    private int $status_code;
    
    public function __construct($message = "", $status_code = 200, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->status_code = $status_code;
    }
    
    public function get_status_code(): int
    {
        return $this->status_code;
    }
}
