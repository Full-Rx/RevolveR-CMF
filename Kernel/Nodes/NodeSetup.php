<?php

 /*
  * 
  * RevolveR CMF Setup
  *
  * v.2.0.1.4
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

if( !$RNV->installed ) {

	$contents  = '<p>Welcome to RevolveR CMF Installer. Next step you have to define some dababase and admin settings.</p>';
 	$contents .= '<p>Please choose database name and create it first!';
 	$contents .= '<h2>Type Data Base options and administartor account data:</h2>';

	$form_parameters = [

		// Main parameters
		'id'		=> 'install-form',
		'class'		=> 'revolver__install-form revolver__new-fetch',
		'method'	=> 'post',
		'action'	=> RQST,
		'encrypt'	=> true,
		'captcha'	=> true,	
		'submit'	=> 'Submit',

		// Fieldsets add
		'fieldsets' => [

			// Fieldset contents parameters
			'fieldset_1' => [

				'title' => 'Administrator Setup',

				// Wrap fields into label
				'labels' => [

					'label_1' => [

						'title'		=> 'Admin name',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type'			=> 'input:text',
								'name'			=> 'revolver_setup_admin_name',
								'placeholder'	=> 'RevolveR admin name',
								'required'		=> true

							],

						],

					],

					'label_2' => [

						'title'		=> 'Admin email',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type'			=> 'input:email',
								'name'			=> 'revolver_setup_admin_email',
								'placeholder'	=> 'RevolveR admin email',
								'required'		=> true

							],

						],

					],

					'label_3' => [

						'title'		=> 'Admin password',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type'			=> 'input:password',
								'name'			=> 'revolver_setup_admin_password',
								'placeholder'	=> 'Repeat RevolveR admin password repeat',
								'required'		=> true

							],

						],

					],

					'label_4' => [

						'title'		=> 'Confirm password',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type'			=> 'input:password',
								'name'			=> 'revolver_setup_admin_password_confirm',
								'placeholder'	=> 'RevolveR admin password',
								'required'		=> true

							],

						],

					],

				],

			],

			'fieldset_2' => [

				'title' => 'Database Setup',
				'collapse' => true,

				'labels' => [

					'label_5' => [

						'title'		=> 'Database Name',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type'			=> 'input:text',
								'name'			=> 'revolver_setup_database_name',
								'placeholder'	=> 'Data Base Name',
								'value'			=> 'revolver',
								'required'		=> true

							],

						],

					],

					'label_6' => [

						'title'		=> 'Database MySQL server host',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type'			=> 'input:text',
								'name'			=> 'revolver_setup_database_host',
								'placeholder'	=> 'Data Base Host',
								'value'			=> 'localhost',
								'required'		=> true

							],

						],

					],

					'label_7' => [

						'title'		=> 'Database MySQL server port',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type'			=> 'input:number',
								'name'			=> 'revolver_setup_database_port',
								'placeholder'	=> 'Data Base Port',
								'value'			=> '3306',
								'required'		=> true

							],

						],

					],

					'label_8' => [

						'title'		=> 'Database MySQL user',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type' 			=> 'input:text',
								'name'			=> 'revolver_setup_database_user',
								'placeholder' 	=> 'Data Base User',
								'value'			=> 'root',
								'required'		=> true

							],

						],

					],

					'label_9' => [

						'title'		=> 'Database MySQL password',
						'access'	=> 'install',
						'auth'		=> 0,

						'fields' => [

							0 => [

								'type' 			=> 'input:password',
								'name'			=> 'revolver_setup_database_password',
								'placeholder' 	=> 'Data Base Password',
								'value'			=> 'root',
								'required'		=> true

							],

						],

					],

				],

			],

		]

	];

	$contents .= $form::build( $form_parameters );

	$title = 'RevolveR CMF :: Setup';

}
else {

	$contents  = '<p>RevolveR CMF setup process done! Welcome to you <a href="/" title="homepage">new site homepage</a>!</p>';

	$title = 'RevolveR CMF :: Setup Done!';

}

$node_data[] = [

	'title'		=> $title,
	'id'		=> 'setup',
	'route'		=> '/setup/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'published' => 1

];

if( !empty(SV['p']) ) {

	$dbx_data = [];

	if( isset(SV['p']['revolver_setup_admin_name']) ) {

		if( (bool)SV['p']['revolver_setup_admin_name']['valid'] ) {

			$user_data_name = SV['p']['revolver_setup_admin_name']['value'];

		}

	}

	if( isset(SV['p']['revolver_setup_admin_email']) ) {

		if( (bool)SV['p']['revolver_setup_admin_email']['valid'] ) {

			$user_data_email = SV['p']['revolver_setup_admin_email']['value'];

		}

	}

	if( isset(SV['p']['revolver_setup_admin_password']) ) {

		if( (bool)SV['p']['revolver_setup_admin_password']['valid'] ) {

			$user_data_password = SV['p']['revolver_setup_admin_password']['value'];

		}

	}

	if( isset(SV['p']['revolver_setup_admin_password_confirm']) ) {

		if( (bool)SV['p']['revolver_setup_admin_password_confirm']['valid'] ) {

			$user_data_password_confirm = SV['p']['revolver_setup_admin_password_confirm']['value'];

		}

	}

	if( isset(SV['p']['revolver_setup_database_host']) ) {

		if( (bool)SV['p']['revolver_setup_database_host']['valid'] ) {

			$dbx_data[ 0 ] = SV['p']['revolver_setup_database_host']['value'];

		}

	}

	if( isset(SV['p']['revolver_setup_database_user']) ) {

		if( (bool)SV['p']['revolver_setup_database_user']['valid'] ) {

			$dbx_data[ 1 ] = SV['p']['revolver_setup_database_user']['value'];

		}

	}

	if( isset(SV['p']['revolver_setup_database_password']) ) {

		if( (bool)SV['p']['revolver_setup_database_password']['valid'] ) {

			$dbx_data[ 2 ] = SV['p']['revolver_setup_database_password']['value'];

		}

	}

	if( isset(SV['p']['revolver_setup_database_port']) ) {

		if( (bool)SV['p']['revolver_setup_database_port']['valid'] ) {

			$dbx_data[ 3 ] = SV['p']['revolver_setup_database_port']['value'];

		}

	}

	if( isset(SV['p']['revolver_setup_database_name']) ) {

		if( (bool)SV['p']['revolver_setup_database_name']['valid'] ) {

			$dbx_data[ 4 ] = SV['p']['revolver_setup_database_name']['value'];

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $captcha::verify(SV['p']['revolver_captcha']['value']) ) {

				define('form_pass', 'pass');

			}

		}

	}

	// Form Passed
	$passed = null;

	// Crypt and write database config
	$db_cfgs = $_SERVER['DOCUMENT_ROOT'] .'/private/DataBase.key';

	$dbx_cfg = $dbx_data[ 0 ] .'|'. $dbx_data[ 1 ] .'|'. $dbx_data[ 2 ] .'|'. $dbx_data[ 3 ] .'|'. $dbx_data[ 4 ];

	if( $user_data_password === $user_data_password_confirm ) {

		$passed = true;	

	}
	else {

		$title = 'RevolveR CMF Warning!';

		$contents = '<p class="revolver__warning">Unable to complite setup! Insufucient data. Check all fields again and repeat submit!</p>'. $contents;

	}

	$node_data[ 0 ] = [

		'title'		=> $title,
		'id'		=> 'setup',
		'route'		=> '/setup/',
		'contents'	=> $contents,
		'teaser'	=> null,
		'footer'	=> null,
		'published' => 1

	];

	// Redirect to done stage
	if( $passed && (bool)SV['p']['identity']['validity'] && form_pass === 'pass' ) {

		// Create key
		file_put_contents( $_SERVER['DOCUMENT_ROOT'] .'/private/Domain.key', uniqid() );

		// Create data base config
		file_put_contents( $db_cfgs, $cipher::crypt('encrypt', $dbx_cfg) );

		/* Success test */
		$test = $_SERVER['DOCUMENT_ROOT'] .'/private/DataBase.key';

		if( is_readable($test) ) {

			$dbTest = file_get_contents( $test, true );

		}
		else {

			$dbTest = null;
		}

		if( $dbTest ) {

			require_once('./private/SSLConfig.php');

			// Get Data Base X API
			$dbx = new DBX(

					array_merge(

						explode('|', 

							$cipher::crypt(

								'decrypt', $dbTest

							)

						), DBX_SSL

					), $file

				);

			// Models
			$model = new Model( $dbx, $DBX_KERNEL_SCHEMA );

			// Create table index
			$dbx::query('c', 'revolver__index', $STRUCT_INDEX);

			// Create table index ratings
			$dbx::query('c', 'revolver__index_ratings', $STRUCT_INDEX_RATINGS);

			// Create table staistics :: compressed
			$dbx::query('c', 'revolver__statistics', $STRUCT_STATISTICS);

			// Create table extensions
			$dbx::query('c', 'revolver__extensions', $STRUCT_EXTENSIONS);

			// Create table files
			$dbx::query('c', 'revolver__files', $STRUCT_FILES);

			// Create table forum topics files
			$dbx::query('c', 'revolver__froom_files', $STRUCT_FROOM_FILES);

			// Create table messages files
			$dbx::query('c', 'revolver__messages_files', $STRUCT_MESSAGES_FILES);
			
			// Create table nodes
			$dbx::query('c', 'revolver__nodes', $STRUCT_NODES);

			$model::set('nodes', [

				'title'			=> 'Welcome your new RevolveR CMF based web-site!',
				'description'	=> 'RevolveR CMF Homepage',
				'content'		=> '<p>Revolver CMF installed but no any contents yet!</p> <p><a href="/node/create/">Create it first!</a></p>',
				'country'		=> 840,
				'user'			=> $user_data_name,
				'time'			=> date('d.m.Y h:i'),
				'route'			=> '/en-US/welcome/',
				'category'		=> 1,
				'published'		=> 1,
				'mainpage'		=> 1

			]);

			// Create nodes ratings
			$dbx::query('c', 'revolver__nodes_ratings', $STRUCT_NODES_RATINGS);

			// Create table users
			$dbx::query('c', 'revolver__users', $STRUCT_USER);

			$model::set('users', [

				'nickname' 				=> $user_data_name,
				'email'					=> $user_data_email,
				'password'				=> $cipher::crypt('encrypt', $user_data_password_confirm),
				'permissions'			=> 'Admin',
				'session_id'			=> 'no-id',
				'avatar'				=> 'default',
				'telephone'				=> '',
				'interface_language'	=> 'EN'

			]);

			// Create table categories
			$dbx::query('c', 'revolver__categories', $STRUCT_CATEGORIES);

			$model::set('categories', [

				'title'			=> 'Welcome',
				'description'	=> 'Welcome! RevolveR CMF Installed.'

			]);

			// Create table store categories
			$dbx::query('c', 'revolver__store_categories', $STRUCT_CATEGORIES);

			// Create table store goods
			$dbx::query('c', 'revolver__store_goods', $STRUCT_GOODS);

			// Create table store goods ratings
			$dbx::query('c', 'revolver__goods_ratings', $STRUCT_NODES_RATINGS);

			// Create table store goods
			$dbx::query('c', 'revolver__store_goods_files', $STRUCT_GOODS_FILES);

			// Create table store goods
			$dbx::query('c', 'revolver__store_comments', $STRUCT_BLOG_COMMENTS);

			// Create store comments ratings
			$dbx::query('c', 'revolver__store_comments_ratings', $STRUCT_COMMENTS_RATINGS);

			// Create store orders
			$dbx::query('c', 'revolver__store_orders', $STRUCT_STORE_ORDERS);

			// Create table forums
			$dbx::query('c', 'revolver__forums', $STRUCT_FORUMS);

			// Create table forum rooms
			$dbx::query('c', 'revolver__forum_rooms', $STRUCT_FORUM_ROOMS);

			$dbx::query('c', 'revolver__froom_comments', $STRUCT_FORUM_COMMENTS);

			$model::set('forums', [

				'title'			=> 'Welcome forum',
				'description'	=> 'Welcome! Forum enabled.'

			]);

			// Create table settings
			$dbx::query('c', 'revolver__settings', $STRUCT_SITE);

			$model::set('settings', [

				'site_brand'			=> 'R CMF',
				'site_title'			=> 'RevolveR CMF',
				'site_description'		=> 'Revolver CMF homepage',
				'site_email'			=> 'service@revolver.team',
				'site_language'			=> 840,
				'interface_language'	=> 'EN',
				'site_template'			=> 'Template'

			]);

			// Create table comments
			$dbx::query('c', 'revolver__comments', $STRUCT_COMMENTS);

			// Create comments ratings
			$dbx::query('c', 'revolver__comments_ratings', $STRUCT_COMMENTS_RATINGS);

			// Create table subscriptions
			$dbx::query('c', 'revolver__subscriptions', $STRUCT_SUBSCRIPTIONS);

			// Create table blog subscriptions
			$dbx::query('c', 'revolver__blog_subscriptions', $STRUCT_BLOG_SUBSCRIPTIONS);

			// Create table blog nodes
			$dbx::query('c', 'revolver__blog_nodes', $STRUCT_BLOG_NODES);

			// Create blog ratings
			$dbx::query('c', 'revolver__blog_ratings', $STRUCT_NODES_RATINGS);

			// Create blog comments ratings
			$dbx::query('c', 'revolver__blog_comments_ratings', $STRUCT_COMMENTS_RATINGS);
			
			// Create table blog files
			$dbx::query('c', 'revolver__blog_files', $STRUCT_BLOG_FILES);
			
			// Create table blog comments
			$dbx::query('c', 'revolver__blog_comments', $STRUCT_BLOG_COMMENTS);

			// Create table roles
			$dbx::query('c', 'revolver__roles', $STRUCT_ROLES);

			$model::set('roles', [

				'access'	=> 'preferences|node|comment|messages|categories|profile|forum|topic|blog|buy',
				'name'		=> 'Admin',

			]);

			$model::set('roles', [

				'access'	=> 'comment|messages|profile|topic|blog|buy',
				'name'		=> 'User'

			]);

			$model::set('roles', [

				'access'	=> 'node|comment|messages|categories|profile|forum|topic|blog|buy',
				'name'		=> 'Writer'

			]);

			$model::set('roles', [

				'access'	=> 'none',
				'name'		=> 'Banned'

			]);

			// Create table messages table
			$dbx::query('c', 'revolver__messages', $STRUCT_MESSAGES);

			// Send message to Admin with user_id -> 1
			$model::set('messages', [

				'user_id'	=> 1,
				'to'		=> $user_data_name,
				'from'		=> $user_data_name,
				'time'		=> date('d.m.Y h:i:s'),
				'message'	=> '<p>Welcome, '. $user_data_name .'! Now you join into administartion group and have full access to manage this website!</p>'

			]);

			// Create table wiki categories
			$dbx::query('c', 'revolver__wiki_categories', $STRUCT_CATEGORIES);

			// Create table wiki nodes
			$dbx::query('c', 'revolver__wiki_nodes', $STRUCT_WIKI_NODES);

			// Create table wiki files
			$dbx::query('c', 'revolver__wiki_files', $STRUCT_FILES);

			// Create table talk
			$dbx::query('c', 'revolver__talk', $STRUCT_TALK);

			// Create table talk
			$dbx::query('c', 'revolver__rates', $STRUCT_RATES);

			// Fix Files and directories permissons

			exec('find '. $_SERVER['DOCUMENT_ROOT'] .' -type d -exec chmod 0770 {} +'); // for sub directory
			exec('find '. $_SERVER['DOCUMENT_ROOT'] .' -type f -exec chmod 0644 {} +'); // for files inside directory

			$RKI->Email::send(

				'revolver.cmf@gmail.com', 'RevolveR CMF Installed just now', 'RevolveR CMF Installed just now on host'. site_host

			);

			header( 'Location: '. $RNV->host );

		}

	}

}

?>
