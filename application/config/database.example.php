<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS - EXAMPLE
| -------------------------------------------------------------------
| This is an example database configuration file.
| Copy this to database.php and update with your actual database credentials.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
*/
$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost', // Your database host
	'username' => 'your_username', // Your database username
	'password' => 'your_password', // Your database password
	'database' => 'piggery', // Your database name
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);