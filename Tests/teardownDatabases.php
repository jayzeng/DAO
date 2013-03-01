<?php
require_once( "DbTestConfiguration.php" );

$teardownSqlDir = __DIR__ . "/Sql/Teardown/";
$sqlFiles = glob( $teardownSqlDir . "*.sql" );

foreach( $sqlFiles as $sqlFile ) {

    $cmd = sprintf( 'mysql -u %s -p\'%s\' -h %s < %s',
                    DB_USERNAME,
                    DB_PASSWORD,
                    DB_HOST,
                    $sqlFile );

    system($cmd, $retVal );
}

