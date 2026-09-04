<?php return array (
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'reverb' => 
      array (
        'driver' => 'reverb',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'host' => NULL,
          'port' => 443,
          'scheme' => 'https',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'cluster' => NULL,
          'host' => 'api-mt1.pusher.com',
          'port' => 443,
          'scheme' => 'https',
          'encrypted' => true,
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'concurrency' => 
  array (
    'default' => 'process',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 12,
      'verify' => true,
      'limit' => NULL,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
      'verify' => true,
    ),
    'rehash_on_login' => true,
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => 'null',
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'E:\\httracks\\final turemed\\FINAL\\storage\\logs/laravel.log',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'E:\\httracks\\final turemed\\FINAL\\storage\\logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
        'replace_placeholders' => true,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'handler_with' => 
        array (
          'stream' => 'php://stderr',
        ),
        'formatter' => NULL,
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
        'facility' => 8,
        'replace_placeholders' => true,
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'E:\\httracks\\final turemed\\FINAL\\storage\\logs/laravel.log',
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => NULL,
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => NULL,
        'secret' => NULL,
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
      'deferred' => 
      array (
        'driver' => 'deferred',
      ),
      'failover' => 
      array (
        'driver' => 'failover',
        'connections' => 
        array (
          0 => 'database',
          1 => 'deferred',
        ),
      ),
    ),
    'batching' => 
    array (
      'database' => 'mysql',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'postmark' => 
    array (
      'token' => NULL,
    ),
    'resend' => 
    array (
      'key' => NULL,
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'slack' => 
    array (
      'notifications' => 
      array (
        'bot_user_oauth_token' => NULL,
        'channel' => NULL,
      ),
    ),
  ),
  'app' => 
  array (
    'name' => 'Turkelitemedcare',
    'env' => 'production',
    'debug' => false,
    'url' => 'https://turkelitemedcare.com',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:le7F/dYtKyAM+F3iWE+7EtAIYyoVOyBV93w/ESLYAhY=',
    'previous_keys' => 
    array (
    ),
    'maintenance' => 
    array (
      'driver' => 'file',
      'store' => 'database',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Concurrency\\ConcurrencyServiceProvider',
      6 => 'Illuminate\\Cookie\\CookieServiceProvider',
      7 => 'Illuminate\\Database\\DatabaseServiceProvider',
      8 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      9 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      10 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      11 => 'Illuminate\\Hashing\\HashServiceProvider',
      12 => 'Illuminate\\Mail\\MailServiceProvider',
      13 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      14 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      15 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      16 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      17 => 'Illuminate\\Queue\\QueueServiceProvider',
      18 => 'Illuminate\\Redis\\RedisServiceProvider',
      19 => 'Illuminate\\Session\\SessionServiceProvider',
      20 => 'Illuminate\\Translation\\TranslationServiceProvider',
      21 => 'Illuminate\\Validation\\ValidationServiceProvider',
      22 => 'Illuminate\\View\\ViewServiceProvider',
      23 => 'App\\Providers\\AppServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Benchmark' => 'Illuminate\\Support\\Benchmark',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Concurrency' => 'Illuminate\\Support\\Facades\\Concurrency',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Context' => 'Illuminate\\Support\\Facades\\Context',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Number' => 'Illuminate\\Support\\Number',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Process' => 'Illuminate\\Support\\Facades\\Process',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schedule' => 'Illuminate\\Support\\Facades\\Schedule',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'Uri' => 'Illuminate\\Support\\Uri',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
    ),
  ),
  'build' => 
  array (
    'version' => 'FINAL-MGMT',
    'port' => 8000,
    'label' => 'Management Final Build',
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'session' => 
      array (
        'driver' => 'session',
        'key' => '_cache',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
        'lock_connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'E:\\httracks\\final turemed\\FINAL\\storage\\framework/cache/data',
        'lock_path' => 'E:\\httracks\\final turemed\\FINAL\\storage\\framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => NULL,
        'secret' => NULL,
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
      'failover' => 
      array (
        'driver' => 'failover',
        'stores' => 
        array (
          0 => 'database',
          1 => 'array',
        ),
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
    'prefix' => 'turkelitemedcare_cache_',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'newture',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'newture',
        'username' => 'root',
        'password' => 'root',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'mariadb' => 
      array (
        'driver' => 'mariadb',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'newture',
        'username' => 'root',
        'password' => 'root',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'newture',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'newture',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'turkelitemedcare_database_',
        'persistent' => false,
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
        'max_retries' => 3,
        'backoff_algorithm' => 'decorrelated_jitter',
        'backoff_base' => 100,
        'backoff_cap' => 1000,
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
        'max_retries' => 3,
        'backoff_algorithm' => 'decorrelated_jitter',
        'backoff_base' => 100,
        'backoff_cap' => 1000,
      ),
    ),
  ),
  'design' => 
  array (
    'default' => 'clinical',
    'allowed' => 
    array (
      0 => 'clinical',
    ),
    'show_switcher' => false,
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'E:\\httracks\\final turemed\\FINAL\\storage\\app/private',
        'serve' => true,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'E:\\httracks\\final turemed\\FINAL\\storage\\app/public',
        'url' => 'https://turkelitemedcare.com/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
        'report' => false,
      ),
      'uploads' => 
      array (
        'driver' => 'local',
        'root' => 'E:\\httracks\\final turemed\\FINAL\\public\\uploads',
        'url' => 'https://turkelitemedcare.com/uploads',
        'visibility' => 'public',
      ),
    ),
    'links' => 
    array (
      'E:\\httracks\\final turemed\\FINAL\\public\\storage' => 'E:\\httracks\\final turemed\\FINAL\\storage\\app/public',
    ),
  ),
  'legal-documents' => 
  array (
    0 => 
    array (
      'title' => 'Impressum / Legal notice',
      'slug' => 'impressum',
      'summary' => 'Business identity and statutory legal notice.',
    ),
    1 => 
    array (
      'title' => 'Privacy notice',
      'slug' => 'privacy-policy',
      'summary' => 'How personal information is collected and handled.',
    ),
    2 => 
    array (
      'title' => 'Cookie policy',
      'slug' => 'cookie-policy',
      'summary' => 'Information about cookies and consent preferences.',
    ),
    3 => 
    array (
      'title' => 'Platform terms',
      'slug' => 'terms',
      'summary' => 'Terms for using the coordination platform.',
    ),
    4 => 
    array (
      'title' => 'Medical disclaimer',
      'slug' => 'medical-disclaimer',
      'summary' => 'The distinction between coordination and medical care.',
    ),
    5 => 
    array (
      'title' => 'Complaints process',
      'slug' => 'complaints',
      'summary' => 'How to raise a concern and what happens next.',
    ),
    6 => 
    array (
      'title' => 'Clinic selection standards',
      'slug' => 'clinic-selection-standards',
      'summary' => 'How partner providers are reviewed before publication.',
    ),
    7 => 
    array (
      'title' => 'Patient rights and provider responsibilities',
      'slug' => 'patient-rights',
      'summary' => 'A clear view of patient and provider responsibilities.',
    ),
  ),
  'locales' => 
  array (
    'supported' => 
    array (
      0 => 'en',
      1 => 'de',
      2 => 'ar',
    ),
    'names' => 
    array (
      'en' => 'English',
      'de' => 'Deutsch',
      'tr' => 'Türkçe',
      'ar' => 'العربية',
    ),
    'rtl' => 
    array (
      0 => 'ar',
    ),
  ),
  'mail' => 
  array (
    'default' => 'smtp',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'scheme' => 'tls',
        'url' => NULL,
        'host' => 'smtp.hostinger.com',
        'port' => '2587',
        'username' => 'info@turkelitemedcare.com',
        'password' => NULL,
        'timeout' => NULL,
        'local_domain' => 'turkelitemedcare.com',
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'resend' => 
      array (
        'transport' => 'resend',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
        'retry_after' => 60,
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
        'retry_after' => 60,
      ),
    ),
    'from' => 
    array (
      'address' => 'info@turkelitemedcare.com',
      'name' => 'Turkelite Medcare',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'E:\\httracks\\final turemed\\FINAL\\resources\\views/vendor/mail',
      ),
      'extensions' => 
      array (
      ),
    ),
    'admin_address' => 'info@turkelitemedcare.com',
  ),
  'seo' => 
  array (
    'priority_procedures' => 
    array (
      0 => 'fue-hair-transplant',
      1 => 'dhi-hair-transplant',
      2 => 'dental-implants',
      3 => 'all-on-4',
      4 => 'dental-veneers',
      5 => 'dental-crowns',
      6 => 'rhinoplasty',
      7 => 'breast-augmentation',
      8 => 'breast-lift',
      9 => 'liposuction',
      10 => 'tummy-tuck-abdominoplasty',
      11 => 'blepharoplasty',
      12 => 'sleeve-gastrectomy',
      13 => 'roux-en-y-gastric-bypass',
      14 => 'gastric-balloon',
      15 => 'lasik',
      16 => 'cataract-surgery',
      17 => 'in-vitro-fertilisation-ivf',
      18 => 'total-knee-replacement',
      19 => 'total-hip-replacement',
    ),
    'indexable_locales' => 
    array (
      0 => 'en',
      1 => 'de',
      2 => 'ar',
    ),
    'configured_clinic_slugs' => 
    array (
      0 => 'clinic-expert',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => true,
    'encrypt' => false,
    'files' => 'E:\\httracks\\final turemed\\FINAL\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'turkelitemedcare_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
  ),
  'site' => 
  array (
    'brand' => 
    array (
      'name' => 'Turkelite Medcare',
      'legal_name' => 'Turkelite Medcare',
      'email' => 'info@turkelitemedcare.com',
      'phone' => '+49 30 23125 400',
      'whatsapp_number' => '+49 30 23125 400',
      'logo_path' => 'assets/img/brand/turkelite-medcare-logo.svg',
      'favicon_path' => 'assets/img/brand/turkelite-medcare-mark.svg',
      'whatsapp' => 'https://wa.me/493023125400?text=Hello%2C%20I%20would%20like%20to%20ask%20about%20treatment%20in%20Turkey.',
    ),
    'support_hours' => 
    array (
      'weekday' => 'Mon-Fri · 08:00-18:00 CET',
      'callback' => 'Weekday callbacks, usually the same or next working day',
    ),
    'specialties' => 
    array (
      0 => 
      array (
        'slug' => 'dental',
        'name' => 'Dental',
        'summary' => 'Oral health and restorative dentistry',
        'description' => 'Implants, restorations and aesthetic dentistry coordinated through partner clinics in Turkey.',
      ),
      1 => 
      array (
        'slug' => 'hair-restoration',
        'name' => 'Hair Restoration',
        'summary' => 'Hair transplant and restoration',
        'description' => 'FUE, DHI and specialist restoration pathways with practical travel coordination.',
      ),
      2 => 
      array (
        'slug' => 'cosmetic-surgery',
        'name' => 'Plastic & Cosmetic Surgery',
        'summary' => 'Face, breast and body procedures',
        'description' => 'Rhinoplasty, body contouring and facial aesthetic procedures with clear consultation steps.',
      ),
      3 => 
      array (
        'slug' => 'bariatric-surgery',
        'name' => 'Bariatric Surgery',
        'summary' => 'Metabolic and weight-loss care',
        'description' => 'Sleeve, bypass and revisional pathways focused on clinical review and recovery timing.',
      ),
      4 => 
      array (
        'slug' => 'eye-care',
        'name' => 'Eye Care',
        'summary' => 'Vision correction and ophthalmology',
        'description' => 'LASIK, cataract and lens procedures supported by a coordinated treatment journey.',
      ),
      5 => 
      array (
        'slug' => 'orthopedics',
        'name' => 'Orthopedics',
        'summary' => 'Bones, joints and spine',
        'description' => 'Joint replacement, sports injuries and spinal procedures coordinated with provider review.',
      ),
      6 => 
      array (
        'slug' => 'ent',
        'name' => 'ENT',
        'summary' => 'Ear, nose and throat',
        'description' => 'Sinus, septum, hearing and throat care through the partner clinic network.',
      ),
      7 => 
      array (
        'slug' => 'urology',
        'name' => 'Urology',
        'summary' => 'Urinary and male health',
        'description' => 'Kidney, prostate and urinary procedures guided by clinical suitability checks.',
      ),
      8 => 
      array (
        'slug' => 'fertility',
        'name' => 'Fertility & Reproductive Medicine',
        'summary' => 'Fertility assessment and treatment',
        'description' => 'Fertility evaluation, IVF and related pathways coordinated around your plan.',
      ),
      9 => 
      array (
        'slug' => 'general-surgery',
        'name' => 'General Surgery',
        'summary' => 'Planned elective surgery',
        'description' => 'Elective surgical procedures with a focus on clear quotation and handover details.',
      ),
    ),
  ),
  'site-pages' => 
  array (
    'home' => 
    array (
      'title' => 'Medical Treatment in Turkey | Turkelite Medcare',
      'description' => 'Explore treatment pathways and coordinated medical travel with independent providers in Turkey.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Medical treatment in Turkey',
          'headline' => 'Care you can understand and compare',
          'lead' => 'Choose a treatment pathway and let one coordination team keep the clinic, schedule, travel, arrival and follow-up handovers connected around you.',
          'actions' => 
          array (
            0 => 
            array (
              'label' => 'Get treatment plan',
              'href' => '/treatment-plan.html',
              'variant' => 'primary',
            ),
            1 => 
            array (
              'label' => 'Explore treatments',
              'href' => '/treatments',
              'variant' => 'ghost',
            ),
          ),
        ),
        'welcome' => 
        array (
          'heading' => 'Welcome to Turkelite Medcare',
          'features' => 
          array (
            0 => 
            array (
              'icon' => 'clinic',
              'number' => '01',
              'title' => 'Provider profiles with clear roles',
              'text' => 'Treatment-linked clinic profiles make it clear where care is delivered and who is responsible for the clinical plan.',
            ),
            1 => 
            array (
              'icon' => 'specialist',
              'number' => '02',
              'title' => 'Specialist-led decisions',
              'text' => 'Treatment suitability remains with the independent clinician who evaluates your case.',
            ),
            2 => 
            array (
              'icon' => 'journey',
              'number' => '03',
              'title' => 'Whole-journey coordination',
              'text' => 'One point of contact keeps clinic communication, scheduling, travel, arrival, and follow-up connected.',
            ),
            3 => 
            array (
              'icon' => 'support',
              'number' => '04',
              'title' => 'International support',
              'text' => 'Journey planning for international patients travelling to partner clinics in Turkey.',
            ),
          ),
          'form_heading' => 'Get treatment plan',
          'specialty_label' => 'Specialty',
          'specialty_placeholder' => 'Choose specialty',
          'country_label' => 'Your country',
          'country_placeholder' => 'Germany',
          'contact_label' => 'Preferred contact',
          'contact_placeholder' => 'WhatsApp or email',
          'button_label' => 'Start my request',
          'note' => 'Medical files can be requested securely later if a clinic needs them for review.',
        ),
        'specialties' => 
        array (
          'eyebrow' => 'Treatment library',
          'heading' => 'Explore our specialties',
          'lead' => 'Choose a medical specialty, then explore the procedure pages already published in the legacy library.',
        ),
        'services' => 
        array (
          'eyebrow' => 'Patient services',
          'heading' => 'Your treatment trip should feel looked after',
          'lead' => 'Before you travel, the clinical plan, provider choice, appointments, logistics and return-home follow-up all need to line up.',
          'items' => 
          array (
            0 => 
            array (
              'title' => 'Coordinated appointments',
              'text' => 'We keep clinic dates and handovers aligned.',
            ),
            1 => 
            array (
              'title' => 'Travel planning',
              'text' => 'Flights, transfers and accommodation can be sequenced around the treatment plan.',
            ),
            2 => 
            array (
              'title' => 'Follow-up organisation',
              'text' => 'We keep the post-treatment handover visible before departure.',
            ),
          ),
          'cta' => 
          array (
            'label' => 'Patient services',
            'href' => '/patient-services',
          ),
          'secondary' => 
          array (
            'label' => 'See how it works',
            'href' => '/how-it-works',
          ),
        ),
        'testimonial' => 
        array (
          'eyebrow' => 'Patient journey examples',
          'heading' => 'What whole-journey assurance should feel like',
          'lead' => 'Patient journeys showing how treatment planning, travel and follow-up stay connected.',
          'quote' => 'I wanted to understand implant options before choosing a clinic. Having the treatment guide, provider questions and practical journey in one place made the questions I needed to ask much clearer.',
          'cite' => 'Martin K. - Germany - Illustrative dental journey',
        ),
        'specialist_teams' => 
        array (
          'eyebrow' => 'Specialists',
          'heading' => 'Meet specialist teams',
          'lead' => 'Specialist profiles connect treatment pathways to the clinics and teams that provide the care.',
          'empty_heading' => 'Specialist profiles are being prepared',
          'empty_text' => 'Published doctor profiles will appear here when they are added by the admin team.',
        ),
        'guides_videos' => 
        array (
          'eyebrow' => 'Guides & articles',
          'heading' => 'Prepare before you travel',
          'lead' => 'Educational content helps patients ask better questions and understand the practical side of treatment abroad.',
          'guide_label' => 'Guide',
          'guide_action' => 'Read guide',
          'video_label' => 'Article',
          'video_heading' => 'Treatment decision checklist',
          'video_meta' => 'Decision support',
          'video_text' => 'Practical decision articles help patients prepare questions about providers, scope, travel timing and follow-up.',
          'video_action' => 'Read articles',
        ),
      ),
    ),
    'about' => 
    array (
      'title' => 'About Us | Turkelite Medcare',
      'description' => 'Who Turkelite Medcare is, what we coordinate, what independent partner clinics remain responsible for, and how we are paid.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'About the platform',
          'headline' => 'About Us',
          'lead' => 'A medical-travel coordination platform designed to connect international patients with selected independent providers in Turkey.',
        ),
        'hero_card' => 
        array (
          'label' => 'Care pathway',
          'heading' => 'Plan -> Coordinate -> Return supported',
          'text' => 'One team coordinating the journey around your care',
        ),
        'intro' => 
        array (
          'what_we_do_heading' => 'What we do',
          'what_we_do' => 'Turkelite Medcare is a medical-travel coordination brand designed for international patients seeking treatment pathways in Turkey. The platform is designed to explain treatment pathways, present provider information, collect patient enquiries and coordinate practical next steps.',
          'what_we_do_not_heading' => 'What we do not do',
          'what_we_do_not' => 'The platform is not a hospital and does not diagnose, prescribe or perform treatment. Final clinical recommendations, informed consent, treatment and aftercare are responsibilities of the independent healthcare provider.',
        ),
        'clinic_standards' => 
        array (
          'heading' => 'How clinic onboarding should work',
          'items' => 
          array (
            0 => 
            array (
              'title' => 'Identity & licensing',
              'text' => 'Verify the legal provider and appropriate healthcare authorisations.',
            ),
            1 => 
            array (
              'title' => 'Specialist credentials',
              'text' => 'Confirm named clinicians and the procedures they perform.',
            ),
            2 => 
            array (
              'title' => 'Patient pathway',
              'text' => 'Document assessment, consent, safety and follow-up processes.',
            ),
            3 => 
            array (
              'title' => 'Content approval',
              'text' => 'Have provider-specific medical claims reviewed before publication.',
            ),
          ),
        ),
        'clinic_sidebar' => 
        array (
          'kicker' => 'For clinics',
          'heading' => 'Join the network',
          'text' => 'The partner section is ready for an onboarding/application workflow.',
          'link_label' => 'Partner with us',
          'link' => '/for-clinics',
        ),
        'contact_routes' => 
        array (
          0 => 
          array (
            'title' => 'Message us on WhatsApp',
            'text' => 'Ask one question. No form, no commitment.',
            'href' => 'https://wa.me/493023125400?text=Hello%2C%20I%20would%20like%20to%20ask%20about%20treatment%20in%20Turkey.',
            'variant' => 'wa',
          ),
          1 => 
          array (
            'title' => 'Request a callback',
            'text' => 'German or English, at a time that suits you.',
            'href' => '/contact#callback',
            'variant' => 'call',
          ),
          2 => 
          array (
            'title' => 'Start a treatment enquiry',
            'text' => 'For when you are ready for a clinic to review your case.',
            'href' => '/treatment-plan',
            'variant' => 'plan',
          ),
        ),
        'transparency' => 
        array (
          'kicker' => 'Commercial transparency',
          'heading' => 'How we are paid',
          'paragraphs' => 
          array (
            0 => 'You should know who pays us before you rely on anything we tell you. Ask us directly and we will answer in writing who pays our fee, whether it differs between clinics, and whether any clinic can pay to be featured.',
            1 => 'What we will commit to in public: a clinic\'s commercial terms with us never decide whether it is clinically right for you. Suitability is the treating clinician\'s judgement, and we do not overrule it or route around it.',
          ),
          'link' => '/legal/terms.html',
          'link_prefix' => 'Full terms are in the',
          'link_label' => 'platform terms',
        ),
      ),
    ),
    'legal' => 
    array (
      'title' => 'Legal & Privacy | Turkelite Medcare',
      'description' => 'Legal, privacy and compliance information for Turkelite Medcare, including medical responsibility, privacy and complaints guidance.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Trust & compliance',
          'headline' => 'Legal & Privacy',
          'lead' => 'Clear information about how Turkelite Medcare coordinates care, protects information and works with independent healthcare providers.',
        ),
        'hero_card' => 
        array (
          'label' => 'Role clarity',
          'heading' => 'Coordinate with confidence',
          'text' => 'Medical decisions remain with independent partner providers.',
        ),
        'documents' => 
        array (
          'heading' => 'Legal and privacy documents',
          'intro' => 'Use the documents below to understand the platform, your rights and the responsibilities of Turkelite Medcare and its independent healthcare partners.',
          'items' => 
          array (
            0 => 
            array (
              'title' => 'Impressum / Legal notice',
              'url' => '/legal/impressum.html',
            ),
            1 => 
            array (
              'title' => 'Privacy notice',
              'url' => '/legal/privacy-policy.html',
            ),
            2 => 
            array (
              'title' => 'Cookie policy',
              'url' => '/legal/cookie-policy.html',
            ),
            3 => 
            array (
              'title' => 'Platform terms',
              'url' => '/legal/terms.html',
            ),
            4 => 
            array (
              'title' => 'Medical disclaimer',
              'url' => '/legal/medical-disclaimer.html',
            ),
            5 => 
            array (
              'title' => 'Complaints process',
              'url' => '/legal/complaints.html',
            ),
            6 => 
            array (
              'title' => 'Clinic selection standards',
              'url' => '/legal/clinic-selection-standards.html',
            ),
            7 => 
            array (
              'title' => 'Patient rights and provider responsibilities',
              'url' => '/legal/patient-rights.html',
            ),
          ),
        ),
        'notice' => 'These documents are structural drafts and must be reviewed by qualified counsel in the relevant jurisdictions before production publication.',
        'ui' => 
        array (
          'important_notice_label' => 'Important notice',
          'draft_label' => 'Draft - pending legal review',
          'legal_flag' => 'The Impressum is a statutory requirement for publishing in Germany. The cookie policy also depends on a working consent banner. No document on this page replaces professional legal advice.',
          'urgent_notice' => 'For urgent medical concerns, contact your treating provider or local emergency services.',
        ),
        'role_clarity' => 
        array (
          'heading' => 'Role clarity',
          'paragraphs' => 
          array (
            0 => 'Turkelite Medcare is a coordination platform. We help organise communication, scheduling and practical travel support around treatment provided by independent healthcare providers.',
            1 => 'Partner clinics and doctors remain responsible for clinical assessment, treatment recommendations, informed consent, medical care, billing for medical services and clinical aftercare.',
          ),
        ),
        'sidebar' => 
        array (
          'kicker' => 'Need help?',
          'heading' => 'Questions about your information?',
          'text' => 'Contact the coordination team if you need help understanding a request or want to raise a concern about how your information is handled.',
          'button' => 'Contact the team',
        ),
      ),
    ),
    'contact' => 
    array (
      'title' => 'Contact | Turkelite Medcare',
      'description' => 'Contact the Turkelite Medcare international patient team about a treatment enquiry, an existing journey or a partner-clinic question.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Talk to us',
          'headline' => 'Contact',
          'lead' => 'Use this page for general enquiries. Treatment-related enquiries should use the structured treatment request.',
        ),
        'contact_info' => 
        array (
          0 => 
          array (
            'label' => 'Email',
            'value' => '{{brand.email}}',
          ),
          1 => 
          array (
            'label' => 'Phone',
            'value' => '{{brand.phone}}',
          ),
          2 => 
          array (
            'label' => 'Languages',
            'value' => 'German, English, additional languages by arrangement',
          ),
        ),
        'form' => 
        array (
          'heading' => 'Contact the coordination team',
          'note' => 'Your enquiry continues through the treatment-coordination workflow.',
        ),
        'callback' => 
        array (
          'heading' => 'Request a callback',
          'lead' => 'Tell us when suits and which language you would prefer. We will call you. There is no obligation, and you do not need to share any medical detail to talk to us.',
          'note' => 'Weekday callbacks, usually the same or next working day.',
        ),
      ),
    ),
    'for-clinics' => 
    array (
      'title' => 'For Clinics | Turkelite Medcare',
      'description' => 'Partner with Turkelite Medcare as an independent clinic or provider in Turkey.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Partner clinics',
          'headline' => 'For Clinics',
          'lead' => 'We work with independent clinics that want a clear patient-coordination layer around their treatment pathways.',
        ),
        'features' => 
        array (
          'heading' => 'What a partner relationship should cover',
          'items' => 
          array (
            0 => 
            array (
              'title' => 'Identity and licensing',
              'text' => 'Verify the legal provider and appropriate healthcare authorisations.',
            ),
            1 => 
            array (
              'title' => 'Specialist credentials',
              'text' => 'Confirm named clinicians and the procedures they perform.',
            ),
            2 => 
            array (
              'title' => 'Patient pathway',
              'text' => 'Document assessment, consent, safety and follow-up processes.',
            ),
            3 => 
            array (
              'title' => 'Content approval',
              'text' => 'Have provider-specific medical claims reviewed before publication.',
            ),
          ),
          'cta' => 
          array (
            'label' => 'Contact us',
            'href' => '/contact',
          ),
        ),
      ),
    ),
    'how-it-works' => 
    array (
      'title' => 'How It Works | Turkelite Medcare',
      'description' => 'How a coordinated treatment journey works step by step, the decisions to settle before booking, and what a clinic quotation should include.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'The Turkelite Medcare model',
          'headline' => 'From first enquiry to follow-up',
          'lead' => 'We organise the international-patient journey around independent clinical decisions made by the partner clinic. One coordinator keeps records, providers, travel logistics and communication moving in the same direction.',
        ),
        'summary' => 
        array (
          'heading' => 'Three clear roles',
          'items' => 
          array (
            0 => 'The patient explains the need and shares requested information.',
            1 => 'Turkelite Medcare prepares, routes, compares and coordinates the case.',
            2 => 'The clinic assesses, recommends, consents and provides treatment.',
          ),
        ),
        'model' => 
        array (
          'eyebrow' => 'Business model',
          'heading' => 'One connected pathway, three distinct responsibilities',
          'text' => 'The platform is the coordination layer between international patients and enrolled healthcare providers, not the medical provider itself.',
        ),
        'experience' => 
        array (
          'eyebrow' => 'Step by step',
          'heading' => 'What the patient actually experiences',
          'text' => 'Every stage has a clear owner, a clear output, and a clear point at which clinical responsibility stays with the treating provider.',
        ),
        'roles' => 
        array (
          'eyebrow' => 'Responsibility map',
          'heading' => 'Who is responsible for what?',
          'patient' => 
          array (
            'label' => 'Patient',
            'items' => 
            array (
              0 => 'Provides accurate medical information',
              1 => 'Asks questions and compares options',
              2 => 'Chooses the provider',
              3 => 'Follows clinical and travel instructions',
            ),
          ),
          'coordinator' => 
          array (
            'label' => 'Turkelite Medcare',
            'items' => 
            array (
              0 => 'Structures and routes the case',
              1 => 'Coordinates provider communication',
              2 => 'Organises practical travel services',
              3 => 'Keeps the journey and follow-up handover connected',
            ),
          ),
          'clinic' => 
          array (
            'label' => 'Partner clinic',
            'items' => 
            array (
              0 => 'Assesses clinical suitability',
              1 => 'Explains treatment, risks and alternatives',
              2 => 'Obtains informed consent',
              3 => 'Provides treatment and clinical aftercare instructions',
            ),
          ),
          'partner_eyebrow' => 'Partner model',
          'partner_heading' => 'Turkelite Medcare is the practical coordination layer, not the medical provider.',
          'partner_text' => 'Partner clinics remain independent healthcare providers. Clinical recommendations, consent, treatment, and medical follow-up remain with the provider.',
        ),
        'cta' => 
        array (
          'eyebrow' => 'Ready to start?',
          'heading' => 'Tell us what treatment you are considering.',
        ),
        'quotations' => 
        array (
          'eyebrow' => 'Before you book',
          'text' => 'Before a treatment journey is ready to book, each of these should have a clear answer and a clear owner.',
          'decision' => 'Decision',
          'why' => 'Why it matters',
          'coordination' => 'How we coordinate it',
          'cost_eyebrow' => 'Quotations',
          'cost_heading' => 'What "included" actually means',
          'cost_text' => 'Every clinic proposal should be broken down against the same checklist, so quotations can be compared on the same terms.',
          'included' => 'Usually included',
          'excluded' => 'Frequently excluded - ask directly',
          'note' => 'A firm price can only follow clinical assessment. Any figure before a clinician reviews your case is an estimate and can change.',
        ),
        'routes' => 
        array (
          'message_title' => 'Send us a message',
          'message_text' => 'Ask one question. No commitment.',
          'callback_title' => 'Request a callback',
          'callback_text' => 'German or English, at a time that suits you.',
          'plan_title' => 'Start a treatment enquiry',
          'plan_text' => 'For when you are ready for a clinic to review your case.',
        ),
        'steps' => 
        array (
          0 => 
          array (
            'number' => '01',
            'title' => 'Tell us what you need',
            'text' => 'Choose a specialty or procedure, or simply describe the concern in your own words.',
            'owner' => 'Patient',
          ),
          1 => 
          array (
            'number' => '02',
            'title' => 'Share the requested records',
            'text' => 'Upload reports, images, medication details and relevant history requested for preliminary review.',
            'owner' => 'Patient',
          ),
          2 => 
          array (
            'number' => '03',
            'title' => 'Case preparation',
            'text' => 'We check completeness, organise the case, translate logistics where required and route it to suitable partner teams.',
            'owner' => 'Turkelite Medcare',
          ),
          3 => 
          array (
            'number' => '04',
            'title' => 'Clinical review',
            'text' => 'The independent clinic or doctor evaluates the information and decides what assessment or treatment may be appropriate.',
            'owner' => 'Partner clinic',
          ),
        ),
        'decisions' => 
        array (
          'heading' => 'The four decisions that should have an owner',
          'items' => 
          array (
            0 => 
            array (
              'decision' => 'Is this procedure suitable for me?',
              'why' => 'The procedure must fit your clinical situation, not simply your preference.',
              'coordination' => 'We organise the records and questions the partner clinic asks for, so the clinical review can actually happen.',
            ),
            1 => 
            array (
              'decision' => 'What exactly is included?',
              'why' => 'Quotes can look similar while covering different treatment, facility and aftercare items.',
              'coordination' => 'We structure the clinic proposal so inclusions, exclusions and practical extras are comparable.',
            ),
            2 => 
            array (
              'decision' => 'When should I book travel?',
              'why' => 'Clinical dates and recovery requirements should determine the journey, not the other way round.',
              'coordination' => 'We sequence flights, transfers and accommodation around the clinic-confirmed plan.',
            ),
            3 => 
            array (
              'decision' => 'What happens after I return?',
              'why' => 'Follow-up should not become unclear once you leave Turkey.',
              'coordination' => 'We make the handover, contact route and provider instructions visible before departure.',
            ),
          ),
        ),
      ),
    ),
    'patient-services' => 
    array (
      'title' => 'Patient Services | Turkelite Medcare',
      'description' => 'Practical support for travel, scheduling, transfers, language and follow-up during a treatment journey.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Practical support',
          'headline' => 'Patient Services',
          'lead' => 'Coordinated appointments, travel planning and return-home follow-up so the practical side of treatment stays connected.',
        ),
        'services' => 
        array (
          'heading' => 'What we coordinate',
          'items' => 
          array (
            0 => 'Coordinated appointments',
            1 => 'Travel planning',
            2 => 'Follow-up organisation',
            3 => 'Language support',
          ),
        ),
        'pre_travel' => 
        array (
          'eyebrow' => 'Before you travel',
          'heading' => 'What a well-prepared patient should have',
          'lead' => 'The exact requirements vary by treatment, but the practical package should be complete before departure.',
          'items' => 
          array (
            0 => 
            array (
              'number' => '01',
              'title' => 'Confirmed clinical pathway',
              'text' => 'Provider, appointment sequence, requested tests and next decision points.',
            ),
            1 => 
            array (
              'number' => '02',
              'title' => 'Journey itinerary',
              'text' => 'Arrival, accommodation, clinic visits, transfer contacts and expected departure window.',
            ),
            2 => 
            array (
              'number' => '03',
              'title' => 'Named contacts',
              'text' => 'Coordinator, clinic international desk and aftercare contact details.',
            ),
            3 => 
            array (
              'number' => '04',
              'title' => 'Follow-up plan',
              'text' => 'Discharge documents, warning signs, review timing and return-home communication route.',
            ),
          ),
        ),
        'cta' => 
        array (
          'eyebrow' => 'Patient support',
          'heading' => 'Build the treatment and travel plan together.',
          'button_label' => 'Start my request',
        ),
        'contact_routes' => 
        array (
          0 => 
          array (
            'title' => 'Message us on WhatsApp',
            'text' => 'Ask one question. No form, no commitment.',
            'href' => 'https://wa.me/493023125400?text=Hello%2C%20I%20would%20like%20to%20ask%20about%20treatment%20in%20Turkey.',
          ),
          1 => 
          array (
            'title' => 'Request a callback',
            'text' => 'German or English, at a time that suits you.',
            'href' => '/contact#callback',
          ),
          2 => 
          array (
            'title' => 'Start a treatment enquiry',
            'text' => 'For when you are ready for a clinic to review your case.',
            'href' => '/treatment-plan',
          ),
        ),
      ),
    ),
    'guides.index' => 
    array (
      'title' => 'Guides | Turkelite Medcare',
      'description' => 'Patient-friendly educational content for better-informed medical-travel decisions.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Knowledge hub',
          'headline' => 'Guides',
          'lead' => 'Patient-friendly educational content supporting safer, better-informed medical-travel decisions.',
          'card_label' => 'Care pathway',
          'card_heading' => 'Plan -> Coordinate -> Return supported',
          'card_text' => 'One team coordinating the journey around your care.',
        ),
        'listing' => 
        array (
          'label' => 'Guide',
          'read_label' => 'Read guide',
          'empty_label' => 'Guide library',
          'empty_heading' => 'Guides are being prepared',
          'empty_text' => 'Published guide pages will appear here when they are added in the admin content library.',
        ),
        'contact_routes' => 
        array (
          0 => 
          array (
            'title' => 'Send us a message',
            'text' => 'Ask one question. No commitment.',
            'href' => '/contact#message',
          ),
          1 => 
          array (
            'title' => 'Request a callback',
            'text' => 'German or English, at a time that suits you.',
            'href' => '/contact#callback',
          ),
          2 => 
          array (
            'title' => 'Start a treatment enquiry',
            'text' => 'For when you are ready for a clinic to review your case.',
            'href' => '/treatment-plan',
          ),
        ),
      ),
    ),
    'stories.index' => 
    array (
      'title' => 'Patient Stories | Turkelite Medcare',
      'description' => 'Published patient journey stories.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Patient journeys',
          'headline' => 'Patient Stories',
          'lead' => 'See how research, clinical review, travel planning and follow-up can connect across a treatment journey.',
          'card_label' => 'Patient pathway',
          'card_heading' => 'Concern -> Options -> Provider -> Journey',
          'card_text' => 'Designed for transparent medical-travel planning',
        ),
        'intro' => 
        array (
          'eyebrow' => 'Journey examples',
          'heading' => 'From question to coordinated plan',
          'lead' => 'Each published story follows the journey from first research through provider review, travel preparation and follow-up planning.',
          'card_meta' => 'Patient journey',
          'published_label' => 'Published story',
          'read_label' => 'Read story',
          'date_label' => 'Story',
        ),
        'empty' => 
        array (
          'heading' => 'No patient stories are published yet',
          'text' => 'Published patient journeys will appear here soon.',
        ),
      ),
    ),
    'doctors.index' => 
    array (
      'title' => 'Doctors | Turkelite Medcare',
      'description' => 'Published specialist profiles from the partner network.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Medical teams',
          'headline' => 'Doctors',
          'lead' => 'Explore specialist profiles by medical focus, affiliated clinic, languages and treatment pathways.',
          'card_label' => 'Provider transparency',
          'card_heading' => 'Doctor -> Clinic -> Procedure',
          'card_text' => 'Credentials must be verified before publication.',
        ),
        'listing' => 
        array (
          'eyebrow' => 'Specialist network',
          'heading' => 'Specialists across our treatment pathways',
          'lead' => 'Specialist profiles are structured around the information international patients need before requesting a clinical review.',
          'card_label' => 'Specialist profile',
          'empty_heading' => 'Specialist profiles are being prepared',
          'empty_text' => 'Published profiles will appear here when they are added by the admin team.',
        ),
      ),
    ),
    'treatment-plan' => 
    array (
      'title' => 'Get Your Treatment Plan | Turkelite Medcare',
      'description' => 'Start a treatment enquiry and share the information needed for coordinator review.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Start here',
          'headline' => 'Get Your Treatment Plan',
          'lead' => 'You do not need to know the exact procedure. Tell us what you are looking for and this structured request prepares the information for coordinator review.',
          'card_label' => 'Care pathway',
          'card_heading' => 'Plan, coordinate, return supported',
          'card_text' => 'One team coordinating the practical journey around your care.',
        ),
        'form' => 
        array (
          'success_heading' => 'Request received',
          'error' => 'Please check the highlighted fields and try again.',
          'reply_heading' => 'A coordinator replies within one working day.',
          'reply_text' => 'Monday to Friday, in German or English.',
          'step1' => 'What can we help with?',
          'step2' => 'About you',
          'step3' => 'Tell us anything useful',
          'next' => 'Continue',
          'back' => 'Back',
          'submit' => 'Submit Treatment Request',
        ),
        'aside' => 
        array (
          'heading' => 'What happens next?',
          'privacy_heading' => 'Your information matters',
        ),
      ),
    ),
    'clinics.index' => 
    array (
      'title' => 'Clinics | Turkelite Medcare',
      'description' => 'Compare published partner clinic profiles.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Partner network',
          'headline' => 'Selected clinics in Turkey',
          'lead' => 'Explore published partner clinics by city, profile, and international-patient support.',
          'card_label' => 'Network snapshot',
        ),
      ),
    ),
    'treatments.index' => 
    array (
      'title' => 'Treatments | Turkelite Medcare',
      'description' => 'Browse the current specialty library and open a treatment pathway that matches your concern.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Treatment library',
          'headline' => 'Treatments',
          'lead' => 'Choose a medical specialty, then explore the legacy condition and procedure pages already published for that pathway.',
          'primary_action' => 'Get treatment plan',
          'secondary_action' => 'Browse specialties',
          'library_label' => 'How to use this library',
          'specialty_step' => 'Choose a specialty',
          'condition_step' => 'Start from a condition or concern',
          'procedure_step' => 'Explore relevant procedures',
          'clinic_step' => 'Compare clinics and specialists',
        ),
      ),
    ),
    'treatments.show' => 
    array (
      'title' => 'Treatment specialty | Turkelite Medcare',
      'description' => 'Open a treatment specialty pathway with the published condition and procedure pages.',
      'content' => 
      array (
        'hero' => 
        array (
          'kicker' => 'Treatment specialty',
          'headline' => 'Specialty detail',
          'lead' => 'Explore the current library and the published pages that sit behind this specialty.',
          'procedure_action' => 'Explore procedures',
          'assurance_plan' => 'Plan your journey',
          'assurance_guidance' => 'Personal guidance',
          'assurance_library' => 'Published library',
          'options_label' => 'Treatment options',
          'explore_label' => 'Explore',
          'empty_text' => 'No procedure content is published yet.',
        ),
      ),
    ),
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'E:\\httracks\\final turemed\\FINAL\\resources\\views',
    ),
    'compiled' => 'E:\\httracks\\final turemed\\FINAL\\storage\\framework\\views',
  ),
  'datatables' => 
  array (
    'search' => 
    array (
      'smart' => true,
      'multi_term' => true,
      'case_insensitive' => true,
      'use_wildcards' => false,
      'starts_with' => false,
    ),
    'index_column' => 'DT_RowIndex',
    'engines' => 
    array (
      'eloquent' => 'Yajra\\DataTables\\EloquentDataTable',
      'query' => 'Yajra\\DataTables\\QueryDataTable',
      'collection' => 'Yajra\\DataTables\\CollectionDataTable',
      'resource' => 'Yajra\\DataTables\\ApiResourceDataTable',
    ),
    'builders' => 
    array (
    ),
    'nulls_last_sql' => ':column :direction NULLS LAST',
    'error' => NULL,
    'columns' => 
    array (
      'excess' => 
      array (
        0 => 'rn',
        1 => 'row_num',
      ),
      'escape' => '*',
      'raw' => 
      array (
        0 => 'action',
      ),
      'blacklist' => 
      array (
        0 => 'password',
        1 => 'remember_token',
      ),
      'whitelist' => '*',
    ),
    'json' => 
    array (
      'header' => 
      array (
      ),
      'options' => 0,
    ),
    'callback' => 
    array (
      0 => '$',
      1 => '$.',
      2 => 'function',
    ),
  ),
);
