<?php
Class dbObj{
    /* Database connection start */
    var $servername = "localhost";
    var $username = "postgres";
    var $password = "PgAdmin";
    var $dbname = "maps";
    var $port = "5434";
    var $conn;
    function getConnstring() {
        $con = pg_connect(getenv("DATABASE_URL") or die("Connection failed: ".pg_last_error());

                    /* check connection */
                    if (pg_last_error()) {
                                    printf("Connect failed: %s\n", pg_last_error());
                                    exit();
                    } else {
                                    $this->conn = $con;
                    }
                    return $this->conn;
    }
}
 
?>
