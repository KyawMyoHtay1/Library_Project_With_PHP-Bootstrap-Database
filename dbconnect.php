<?php

if (!function_exists('library_env')) {
    function library_env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false || $value === '') {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('library_mysql_config')) {
    function library_mysql_config()
    {
        $config = [
            'host' => library_env('MYSQLHOST', library_env('DB_HOST', '127.0.0.1')),
            'port' => (int) library_env('MYSQLPORT', library_env('DB_PORT', 3306)),
            'user' => library_env('MYSQLUSER', library_env('DB_USERNAME', 'root')),
            'password' => library_env('MYSQLPASSWORD', library_env('DB_PASSWORD', '')),
            'database' => library_env('MYSQLDATABASE', library_env('DB_DATABASE', 'librarydb')),
        ];

        $databaseUrl = library_env('MYSQL_URL', library_env('DATABASE_URL'));

        if ($databaseUrl) {
            $parts = parse_url($databaseUrl);

            if ($parts !== false) {
                if (!empty($parts['host'])) {
                    $config['host'] = $parts['host'];
                }

                if (!empty($parts['port'])) {
                    $config['port'] = (int) $parts['port'];
                }

                if (!empty($parts['user'])) {
                    $config['user'] = $parts['user'];
                }

                if (array_key_exists('pass', $parts)) {
                    $config['password'] = $parts['pass'];
                }

                if (!empty($parts['path'])) {
                    $config['database'] = ltrim($parts['path'], '/');
                }
            }
        }

        return $config;
    }
}

if (!isset($connect) || !($connect instanceof mysqli)) {
    $databaseConfig = library_mysql_config();
    $connect = mysqli_connect(
        $databaseConfig['host'],
        $databaseConfig['user'],
        $databaseConfig['password'],
        $databaseConfig['database'],
        $databaseConfig['port']
    );

    if (!$connect) {
        error_log('Database connection failed: ' . mysqli_connect_error());
        die('Database connection failed. Check your environment variables.');
    }

    mysqli_set_charset($connect, 'utf8mb4');
}

?>
