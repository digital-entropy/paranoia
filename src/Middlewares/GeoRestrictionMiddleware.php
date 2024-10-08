<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Middlewares;

use Dentro\Paranoia\Events\GeoRestrictionViolationDetected;
use Dentro\Paranoia\Paranoia;
use Dentro\Paranoia\Services\IpInfo;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class GeoRestrictionMiddleware
{
    public function __construct(protected Paranoia $paranoia) {}

    /**
     * @throws GuzzleException
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        if (! $this->paranoia->shouldCheckGeoRestriction()) {
            return $next($request);
        }

        $country = trim(IpInfo::getCountry($request->ip()));
        $allowed = $this->getAllowedGeoLocation();

        if (is_array($allowed) && ! in_array($country, $allowed) && ! in_array('*', $allowed)) {
            event(GeoRestrictionViolationDetected::class, auth()->guard()->user());
            $this->actionWhenNotAllowed();
        }

        if (is_string($allowed) && $country !== $allowed && $allowed !== '*') {
            event(GeoRestrictionViolationDetected::class, auth()->guard()->user());
            $this->actionWhenNotAllowed();
        }

        return $next($request);
    }

    protected function actionWhenNotAllowed(): void
    {
        abort(403);
    }

    /**
     * @return array<string>|string|null
     */
    protected function getAllowedGeoLocation(): array|string|null
    {
        /** @var array<string>|string|null */
        return config('paranoia.geo.allowed', '*');
    }
}
