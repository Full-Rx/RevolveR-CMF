<?php

 /*
  * RevolveR CMF Kernel
  *
  * v.2.0.1.5
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

// Kernel version
define('rr_version', '2.0.1.5');

// Apply config
require_once('./private/ksettings.php');

// X64 guest number
define('BigNumericX64', 9223372036854775806);

// Debug mode
if( DEBUG ) {

	ini_set('error_reporting', E_WARNING | E_STRICT | E_DEPRECATED | E_USER_DEPRECATED | E_PARSE | E_RECOVERABLE_ERROR);


}
else {

	error_reporting(0);

}

// Hide GET parameters for interface and store it
$uri_segment = explode('?', $_SERVER['REQUEST_URI']);

if( isset($uri_segment[ 1 ]) ) {

	$_SERVER['REQUEST_URI'] = str_ireplace( 

		[ $uri_segment[ 1 ], '?', '&' ], 

		[ '', '', '' ], 

		trim( 

			$_SERVER['REQUEST_URI'] 

		) 

	);

}

// Set request and passway
define('RQST', $_SERVER['REQUEST_URI'] );

define('PASS', explode('/', RQST));

// Default extensions settings
$esettings = null;

// Default language is English
$ipl = 'EN';

// Templates cache directory
define('TCache', $_SERVER['DOCUMENT_ROOT'] .'/cache/tplcache/');

function detectSSL(): ?bool {

	// check HTTPS protocol
	if( isset($_SERVER['HTTPS']) ) {

		if( 'off' !== strtolower($_SERVER['HTTPS']) ) {

			return true;

		}

		if( 1 === (int)$_SERVER['HTTPS'] ) {

			return true;

		}

	}

	if( isset($_SERVER['HTTP_X_FORWARDED_SSL']) ) {

		if( 'on' === $_SERVER['HTTP_X_FORWARDED_SSL'] ) {

			return true;

		}

	}

	if( isset($_SERVER['HTTP_X_FORWARDED_PORT']) ) {

		if( 443 === (int)$_SERVER['HTTP_X_FORWARDED_PORT'] ) {

			return true;

		}

	}

	if( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) ) {

		if( strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https' ) {

			return true;

		}

	}

	if( isset($_SERVER['REQUEST_SCHEME']) ) {

		if( strtolower($_SERVER['REQUEST_SCHEME'] === 'https') ) {

			return true;

		}

	}

	// check server port
	if( isset($_SERVER['SERVER_PORT']) ) {

		if( 443 === (int)$_SERVER['SERVER_PORT'] ) {

			return true;

		}

	}

	// non-SSL
	return null;

}

// Set URI prefix
define('uri_prefix', detectSSL() ? 'https://' : 'http://');

define('site_host', strtolower(uri_prefix . $_SERVER['HTTP_HOST']));

// Check for SSL domain mirror is available
ini_set('session.cookie_httponly', 1);

if( detectSSL() ) {

	// Enable SSL cookie
	ini_set('session.cookie_secure', 1);

}
else {

	$License = 'https://'. $_SERVER['HTTP_HOST'] .'/License.txt';

	if( (int)file_get_contents( $License, null, stream_context_create( [ 'http' => [ 'timeout' => 1 ] ] ) ) > 0 ) {

		header('Location: https://'. $_SERVER['HTTP_HOST'] );

	}

	ini_set('session.cookie_secure', 0);

}

if( in_array(session_status(), [ PHP_SESSION_DISABLED, PHP_SESSION_NONE ], true) ) { 

	ini_set('session.referer_check', $_SERVER['HTTP_HOST']);
	ini_set('session.cookie_samesite', 'Strict');
	ini_set('session.cache_limiter', '');
	ini_set('session.use_strict_mode', 1);
	ini_set('session.use_cookies', 1);
	ini_set('session.use_only_cookies', 1);
	ini_set('session.use_trans_sid', 1);

	session_name('__RevolveR_sessid');

	session_start();

}

// Register extensions 
$extensionsTranslations = [];
$extensionsScripts = []; 
$extensionsStyles  = [];
$extensionsRoutes  = [];

$extensions = [];

$file = new File();

foreach( $file::getDir('./Extensions/') as $extDir ) {

	if(
		is_readable('./Extensions/'. $extDir .'/DataBase.php') && 
		is_readable('./Extensions/'. $extDir .'/Translations.php')

	) {

	require_once('./Extensions/'. $extDir .'/DataBase.php');
	require_once('./Extensions/'. $extDir .'/Translations.php');

	$extensions[] = $extDir;

	}

}

// Register extension
define('REGISTERED_EXTENSIONS', $extensions);
define('etranslations', $extensionsTranslations);

// Relocate to root 
if( RQST === '/index.php' ) {

	header('Location: '. site_host);

}

/* Language library */
$lang = new Language(); 

