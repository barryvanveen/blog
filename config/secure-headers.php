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
        'enable' => false,
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
                    'o46NCqFsO7R0miT5YPCnL6OdhXUWWYGouIovNwHR6k8=', // js vars admin
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
