<p align="center">
    <h1 align="center">Paranoia</h1>
    <p align="center">Laravel Security Compliance for Stateful Session</p>
    <p align="center">
        <a href="https://github.com/addeeandra/paranoia/actions"><img alt="GitHub Workflow Status (main)" src="https://github.com/addeeandra/paranoia/actions/workflows/tests.yml/badge.svg"/></a>
    </p>
</p>

------
This package provides an **app-level security** that abundantly securing your Laravel project.

> **Requires [PHP 8.3+](https://php.net/releases/)**

## Geo Restriction Detection

Preventing unauthorized access based on Geo IP. Our Geo IP check is
leveraging [ipinfo.io](https://ipinfo.io). Geo Restriction is suitable for an application that following a strict
security rules related to **limited-country access** policy such as in government, financial institution, internal
software, and such.

```php
# Register this Middleware
\Addeeandra\Paranoia\Middlewares\GeoRestrictionMiddleware::class

# Event dispatched by this detection
\Addeeandra\Paranoia\Events\GeoRestrictionDetected::class
```

Check [OWASP AppSensor](https://owasp.org/www-project-appsensor/) at **RP1: Suspicious or Disallowed User Source
Location** for more information.

## IP Changes During Session Detection

Allowing detection on IP changes during a session. It can be used to preventing unauthorized access by detecting changes
on new IP. Please be aware that changing WiFi or Mobile Data can be detected as a new IP. Use this with full-awareness.

```php
# Register this Middleware
\Addeeandra\Paranoia\Middlewares\IPChangeRestrictionMiddleware::class

# Event dispatched by this detection
\Addeeandra\Paranoia\Events\IPChangeDuringSessionViolationDetected::class
```

## User Agent Changes during Session Detection

Allowing detection on user agent changes during a session. This middleware is the same as Location Changes Detection,
but it checks suspicious changes on User-Agent. Can preventing user's session to move between multiple user-agent.

```php
# Register this Middleware
\Addeeandra\Paranoia\Middlewares\UserAgentChangeRestrictionMiddleware::class

# Event dispatched by this detection
\Addeeandra\Paranoia\Events\UserAgentChangeDuringSessionViolationDetected::class
```

Check [OWASP AppSensor](https://owasp.org/www-project-appsensor/) at **SE6: Change of User Agent Mid Session** for
more information.

## Locations Changes During Session Detection

> [!NOTE]
> Under active development.

Allowing detection on location changes during a session. This middleware is suitable for hardening session's security
after it being leaked. It can be used to preventing unauthorized access of a leaked session by detecting changes on
suspicious new IP location.

This feature minimize the **escalation** risk
of [Session Hijacking Attack](https://owasp.org/www-community/attacks/Session_hijacking_attack).
Check [OWASP AppSensor](https://owasp.org/www-project-appsensor/) at **SE5: Source Location Changes During Session** for
more information.

## Client-Server HTTP Integrity with Digest

> [!NOTE]
> To be available.

Allowing secure communication between client and server using HTTP Header. This feature enable request
message's integrity on request and on receive with customized digest algorithm.

## Configuration

```dotenv
IPINFO_TOKEN=your_ipinfo_token
```