/* Calendar futures */
$calendar = new Calendar();

/* Notifications */
$notify = new Notifications($ipl);

/* Captcha */
$captcha = new Captcha($ipl, $notify, detectSSL());

/* Encryption */
$cipher = new Cipher();

/* Variables dispatch */
$D = new SecureVariablesDispatcher($notify, detectSSL());

$V = $D::get();

// auth = null by null
if( !isset( $V['c']['authorization'] ) ) {

	$V['c']['authorization'] = null;

}

define('SV', count( $V ) > 0 ? $V : null);

/* Crop given markup by length, close opened tags, secure attribites */
$markup = new Markup();

// Read data base config file
$xkey = $file::readFile( $_SERVER['DOCUMENT_ROOT'] .'/private/DataBase.key' );

if( $xkey ) {

	$db_config = $xkey;

} 
else {

	// Setup  mode variables
	$db_config = null;
	$not_found = null;

	define('BRAND', 'RevolveR');
	define('TITLE', 'RevolveR CMF not installed');
	define('DESCRIPTION', 'RevolveR CMF setup stage');

	define('Auth', 0);
	define('ACCESS', 'setup');

	define('main_language', 'en');

	$authFlag = null;

	$primary_language = 'EN';

}

/* Is framework installed */
if( strlen( $db_config ) > 0 ) {

	require_once('./private/SSLConfig.php');

	define('INSTALLED', true);

	$ci = '';

	// Get Data Base X API
	$dbx = new DBX(

		array_merge(

			explode('|',

				$cipher::crypt(

					'decrypt',

					$db_config

				)

			), DBX_SSL

		), $file

	);

	// Models
	$model = new Model( $dbx, $DBX_KERNEL_SCHEMA );

	// Store main domain settings
	$vsettings = iterator_to_array(

		$model::get( 'settings', [

			'criterion' => 'id::*',

			'bound'		=> [

				1

			],

			'course'	=> 'forward',
			'sort' 		=> 'id'

		])

	)['model::settings'];

	// Extensions configuration
	$esettings = iterator_to_array(

		$model::get( 'extensions', [

			'criterion' => 'id::*',
			'course'	=> 'forward',
			'sort' 		=> 'id'

		])

	)['model::extensions'];


	if( $vsettings ) {

		$set = $vsettings[0];

		define('BRAND', $set['site_brand']);

		define('TITLE', $set['site_title']);

		define('DESCRIPTION', $set['site_description']);

		define('TEMPLATE', $set['site_template']);

		define('default_email', $set['site_email']);

		define('LANGUAGE', $set['site_language']);

		// Get translate into stack
		$primary_language = $lang::getLanguageData( LANGUAGE )['hreflang'];

		$ipl = $set['interface_language'];

		define('iLANGUAGE', $ipl);

	}

	if( isset( SV['c']['usertoken'] ) ) {

		$token_explode = explode('|', $cipher::crypt('decrypt', SV['c']['usertoken']));

		// Get Access
		$access = iterator_to_array(

			$model::get('user->role', [

				'criterion' => 'users::email::'. $token_explode[0]

			])

		);

		if( $access['model::user->role'] ) {

			$access = $access['model::user->role'][0];

			if( $access['users']['password'] === $token_explode[1] ) {

				define('USER', [

					'language' => $access['users']['interface_language'],
					'session'  => $access['users']['session_id'],
					'name'     => $access['users']['nickname'],
					'password' => $access['users']['password'],
					'avatar'   => $access['users']['avatar'],
					'email'    => $access['users']['email'],
					'id'	   => $access['users']['id']

				]);

				$acl = []; 

				$acl['role'] = $access['users']['permissions'];

				$restrictions = explode('|',  $access['roles']['access']);

				foreach( $restrictions as $a ) {

					$acl[ $a ] = true;

				}

				if( $acl['role'] !== 'Banned' ) {

					if( $_SESSION['session_token'] === USER['session'] ) {

						define('ACCESS', $acl);

						define('FORM_ACCESS', [

							'permissions' => ACCESS,
							'auth' 		  => 1

						]);

					}
					else {

						define('ACCESS', 'none');

						define('FORM_ACCESS', [

							'permissions' => [

								ACCESS

							],

							'auth' => 0

						]);

					}

				}
				else {

					define('ACCESS', 'none');

					define('FORM_ACCESS', [

						'permissions'  => [

							'messages' => 1

						],

						'auth' => 1

					]);

				}

			}

		}

	}
	else {

		define('ACCESS', 'none');

		define('FORM_ACCESS', [

			'permissions'	=> [

				'register'	=> 1,
				'recovery'	=> 1,
				'comment'	=> 1,
				'buy'		=> 1,
				'auth'		=> 1

			],

			'auth' => 0

		]);

		define('USER', [

			'id'		=> BigNumericX64,
			'language'	=> iLANGUAGE,
			'email'		=> null,
			'name'		=> null

		]);

	}

	if( defined('ACCESS') ) {

		define('ROLE', isset( ACCESS['role'] ) ? ACCESS['role'] : 'none' );

	}

	// Show only contents for choosen contents language in preferences
	define('main_language', $primary_language);

	// Switch language betwin default web site settings and user defined interface language
	$ipl = USER['language']; 

	/* Extensions routing */ 
	foreach( REGISTERED_EXTENSIONS as $ext ) {

		if( is_readable('./Extensions/'. $ext .'/Extension.php') ) {

			require_once('./Extensions/'. $ext .'/Extension.php');

		}

	}

	// Apply config
	require_once('./private/Config.php');

	$not_found = true;

	$all_nodes = iterator_to_array(

		$model::get('nodes', [

			'criterion' => 'id::*',
			'course'	=> 'backward',
			'sort' 		=> 'id'

		])

	)['model::nodes'];

	if( count( $all_nodes ) > 0 ) {

		define('CONTENTS_FLAG', true);

	}
	else {

		define('CONTENTS_FLAG', false);

	}

	if( isset( SV['g']['page'] ) ) {

		define('pagination', [

			'offset'  => (int)SV['g']['page']['value'] * $nodes_per_page,
			'curent'  => SV['g']['page']['value'],
			'allow'   => 1

		]);

		$not_found = null;

	}
	else {

		if( RQST === '/' || PASS[ 1 ] === 'blog' ) {

			define('pagination', [

				'offset'  => 0,
				'curent'  => 0,
				'allow'   => 1

			]);

		}
		else {

			define('pagination', [

				'offset'  => 0,
				'curent'  => 0,
				'allow'   => 0

			]);

		}

		$not_found = true;

	}

	foreach( $all_nodes as $node => $n ) {

		if( RQST === $n['route'] ) {			

			if( !empty( $n['title'] ) ) {

				$title = $n['title'];

			}

			if( !empty( $n['description'] ) ) {

				$site_description = $n['description'];

			}

			define('NODE_ID', (int)$n['id'] ?? null );

			$not_found = null;

			$main_language = $lang::getLanguageData( $n['country'] )['hreflang'];

		}
		else if( RQST !== '/' ) {

			foreach( main_nodes as $k ) {

				if( $k['route'] === RQST ) {

					$not_found = null;

					$site_description = $k['descr'];

				}

			}

		}

	}

}
else {

	// Main config
	require_once('./private/Config.php');

	// Setup mode
	define('INSTALLED', null);

	$ci = 'not-installed';

	// Default template
	define('TEMPLATE', 'Template');

	define('pagination', [

		'offset'  => 0,
		'curent'  => 0,
		'allow'   => 0

	]);

	define('FORM_ACCESS', [

		'permissions' => [

			'install' => 1

		],

		'auth' => 0

	]);

	if( RQST === '/' ) {

		header('Location: '. site_host . '/setup/');

	}

}

