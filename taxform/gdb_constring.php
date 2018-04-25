<?php

/*
 * All database connection variables
 */
 define('DB_USER', "postgres"); // db user
define('DB_PASSWORD', "PgAdmin"); // db password (mention your db password here)
define('DB_DATABASE', "maps"); // database name
define('DB_HOST', "localhost"); // db server
define('DB_PORT', "5434");

 define('DB_CONNSTRING', 'host='.DB_HOST.' port='.DB_PORT.' dbname='.DB_DATABASE.' user='.DB_USER.' password='.DB_PASSWORD); // database name

?>