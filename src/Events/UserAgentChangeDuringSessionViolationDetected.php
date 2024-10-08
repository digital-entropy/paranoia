<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAgentChangeDuringSessionViolationDetected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ?Authenticatable $authenticatable
    ) {
        //
    }
}
