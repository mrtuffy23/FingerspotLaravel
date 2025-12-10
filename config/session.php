<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Session Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default session "driver" that will be used to
    | store session data. Laravel supports a variety of storage options to
    | persist session data. Database, file, cookie drivers are available.
    |
    */

    'driver' => env('SESSION_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Session Lifetime
    |--------------------------------------------------------------------------
    |
    | Here you may specify the number of minutes that will be allowed to
    | elapse before the user is asked to re-authenticate before using the
    | application. This uses the activity timestamp in the cookie.
    |
    */

    'lifetime' => env('SESSION_LIFETIME', 120),

    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Session Cookie Name
    |--------------------------------------------------------------------------
    |
    | Here you may change the name of the cookie used to identify a session
    | instance with your application. This can be changed at any time to give
    | the cookie a different name.
    |
    */

    'cookie' => env('SESSION_COOKIE', 'PHPSESSID'),

    /*
    |--------------------------------------------------------------------------
    | Session Path
    |--------------------------------------------------------------------------
    |
    | The path for which the cookie will be regarded as available. Typically
    | this will be the root path of your application but you are free to
    | change this when you have websites nested within each other.
    |
    */

    'path' => env('SESSION_PATH', '/'),

    /*
    |--------------------------------------------------------------------------
    | Session Domain
    |--------------------------------------------------------------------------
    |
    | Here you may change the domain of the cookie that is used to identify a
    | session in your application. This will determine which domains the cookie
    | is available to in your application. A sensible default has been set.
    |
    */

    'domain' => env('SESSION_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Session Files Storage Path
    |--------------------------------------------------------------------------
    |
    | When using the file driver for sessions, files will be stored at this
    | location. This location is not used when using other session drivers.
    |
    */

    'files' => storage_path('framework/sessions'),

    /*
    |--------------------------------------------------------------------------
    | HTTPS Only Cookies
    |--------------------------------------------------------------------------
    |
    | By setting this option to true, session cookies will only be sent back
    | to the server if the browser has a HTTPS connection. This will keep
    | the cookie from being sent to you if it can not be done securely.
    |
    */

    'secure' => env('SESSION_SECURE_COOKIES', false),

    /*
    |--------------------------------------------------------------------------
    | HTTP Access Only
    |--------------------------------------------------------------------------
    |
    | Setting this value to true will restrict access to the session cookie
    | to HTTP requests only. You will not be able to access the session via
    | JavaScript. This is the safest option but may be inconvenient.
    |
    */

    'http_only' => env('SESSION_HTTP_ONLY', true),

    /*
    |--------------------------------------------------------------------------
    | Same-Site Cookies
    |--------------------------------------------------------------------------
    |
    | This option determines how your cookies behave when cross-site requests
    | take place, and can be used to mitigate CSRF attacks. By default, we
    | will set this value to "lax" since this is a secure default value.
    |
    | Supported: "lax", "strict", "none", null
    |
    */

    'same_site' => env('SESSION_SAME_SITE', 'lax'),

    /*
    |--------------------------------------------------------------------------
    | Session Encryption
    |--------------------------------------------------------------------------
    |
    | This option allows you to easily specify that all of your session data
    | should be encrypted before it is stored. All encryption will be run
    | automatically by Laravel and you can use the data in the sessions.
    |
    */

    'encrypt' => env('SESSION_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Lottery Configuration
    |--------------------------------------------------------------------------
    |
    | When a user session expires, Laravel will sweep the session storage
    | for old sessions. The "lottery" configuration tells Laravel when to do
    | this. By default, 2 out of every 100 requests will trigger a sweep.
    |
    */

    'lottery' => [2, 100],

];