// Register extensions settings 
define('EXTENSIONS_SETTINGS', !$esettings ? [[null, null, null]] : $esettings);

/* Authorization */
if( INSTALLED ) {

	$auth = new Auth( $model, $cipher, detectSSL() );

}

/* RevolveR Route Init */
$route = new Route(main_nodes);

/* RevolveR Menu */
$menu = new Menu(main_nodes, 'ul');

/* RevolveR Node Init */
$node = new Node();

/* RevolveR Email support */
$mail = new eMail();

/* User Agent Info */
$uaInfo = new DetectUserAgent();

if( INSTALLED ) {

	$record = new Statistics( $model );

	$record::writeHit( isset( USER['name'] ) ? USER['name'] : 'guest' );

	if( isset(SV['c']['authorization']) ) {

		if( (bool)SV['c']['authorization'] ) {

			if( USER['session'] !== SV['s']['session_token'] || empty( SV['s']['session_token'] ) ) {

				$auth::logout(); // logoff when another authorization success

			}

		}

	}

	define('Status_DBX', [ $dbx::$queries_counter, $dbx::$queries_cache_counter, $dbx::$queries_hash_counter, $dbx::$connect_counter ]);

}

/* HTML Form Builder Helper */
$form = new HTMLFormBuilder($ipl);

