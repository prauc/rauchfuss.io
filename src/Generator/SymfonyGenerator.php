<?php

namespace App\Generator;

use App\Kernel;

class SymfonyGenerator
{
    public function version() {
        return Kernel::VERSION;
    }
}