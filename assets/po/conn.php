<?php

/*
 * All database connection variables
 */
 define('DB_USER', getenv('POSTGRESQL_USER')); // db user
define('DB_PASSWORD', getenv('POSTGRESQL_PASSWORD')); // db password (mention your db password here)
define('DB_DATABASE', getenv('POSTGRESQL_DATABASE')); // database name
define('DB_HOST', getenv('POSTGRESQL_SERVICE_HOST')); // db server
define('DB_PORT', getenv('POSTGRESQL_SERVICE_PORT'));

 define('DB_CONNSTRING', 'host='.DB_HOST.' port='.DB_PORT.' dbname='.DB_DATABASE.' user='.DB_USER.' password='.DB_PASSWORD); // database name
  echo getenv('POSTGRESQL_DATABASE');
   echo getenv('POSTGRESQL_USER');
    echo getenv('POSTGRESQL_PASSWORD');
     echo getenv('POSTGRESQL_SERVICE_HOST');
?>