/* Conclude caches and templates */
$resolve = new Conclude( $file );

// Auth flag
if( !defined('Auth') ) {

	define('Auth', (int)USER['id'] !== (int)BigNumericX64 ? 1 : 0);	

}

// URI template cache
$TCache	= !(bool)Auth ? $resolve::getCacheFile( $uri_segment ) : 0;

if( !(bool)$TCache ) {

	/* Interface resources optimization */
	$scripts = $resolve::publicResourcesCacheRefresh('script', scripts);

	$istyles = $resolve::publicResourcesCacheRefresh('style', styles);

}

// Prevent loading route if access not grants
if( !(bool)Auth ) {

	if( defined('ROUTE') ) {

		if( isset( ROUTE['edit'] ) ) {

			if( ROUTE['edit'] ) {

				header( 'Location: '. site_host .'/user/auth/' );

			}

		}

	}
	else {

		if( PASS[ count( PASS ) - 2 ] === 'edit' ) {

			header( 'Location: '. site_host .'/user/auth/' );

		}

	}

}

/*  Clean stage */
if( INSTALLED ) {

	$authFlag = (int)USER['id'] !== (int)BigNumericX64 ? true : null;

}

/* Redirected notifications */
if( SV ) {

	if( isset(SV['g']['notification']) ) {

		$vnotify = explode('^', SV['g']['notification']['value']);

		switch( $vnotify[0] ) {

			case 'accept-privacy-policy':

				$captcha::setCookie('accepted-privacy-policy', session_id(), time() + 86400, '/');

				$notify::set('status', '<div class="privacy-policy-notification">Privacy policy accepted! You can continue to use and enjoy our services.</div>', null);

				break;

			case 'authorized':

				if( Auth ) {

					$notify::set('status', '<p class="auth-notification"><img src="'. ( USER['avatar'] === 'default' ? '/public/avatars/default.png' : USER['avatar'] ) .'" /><br />'. USER['name'] .', welcome. '. ROLE .' rights granted for you.</p>', null);

				}

				break;

			case 'soon':
				
				if( Auth ) {

					$notify::set('status', '<p class="auth-notification"><img src="'. ( USER['avatar'] === 'default' ? '/public/avatars/default.png' : USER['avatar'] ) .'" /><br />'. USER['name'] .', goodbye.</p>', null);

					$notify::set('inactive', '<p class="auth-notification">Authorization session off now.</p>', null);

				}

				break;

			case 'profile-updated':

				if( Auth && ROLE === 'Admin') {

					$info = explode('-', $vnotify[1]);

					$notify::set('status', '<p class="auth-notification">User id-'. strip_tags( $info[0] ) .' profile updated. Granted permissions of '. strip_tags( $info[1] ) .'.</p>', null);

				}

				break;

			case 'profile-not-updated':

				if( Auth ) {

					$notify::set('inactive', 'Profile not updated');

				}

				break;	

			case 'category-updated':

				if( Auth ) {

					$notify::set('status', '<p class="auth-notification">Category '. strip_tags( $vnotify[1] ) .' updated.</p>', null);

				}

				break;

			case 'category-erased':

				if( Auth ) {

					$notify::set('notice', '<p class="auth-notification">Category '. strip_tags( $vnotify[1] ) .' erased.</p>', null);

				}

				break;

			case 'comment-added':

				switch( strip_tags($vnotify[1]) ) {

					case 'subscribed':

						$notify::set('active', 'Comment added. You subscribed to updates via email');

						break;

					case 'not-subscribed':

						$notify::set('inactive', 'Comment added. You not subscribed to updates');

						break;

					}

				break;

			case 'comment-updated':

				if( Auth ) {

					$notify::set('notice', '<p class="auth-notification">Comment '. strip_tags( $vnotify[1] ) .' updated.</p>', null);

				}

				break;

			case 'comment-erased':

				if( Auth ) {

					$notify::set('notice', '<p class="auth-notification">Comment '. strip_tags( $vnotify[1] ) .' erased.</p>', null);

				}

				break;

			case 'node-created':

				if( Auth ) {

					$notify::set('status', 'New node created now');

				}

				break;

			case 'node-updated':

				if( Auth ) {

					$info = explode('-', $vnotify[1]);

					$npublished = (bool)$info[1] ? 'published' : 'unpublished';
					$nmainpagep = (bool)$info[2] ? 'attached to mainpage' : 'detached from mainpage'; 

					$notify::set('status', '<p class="auth-notification">Node '. strip_tags($info[0]) .' updated, '. $npublished .', '. $nmainpagep .'.</p>', null);

				}

				break;

			case 'node-erased':

				if( Auth ) {

					$info = explode('-', $vnotify[1]);

					$notify::set('status', '<p class="auth-notification">Node '. strip_tags($info[0]) .' erased now.</p>', null);

				}

				break;

			case 'conflict-reason':

				switch( strip_tags($vnotify[1]) ) {

					case 'URI':

						$notify::set('notice', 'URI conflict');

						$notify::set('inactive', 'No changes applyed');

						break;

				}

				break;

			case 'authorization-reazon':

				$notify::set('inactive', 'Seems, authorization session ends');

				break;

			case 'nothing-to-show':

				$notify::set('inactive', 'Nothing to show');

				break;

			case 'no-changes':

				$notify::set('inactive', 'No changes applyed');

				break;

			default:

				break;

		}

	}

}

