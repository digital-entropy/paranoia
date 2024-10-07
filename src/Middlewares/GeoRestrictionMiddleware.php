<?php

declare(strict_types=1);

namespace Addeeandra\Paranoia\Middlewares;

use Addeeandra\Paranoia\Events\GeoRestrictionViolationDetected;
use Addeeandra\Paranoia\Services\IpInfo;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class GeoRestrictionMiddleware
{
    /**
     * @throws GuzzleException
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $country = trim(IpInfo::getCountry($request->ip()));
        $allowed = $this->getAllowedGeoLocation();

        if (is_array($allowed) && ! in_array($country, $allowed) && ! in_array('*', $allowed)) {
            event(GeoRestrictionViolationDetected::class, $request->getUser());
            $this->actionWhenNotAllowed();
        }

        if (is_string($allowed) && $country !== $allowed && $allowed !== '*') {
            event(GeoRestrictionViolationDetected::class, $request->getUser());
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
