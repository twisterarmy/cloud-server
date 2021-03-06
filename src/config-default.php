<?php

// VERSION
define('SERVER_VERSION', '0.1');

// COMMON
define('PROJECT_HOST', '');
define('PROJECT_DIR', __DIR__);

// DB
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_DATABASE', '');
define('DB_USER', '');
define('DB_PASSWORD', '');

// TWISTER
define('TWISTER_HOST', 'localhost');
define('TWISTER_PORT', 28332);
define('TWISTER_SSL', true);
define('TWISTER_PROTOCOL', '');
define('TWISTER_USER', '');
define('TWISTER_PASSWORD', '');

define('TWISTER_SEQ_START_FROM', 0); // Start initial DHT revision from

// MEMCACHE
define('MEMCACHE_HOST', 'localhost');
define('MEMCACHE_PORT', 11211);
define('MEMCACHE_COMPRESS', false);
define('MEMCACHE_DHT_AVATAR_TIMEOUT', 3600);
define('MEMCACHE_DHT_PROFILE_TIMEOUT', 3600);

// COMMON
define('APPLICATION_ALLOW_REGISTRATION', true);
define('APPLICATION_FOLLOW_ON_REGISTRATION', []);

define('APPLICATION_MAX_POST_FEED', 50);

define('APPLICATION_USER_REGISTRATION_TIMEOUT', 86400);

define('APPLICATION_MODULE_USERS_LIMIT', 5);