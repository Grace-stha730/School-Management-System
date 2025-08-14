<?php

namespace App\Exceptions\Repository;

use Exception;

class RepositoryException extends Exception
{
    public function __construct($message = "Repository operation failed", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
