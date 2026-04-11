<?php

namespace App\Exceptions\Api;

use RuntimeException;

/**
 * Thrown by AuthService when email is unknown OR password does not match.
 *
 * Both cases produce the same exception (and same HTTP message in the
 * controller) so we never leak which one failed — see BR-LOGIN-1.
 */
class InvalidCredentialsException extends RuntimeException
{
}
