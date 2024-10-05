<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Events;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GeoRestrictionViolationDetected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ?Authenticatable $authenticatable
    ) {
        //
    }
}
