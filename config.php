<?php

//Application settings

//Warning: only modify the data if you are sure, this could cause malfunction

return [
	'app_name' => 'Favorite Links',
	'views_path' => 'resources/views',
	'controllers_path' => 'App\\Controllers',
	'models_path' => 'App\\Models',
	'timezone' => 'UTC',

	//Configure your database settings
	'database' => [
		'driver' => 'mysql',
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => 'mysql',
		'database' => 'favoritelinks',
		'charset'   => 'utf8',
    	'collation' => 'utf8_unicode_ci',
    	'prefix'    => '',
	],

	//Data for sessions and cookies
	'session' => [
		'name' => 'fl_session',
		'table' => 'sessions',
		'model' => \App\Models\User::class,
	],

	//Urls not need Login to access
	'excludeAuth' => [
		'/login/forgot',
		'/login/recovery',
		'/login/forgotPost',
		'/login/recoveryPost',
	],

	'mail' => [
		'host' => '',
		'port' => '',
		'from' => [
			'address' => 'fl@fl.com',
			'name' => 'FL',
		],
		'encryption' => null,
		'username' => '',
		'password' => '',
		'sendmail' => '/usr/sbin/sendmail -bs',
	],
];
