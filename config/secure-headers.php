<?php

declare(strict_types=1);

return [

    /*
     * Server
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Server
     *
     * Note: when server is empty string, it will not add to response header
     */

    'server' => '',

    /*
     * X-Content-Type-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     *
     * Available Value: 'nosniff'
     */

    'x-content-type-options' => 'nosniff',

    /*
     * X-Download-Options
     *
     * Reference: https://msdn.microsoft.com/en-us/library/jj542450(v=vs.85).aspx
     *
     * Available Value: 'noopen'
     */

    'x-download-options' => 'noopen',

    /*
     * X-Frame-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     *
     * Available Value: 'deny', 'sameorigin', 'allow-from <uri>'
     */

    'x-frame-options' => 'sameorigin',

    /*
     * X-Permitted-Cross-Domain-Policies
     *
     * Reference: https://www.adobe.com/devnet/adobe-media-server/articles/cross-domain-xml-for-streaming.html
     *
     * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */

    'x-permitted-cross-domain-policies' => 'none',

    /*
     * X-Power-By
     *
     * Note: it will not add to response header if the value is empty string.
     */

    'x-power-by' => '',

    /*
     * X-XSS-Protection
     *
     * Reference: https://blogs.msdn.microsoft.com/ieinternals/2011/01/31/controlling-the-xss-filter
     *
     * Available Value: '1', '0', '1; mode=block'
     */

    'x-xss-protection' => '1; mode=block',

    /*
     * Referrer-Policy
     *
     * Reference: https://w3c.github.io/webappsec-referrer-policy
     *
     * Available Value: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
     *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
     */

    'referrer-policy' => 'no-referrer',

    /*
     * Clear-Site-Data
     *
     * Reference: https://w3c.github.io/webappsec-clear-site-data/
     */

    'clear-site-data' => [
        'enable' => false,
    ],

    /*
     * HTTP Strict Transport Security
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security
     *
     * Please ensure your website had set up ssl/tls before enable hsts.
     */

    'hsts' => [
        'enable' => env('SECURITY_HEADER_HSTS_ENABLED', false),

        'max-age' => 15552000,

        'include-sub-domains' => false,

        'preload' => true,
    ],

    /*
     * Expect-CT
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Expect-CT
     */

    'expect-ct' => [
        'enable' => false,
    ],

    /*
     * Permissions Policy
     *
     * Reference: https://w3c.github.io/webappsec-permissions-policy/
     */
    'permissions-policy' => [
        'enable' => true,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/accelerometer
        'accelerometer' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/ambient-light-sensor
        'ambient-light-sensor' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/autoplay
        'autoplay' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/battery
        'battery' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/camera
        'camera' => [
            'none' => true,
        ],

        // https://www.chromestatus.com/feature/5690888397258752
        'cross-origin-isolated' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/display-capture
        'display-capture' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/document-domain
        'document-domain' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/encrypted-media
        'encrypted-media' => [
            'none' => true,
        ],

        // https://wicg.github.io/page-lifecycle/#execution-while-not-rendered
        'execution-while-not-rendered' => [
            'none' => true,
        ],

        // https://wicg.github.io/page-lifecycle/#execution-while-out-of-viewport
        'execution-while-out-of-viewport' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/fullscreen
        'fullscreen' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/geolocation
        'geolocation' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/gyroscope
        'gyroscope' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/magnetometer
        'magnetometer' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/microphone
        'microphone' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/midi
        'midi' => [
            'none' => true,
        ],

        // https://drafts.csswg.org/css-nav-1/
        'navigation-override' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/payment
        'payment' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/picture-in-picture
        'picture-in-picture' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/publickey-credentials-get
        'publickey-credentials-get' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/screen-wake-lock
        'screen-wake-lock' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/sync-xhr
        'sync-xhr' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/usb
        'usb' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/web-share
        'web-share' => [
            'none' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Feature-Policy/xr-spatial-tracking
        'xr-spatial-tracking' => [
            'none' => true,
        ],
    ],

    /*
     * Content Security Policy
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP
     */

    'csp' => [
        'enable' => env('SECURITY_HEADER_CSP_ENABLED', false),

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy-Report-Only
        'report-only' => false,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/block-all-mixed-content
        'block-all-mixed-content' => true,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/upgrade-insecure-requests
        'upgrade-insecure-requests' => false,

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/base-uri
        'base-uri' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/form-action
        'form-action' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/img-src
        'img-src' => [
            'self' => true,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/navigate-to
        'navigate-to' => [
            'unsafe-allow-redirects' => false,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/prefetch-src
        'prefetch-src' => [
            'self' => true,
        ],

        // https://w3c.github.io/webappsec-trusted-types/dist/spec/#integration-with-content-security-policy
        'require-trusted-types-for' => [
            'script' => false,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/script-src
        'script-src' => [
            'self' => true,

            'unsafe-inline' => false,

            'unsafe-eval' => false,

            // https://www.w3.org/TR/CSP3/#unsafe-hashes-usage
            'unsafe-hashes' => false,

            'hashes' => [
                'sha256' => [
                    // local
                    'b8ZAFQsPNeXTK3ntWbCANqgmWHLqLL42L8+seMMNI2g=', // js vars
                    '/37ooHxLANUJ+by05dPK/V4LKVfyTQNS14KyEcD0ZME=', // js vars admin
                    // production
                    'qLSUMcy6v/z0vk9gaTvMEtAn7ISgeVBx9X7/2Ikt1rg=', // js vars
                    'UyftXeKYBypxjGmD9JjGRcVJsmA0xblM5XcJMFUqhVI=', // js vars admin
                ],
            ],
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/style-src
        'style-src' => [
            'self' => true,

            'unsafe-inline' => false,

            'unsafe-eval' => false,
        ],

        // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Content-Security-Policy/object-src
        'object-src' => [
            'none' => true,
        ],
    ],
];
