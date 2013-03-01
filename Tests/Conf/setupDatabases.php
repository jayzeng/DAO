<?php
require_once( "DbTestConfiguration.php" );

$setupSqlDir = __DIR__.DIRECTORY_SEPARATOR.'Sql/Setup/';

$cmd = sprintf( 'mysql -u %s -p\'%s\' -h %s < %s',
                DB_USERNAME,
                DB_PASSWORD,
                DB_HOST,
                $setupSqlDir.'DbTestSetup.sql' );

return system($cmd, $retVal);

