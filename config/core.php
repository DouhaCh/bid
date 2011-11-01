<?php
	define('LOG_ERROR', 2);

	Configure::write('Routing.admin', 'admin');
	
	$session = array(
		'save' => 'php',
		'cookie' => 'PHPSESSID',
		'timeout' => '300',
		'start' => true,
		'checkAgent' => true,
		//'table' => 'cake_sessions',
		//'database' => 'default'
	);
	Configure::write('Session', $session);

	$security = array(
		'level' => 'medium',
		'salt' => '07a6b2214c954ba069dbf8196d315f83a30baef9'
	);
	Configure::write('Security', $security);

	Cache::config('default', array('engine' => 'File', 'path'=>TMP.'cache'));
	Cache::config('short', array(
		'engine' => 'File', 
		'path'=>TMP.'cache',
	    'serialize' => true,
		'duration'=> '+3 days',
	));
	
	Configure::write('viewPaths', 'asgas');

?>