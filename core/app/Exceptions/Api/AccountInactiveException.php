<?php

namespace App\Exceptions\Api;

use RuntimeException;

/**
 * Thrown when a user exists and password matches but status != active (1).
 */
class AccountInactiveException extends RuntimeException
{
}
