<?php

declare(strict_types=1);

namespace Dentro\Paranoia\Securities;

final class SessionSecurity
{
    /**
     * Session security that detects session's IP changes.
     *
     * Reference: OWASP AppSensor SE4
     */
    const string IP_CHANGES = 'ip_changes';

    /**
     * Session security that detects session's location changes.
     *
     * Reference: OWASP AppSensor SE5
     */
    const string LOCATION_CHANGES = 'location_changes';

    /**
     * Session security that detects user agent's changes.
     *
     * Reference: OWASP AppSensor SE6
     */
    const string USER_AGENT_CHANGES = 'user_agent_changes';

    /**
     * Session security that detects session's geo deviation on authentication with whitelist..
     *
     * Reference: OWASP AppSensor AE13
     */
    const string LOCATION_DEVIATION = 'geo_deviation';
}
