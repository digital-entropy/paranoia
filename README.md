<p align="center">
    <h1 align="center">Paranoia</h1>
    <p align="center">HTTP Session Security for Laravel</p>
    <p align="center">
        <a href="https://github.com/addeeandra/paranoia/actions">
            <img alt="GitHub Workflow Status (main)" src="https://github.com/addeeandra/paranoia/actions/workflows/tests.yml/badge.svg"/>
        </a>
        <a href="https://packagist.org/packages/addeeandra/paranoia">
            <img alt="Latest Stable Version" src="https://img.shields.io/packagist/v/addeeandra/paranoia"/>
        </a>
        <a href="https://packagist.org/packages/addeeandra/paranoia">
            <img alt="Total Downloads" src="https://img.shields.io/packagist/dt/addeeandra/paranoia"/>
        </a>
    </p>
</p>

------

> [!CAUTION]
> Paranoia only support session with `database` driver. We planned to support more drivers if there's any demand for
> that. Please make new issue to request which driver you want to support.

> [!IMPORTANT]
> In session hijacking (TCP hijacking), attackers steal a web user's active session by acquiring their unique session
> ID. This lets them impersonate the user, accessing data or performing actions as if they were the legitimate user.

A minimal effort to prevent session hijacking is 'to not being hijacked' by using secure transport protocol such as
**https**. This package provide extra-layer for Session's security by detecting suspicious changes (user-agent,
location, ip) on user's session.

> **Requires [PHP 8.3+](https://php.net/releases/)**

## Geo Restriction Detection

Preventing unauthorized access based on Geo IP. Our Geo IP check is
leveraging [ipinfo.io](https://ipinfo.io). Geo Restriction is suitable for an application that following a strict
security rules related to **limited-country access** policy such as in government, financial institution, internal
software, and such.

```php
# Register this Middleware
\Dentro\Paranoia\Middlewares\GeoRestrictionMiddleware::class

# Event dispatched by this detection
\Dentro\Paranoia\Events\GeoRestrictionDetected::class
```

Check [OWASP AppSensor](https://owasp.org/www-project-appsensor/) at **RP1: Suspicious or Disallowed User Source
Location** for more information.

## IP Changes During Session Detection

Allowing detection on IP changes during a session. It can be used to preventing unauthorized access by detecting changes
on new IP. Please be aware that changing WiFi or Mobile Data can be detected as a new IP. Use this with full-awareness.

```php
# Register this Middleware
\Dentro\Paranoia\Middlewares\IPChangeRestrictionMiddleware::class

# Event dispatched by this detection
\Dentro\Paranoia\Events\IPChangeDuringSessionViolationDetected::class
```

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

## User-Agent Changes During Session Detection

Allowing detection on user agent changes during a session. This middleware is the same as Location Changes Detection,
but it checks suspicious changes on User-Agent. Can preventing user's session to move between multiple user-agent.

```php
# Register this Middleware
\Dentro\Paranoia\Middlewares\UserAgentChangeRestrictionMiddleware::class

# Event dispatched by this detection
\Dentro\Paranoia\Events\UserAgentChangeDuringSessionViolationDetected::class
```
This feature minimize the **escalation** risk
of [Session Hijacking Attack](https://owasp.org/www-community/attacks/Session_hijacking_attack).

Check [OWASP AppSensor](https://owasp.org/www-project-appsensor/) at **SE6: Change of User Agent Mid Session** for
more information.

## Utilities

> [!CAUTION]
> We planned to split this security utilities into separate package.

### X-Form Header Encoding as Form

This utility prevents plain-text body submission.

```php
# Register this Middleware
\Dentro\Paranoia\Middlewares\FormViaHeaderMiddleware::class
```

The middleware use `X-Form` header, decode `X-Form` as array, and convert the array as request's inputs.

### X-Content-Digest as Secure Handshake

> [!NOTE]
> To be available.

This utility allows secure communication handshake. The middleware use `X-Content-Digest` to validate request's
integrity.

## Configuration

```dotenv
IPINFO_TOKEN=your_ipinfo_token
```
