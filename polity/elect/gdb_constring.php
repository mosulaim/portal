<?php

/*
 * All database connection variables
 */
 define('DB_USER', getenv('OPENSHIFT_POSTGRESQL_DB_USERNAME')); // db user
define('DB_PASSWORD', getenv('OPENSHIFT_POSTGRESQL_DB_PASSWORD')); // db password (mention your db password here)
define('DB_DATABASE', getenv('OPENSHIFT_GEAR_NAME')); // database name
define('DB_HOST', getenv('OPENSHIFT_POSTGRESQL_DB_HOST')); // db server
define('DB_PORT', getenv('OPENSHIFT_POSTGRESQL_DB_PORT'));

 define('DB_CONNSTRING', 'host='.DB_HOST.' port='.DB_PORT.' dbname='.DB_DATABASE.' user='.DB_USER.' password='.DB_PASSWORD); // database name

?>