if( !defined('ROUTE') ) {

	/* Forum routes */
	if( PASS[ 1 ] === 'forum' ) {

		define('ROUTE', [

			'route' => '/forum/',
			'node'	=> '#forum',
			'type'	=> 'node',
			'ext'	=> null

		]);

	}

	/* Blog routes */
	if( PASS[ 1 ] === 'blog' ) {

		define('ROUTE', [

			'route' => '/blog/',
			'node'	=> '#blog',
			'type'	=> 'node',
			'ext'	=> null

		]);

	}

	/* Wiki routes */
	if( PASS[ 1 ] === 'wiki' ) {

		define('ROUTE', [

			'route' => '/wiki/',
			'node'	=> '#wiki',
			'type'	=> 'node',
			'ext'	=> null

		]);

		$not_found = null;

	}

	/* Store routes */
	if( PASS[ 1 ] === 'store' ) {

		define('ROUTE', [

			'route' => '/store/',
			'node'	=> '#store',
			'type'	=> 'node',
			'ext'	=> null

		]);

		$not_found = null;

	}

}

// 404
define('N', $not_found);

// RKI :: RevolveR Kernel Interface Model
require_once( $_SERVER['DOCUMENT_ROOT'] .'/Kernel/KernelInterface.php' );

// RNV :: RevolveR Node Variables Model
require_once( $_SERVER['DOCUMENT_ROOT'] .'/Kernel/NodeVariables.php' );

