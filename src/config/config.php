<?php
// Configuration for Database
/* define('DB_HOST', 'aarm4gwirek2yc.cfklfzizd1ma.us-west-2.rds.amazonaws.com');
define('DB_USER', 'ventsellProg');
define('DB_PASS', 'lnBQhPDqRMWLVRks');
define('DB_NAME', 'ventsell');
define('DB_PORT', 3306);
 */
define('DB_HOST', 'localhost');
define('DB_USER', getenv('DBUSER'));
define('DB_PASS', getenv('DBPASS'));
define('DB_NAME', getenv('DBNAME'));
define('DB_PORT', 3306);

// Secret for JWT Auth
define('SECRET', 'ewiueyq8ewqu7e627918039213123nmbasas923704912-13yGSIUAG698723GYU23@#Y98EWE!!EYWQ8DHS');
