<?php

declare(strict_types=1);

namespace App\Virtual\Requests;

/**
 * @OA\Schema(
 *      title="Login User request",
 *      description="Login user request body data",
 *      type="object",
 *      required={"email", "password"}
 * )
 */
class AccessRequest
{
    /**
     * @OA\Property(
     *      title="email",
     *      description="User email",
     *      example="taylor@gmail.com"
     * )
     *
     * @var string
     */
    public string $email;

    /**
     * @OA\Property(
     *      title="password",
     *      description="User password",
     *      example=12345678
     * )
     *
     * @var string
     */
    public string $password;
}