if( !(bool)$TCache ) {

	/* Main routing */
	require_once( $_SERVER['DOCUMENT_ROOT'] .'/Kernel/Modules/Switch.php' );

	/* Extensions routing */ 
	foreach( REGISTERED_EXTENSIONS as $ext ) {

		if( is_readable('./Extensions/'. $ext .'/Switch.php') ) {

			require_once('./Extensions/'. $ext .'/Switch.php');

		}

	}

	########################### TPL VARS ##########################

	// Website branding
	$brand = !empty( BRAND ) ? BRAND : 'RevolveR';

	// Website title
	$title = !empty( $title ) ? $title : TITLE;

	// Website description
	$descr = !empty( $site_description ) ? $site_description : DESCRIPTION;

	// Is allowed to to show on the fronpage
	$flag_main_node = null;

	// Nodes titles
	foreach( main_nodes as $mn => $v ) {

		if( RQST === $v['route'] && RQST !== '/' ) {

			$title = $v['title'];

		}

		if( defined('ROUTE') ) {

			if( ROUTE['route'] === $v['route'] ) {

				$flag_main_node = true;

			}

		}

	}

	// Edit's'routes titles
	if( !defined('ROUTE') ) {

		if( is_array( $node_data ) ) {

			foreach( $node_data as $n ) {

				if( PASS[ count( PASS ) - 2 ] === 'edit' && explode('edit/', RQST)[ 0 ] === $n['route'] ) {

					$title = TRANSLATIONS[ $ipl ]['Edit'] .' :: '. $n['title']; 

				}

			}

			if( PASS[ 1 ] === 'comment' && PASS[ 3 ] === 'edit' ) {

				$title = TRANSLATIONS[ $ipl ]['Edit'] .' :: comment &#8226; '. PASS[ 2 ];

			}

		}

	}
	else {

		if( isset( PASS[ 3 ] ) ) {

			if( PASS[ 1 ] === 'categories' && PASS[ 3 ] === 'edit' ) {

				$title = TRANSLATIONS[ $ipl ]['Edit'] .' :: category &#8226; '. PASS[ 2 ];

			}

		}

	}

	// Main page title
	if( RQST === '/' ) {

		$title = !empty(TITLE) ? TITLE : $title;

	} 
	else if( PASS[1] === 'blog' && !empty(PASS[ 2 ]) ) {

		$bi = iterator_to_array(

			$model::get( 'blog_nodes', [

				'criterion' => 'route::'. rtrim(explode('/edit/', RQST)[ 0 ], '/') .'/',

				'bound'		=> [

					1

				],

				'course'	=> 'backward',
				'sort' 		=> 'id'

			])

		)['model::blog_nodes'][0];

		$title = $bi['title'];

		$descr = $bi['description'];


	} 
	else if( PASS[ 1 ] === 'store' && PASS[ 2 ] === 'goods' && is_numeric(PASS[ 3 ]) ) {

		$si = iterator_to_array(

			$model::get( 'store_goods', [

				'criterion' => 'id::'. (int)PASS[ 3 ],

				'bound'		=> [

					1

				],

				'course'	=> 'backward',
				'sort' 		=> 'id'

			])

		)['model::store_goods'][0];

		$title = $si['title'];

		$descr = $si['description'];


	} 
	else if( PASS[ 1 ] === 'wiki' && isset(PASS[ 3 ]) ) {

		$wi = iterator_to_array(

			$model::get('wiki_nodes', [

				'criterion' => 'route::'. RQST,
				'course'	=> 'forward',
				'sort'		=> 'id'

			])

		)['model::wiki_nodes'][0];


		$title = $wi['title'];

		$descr = $wi['description'];

	}

	// Add pagination index to title
	if( isset($uri_segment[ 1 ]) ) {

		$page_number = explode('page=', $uri_segment[ 1 ] );

		$title .= isset( $page_number[ 1 ] ) ? ' '. TRANSLATIONS[ $ipl ]['page'] .' '. $page_number[ 1 ] : '';

	}

	// URI
	$host = site_host . RQST;

}

// RKV :: RevolveR Kernel Variables Model
require_once( $_SERVER['DOCUMENT_ROOT'] .'/Kernel/KernelVariables.php' );

if( INSTALLED ) {

	// performs automatic database modification, 
	// caches clean and fix rights 
	// when Kernel version upgrades

	require_once($_SERVER['DOCUMENT_ROOT'] . '/Kernel/Upgrade.php');

}

if( defined('ROUTE') ) {

	// Services
	if( ROUTE['type'] === 'service' ) {

		if( defined('serviceOutput') ) {

			if( (bool)$TCache ) {

				$resolve::ConcludeCache( serviceOutput['ctype'], $TCache );

			}
			else {

				$resolve::Сonclude( serviceOutput['ctype'] );

			}

		}

	}
	else if( ROUTE['type'] === 'node' ) {

		if( (bool)$TCache ) {

			$resolve::ConcludeCache( 'text/html', $TCache );

		}
		else {

			require(

				$_SERVER['DOCUMENT_ROOT'] . ltrim( $resolve::Template(), '.' )

			);

			$resolve::Сonclude( 'text/html', $uri_segment );

		}

	}

}
else {

	if( (bool)$TCache ) {

		$resolve::ConcludeCache( 'text/html', $TCache );

	}
	else {

		require(

			$_SERVER['DOCUMENT_ROOT'] . ltrim( $resolve::Template(), '.' )

		);

		$resolve::Сonclude( 'text/html', $uri_segment );

	}

}

?>
