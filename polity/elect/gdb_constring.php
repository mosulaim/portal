<?php

/*
 * All database connection variables
 */
 define('DB_USER', getenv('POSTGRESQL_USER')); // db user
define('DB_PASSWORD', getenv('POSTGRESQL_PASSWORD')); // db password (mention your db password here)
define('DB_DATABASE', getenv('POSTGRESQL_DATABASE')); // database name
define('DB_HOST', getenv('POSTGRESQL_SERVICE_HOST')); // db server
define('DB_PORT', getenv('POSTGRESQL_SERVICE_PORT'));

 define('DB_CONNSTRING', 'host='.DB_HOST.' port=5432 dbname=mapdb user=postgresql password=PgAdmin'); // database name

?>