<?php

// Initialization file for connecting to your database, autoloading the classes and functionality to remember user.
session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' => 'HOST NAME GOES HERE',
		'username' => 'USERNAME GOES HERE',
		'password' => 'PASSWORD GOES HERE',
		'db' => 'DATABASE NAME GOES HERE'
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token' 
	)
);

spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

include 'includes/header.php';

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

	if($hashCheck->count()) {
		$hashCheck->first()->user_id;
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}