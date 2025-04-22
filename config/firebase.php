<?php

declare(strict_types=1);

return [
    /*
     * ------------------------------------------------------------------------
     * Default Firebase project
     * ------------------------------------------------------------------------
     */

    'default' => env('FIREBASE_PROJECT', 'app'),

    /*
     * ------------------------------------------------------------------------
     * Firebase project configurations
     * ------------------------------------------------------------------------
     */

    'projects' => [
        'app' => [

            /*
             * ------------------------------------------------------------------------
             * Credentials / Service Account
             * ------------------------------------------------------------------------
             *
             * In order to access a Firebase project and its related services using a
             * server SDK, requests must be authenticated. For server-to-server
             * communication this is done with a Service Account.
             *
             * If you don't already have generated a Service Account, you can do so by
             * following the instructions from the official documentation pages at
             *
             * https://firebase.google.com/docs/admin/setup#initialize_the_sdk
             *
             * Once you have downloaded the Service Account JSON file, you can use it
             * to configure the package.
             *
             * If you don't provide credentials, the Firebase Admin SDK will try to
             * auto-discover them
             *
             * - by checking the environment variable FIREBASE_CREDENTIALS
             * - by checking the environment variable GOOGLE_APPLICATION_CREDENTIALS
             * - by trying to find Google's well known file
             * - by checking if the application is running on GCE/GCP
             *
             * If no credentials file can be found, an exception will be thrown the
             * first time you try to access a component of the Firebase Admin SDK.
             *
             */

            'credentials' => [
                'type' => "service_account",
                'project_id' => "purple-card-enduser",
                'private_key_id' => "909c9d1d75540c8715192aac7bfc1947ccbea0dd",
                'private_key' => "-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDdHk+jWLXVmIXU\nndljequxFQiwP7DbZEZIyUhwPb9GK4iJ+vlxRZL1gh6kK0L25dZZsacSwsdEfBxq\nfT1L17NtdT+KnbryytR7AIG+tlElRtfyRK/N6bC8fFSBa8hAzf337uaIFapi0w+1\nsNvXyFrGIDK4N9feQ+Uy1nsYfpLBxbcCJX+O6Zfx3BKXjVEA9Oywo9SHMBpsLPx3\nc2SGGr4DrGYRU2GThGRWdoU5Htt97PQiMTjjH4IZUU3JIjI23EpKeBR0Nyc5hc/a\nvVoh7a6PxoKNkKo3akvSlfeZZJNcdBZjE7o1I9fpQ218t2fRGbc67gtqPlcI3Czk\nG6pCZ3ELAgMBAAECggEAQOXbk+K+g7FfXjbyDet8Jt44jZoz60B0DHrfQuoskIjI\na075FVDJQDtviNmVrudoUSz+D1iRqb0PjgWyDueBJxgpjSKcMxRq5qsQaYwTJvCz\nAs08GCCFmdyX5OBghvyulK1OhhRtzNGLo9UDwUJxugLLQBxLxFWDGLewq1DC/BHi\nwQTjLjaTUW+2bfCAATPvmzTFY8EpBA34Wv+NQj6YpvKav2JylR/baUQwgsQBQVSR\nvWamLL2sslIhtrTX6yy9pic/EGQh0C3P4movdubfKUOmm/kKE55NNNo1ve8HTt2M\n/z3PWtZGqRD9hO8pvp0XRAEzL8MBajSdj7jB8i9OWQKBgQD5rGS2jNBFmqvcye2O\n7vhEtv4wOS/TvWx19FWDHszxN4bfc5pn29kwKMzV4Rzs8ilOqVIbwONMsv6ruMnQ\nn/B6RO0emzmhSsvQBPm30mBitFrlWM2dI5WnOgJK4R5whFcWJijFSXZj8dpViQLn\nHK0ztWyfclTENrhj9gmlq3hSFwKBgQDiuK8l5BBHGCEozg0GvFgGhVxT2Ce9lUCG\n0ZaXINj81gFUSAXZXmmLBLdUZ0r1WxGVV4panc/ahoGj5xRnSRuHJ+iBxU24jlxN\neiTD5tTpXkBEo6T8OCw0pr7XZm0de2d3jnp14+WLSu36EvjqaI0RwRjt/6EMzxrb\ni3xgCXf1LQKBgQCA7C0BOosdNfYCx15czTbzvI5a9lyk+I42BlnVoCTxddu2LKqR\ncKugu6Cx3FEkZRNBZBta62ozo8XvhDbp/HyfllHe2QaUK9w8aSVNb1uH/FtnTEi4\nGLThKgofAknGjf+uFzw8S2fPygYU3u/ZySwCpG8XkmEFBMCIFXb7ziQONwKBgQCa\nQan5j9h7ZrF4/+jhAlip5ybQbSts1BXZJNTe8pxwOnMhEvfX02LgEU9i//yCP3oR\nMESULvdy6T1fdSPuulEefkq1sLaWsVWf6VEGcRG/zj7P9L+WU+nP0PvbtnbbLlFR\niQFNIMfXJB4SncH6SzRgNg9uLxU2j9roMVloRkj16QKBgQDWX5d4f8tZf3f6WkLw\n0fZf0MMab0uSo4IR6fPtugLumDHlicRwi3DI6RNErcH+4YEzYA4ewMTFRu6gD4v2\nlAQdhI05Q0hv0Mlipbs/nvrdQ+zDDn8A4xcrWvCxDyJLZ2P5GZFlmUD4PiKCs2z2\n6bMqKh0DZiFIHKMXDJgOjTNubA==\n-----END PRIVATE KEY-----\n",
                'client_email' => "firebase-adminsdk-gnqgf@purple-card-enduser.iam.gserviceaccount.com",
                'client_id' => "118325696318046972909",
                'auth_uri' => "https://accounts.google.com/o/oauth2/auth",
                'token_uri' => "https://oauth2.googleapis.com/token",
                'auth_provider_x509_cert_url' => "https://www.googleapis.com/oauth2/v1/certs",
                'client_x509_cert_url' => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-gnqgf%40purple-card-enduser.iam.gserviceaccount.com",
                'universe_domain' => "googleapis.com",
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Auth Component
             * ------------------------------------------------------------------------
             */

            'auth' => [
                'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firestore Component
             * ------------------------------------------------------------------------
             */

            'firestore' => [

                /*
                 * If you want to access a Firestore database other than the default database,
                 * enter its name here.
                 *
                 * By default, the Firestore client will connect to the `(default)` database.
                 *
                 * https://firebase.google.com/docs/firestore/manage-databases
                 */

                // 'database' => env('FIREBASE_FIRESTORE_DATABASE'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Realtime Database
             * ------------------------------------------------------------------------
             */

            'database' => [

                /*
                 * In most of the cases the project ID defined in the credentials file
                 * determines the URL of your project's Realtime Database. If the
                 * connection to the Realtime Database fails, you can override
                 * its URL with the value you see at
                 *
                 * https://console.firebase.google.com/u/1/project/_/database
                 *
                 * Please make sure that you use a full URL like, for example,
                 * https://my-project-id.firebaseio.com
                 */

                'url' => env('FIREBASE_DATABASE_URL'),

                /*
                 * As a best practice, a service should have access to only the resources it needs.
                 * To get more fine-grained control over the resources a Firebase app instance can access,
                 * use a unique identifier in your Security Rules to represent your service.
                 *
                 * https://firebase.google.com/docs/database/admin/start#authenticate-with-limited-privileges
                 */

                // 'auth_variable_override' => [
                //     'uid' => 'my-service-worker'
                // ],

            ],

            'dynamic_links' => [

                /*
                 * Dynamic links can be built with any URL prefix registered on
                 *
                 * https://console.firebase.google.com/u/1/project/_/durablelinks/links/
                 *
                 * You can define one of those domains as the default for new Dynamic
                 * Links created within your project.
                 *
                 * The value must be a valid domain, for example,
                 * https://example.page.link
                 */

                'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
            ],

            /*
             * ------------------------------------------------------------------------
             * Firebase Cloud Storage
             * ------------------------------------------------------------------------
             */

            'storage' => [

                /*
                 * Your project's default storage bucket usually uses the project ID
                 * as its name. If you have multiple storage buckets and want to
                 * use another one as the default for your application, you can
                 * override it here.
                 */

                'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

            ],

            /*
             * ------------------------------------------------------------------------
             * Caching
             * ------------------------------------------------------------------------
             *
             * The Firebase Admin SDK can cache some data returned from the Firebase
             * API, for example Google's public keys used to verify ID tokens.
             *
             */

            'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

            /*
             * ------------------------------------------------------------------------
             * Logging
             * ------------------------------------------------------------------------
             *
             * Enable logging of HTTP interaction for insights and/or debugging.
             *
             * Log channels are defined in config/logging.php
             *
             * Successful HTTP messages are logged with the log level 'info'.
             * Failed HTTP messages are logged with the log level 'notice'.
             *
             * Note: Using the same channel for simple and debug logs will result in
             * two entries per request and response.
             */

            'logging' => [
                'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
                'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
            ],

            /*
             * ------------------------------------------------------------------------
             * HTTP Client Options
             * ------------------------------------------------------------------------
             *
             * Behavior of the HTTP Client performing the API requests
             */

            'http_client_options' => [

                /*
                 * Use a proxy that all API requests should be passed through.
                 * (default: none)
                 */

                'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

                /*
                 * Set the maximum amount of seconds (float) that can pass before
                 * a request is considered timed out
                 *
                 * The default time out can be reviewed at
                 * https://github.com/kreait/firebase-php/blob/6.x/src/Firebase/Http/HttpClientOptions.php
                 */

                'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

                'guzzle_middlewares' => [],
            ],
        ],
    ],
];
