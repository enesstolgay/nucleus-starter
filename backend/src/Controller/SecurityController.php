<?php

namespace App\Controller;

class SecurityController
{
    public function check(): void
    {
        throw new \LogicException('This method should not be reached — json_login handles authentication.');
    }
}
