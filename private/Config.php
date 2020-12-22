<?php

 /*
  * 
  * RevolveR Kernel configuration
  *
  * v.2.0.0.8
  *
  *
  *
  *
  *
  *			          ^
  *			         | |
  *			       @#####@
  *			     (###   ###)-.
  *			   .(###     ###) \
  *			  /  (###   ###)   )
  *			 (=-  .@#####@|_--"
  *			 /\    \_|l|_/ (\
  *			(=-\     |l|    /
  *			 \  \.___|l|___/
  *			 /\      |_|   /
  *			(=-\._________/\
  *			 \             /
  *			   \._________/
  *			     #  ----  #
  *			     #   __   #
  *			     \########/
  *
  *
  *
  * Developer: Dmitry Maltsev
  *
  * License: Apache 2.0
  *
  */

// Website defaults
$name  = 'New website';
$title = 'Homepage';
$descr = 'Homepage index';

// Nodes per page 
$nodes_per_page = 5;

// Kernel scripts
define('scripts', array_merge([

		[

			'path' => '/Interface/revolver.js',
			'name' => 'revolver kernel',
			'part' => 'kernel',
			'min'  => 1

		],

		[

			'path' => '/Interface/timeThemes.js',
			'name' => 'revolver interface time futures',
			'part' => 'module',
			'min'  => 1

		],

		[

			'path' => '/Interface/interface.js',
			'name' => 'revolver interface',
			'part' => 'module',
			'min'  => 1

		]

	], $extensionsScripts )

);

// Interface styles
define('styles', array_merge([

		[

			'path'  => '/Interface/revolver.css',
			'name'  => 'revolver kernel',
			'part'  => 'kernel',
			'min'   => 1,
			'defer' => 1

		],

		[

			'path'  => '/Interface/revolver-interface.css',
			'name'  => 'revolver interface',
			'part'  => 'kernel',
			'min'   => 1,
			'defer' => 1

		]

	], $extensionsStyles )

);

/* Main framework routing */
define('main_nodes', array_merge([

		// Homepage
		TRANSLATIONS[ $ipl ]['Home'] => [

			'title' => TRANSLATIONS[ $ipl ]['Home'],
			'route' => '/',
			'node'	=> '#homepage',
			'id'	=> 0,

			'param_check' => [

				'menu' => 1

			],

			'type'	=> 'node'

		],

		// Create node
		TRANSLATIONS[ $ipl ]['Create'] => [

			'title' => TRANSLATIONS[ $ipl ]['Create node'],
			'route' => '/node/create/',
			'node'  => '#create',
			'id'	=> 'create',

			'param_check' => [

				'auth'     => 1,
				'menu'     => 1,
				'isAdmin'  => 1,
				'isWriter' => 1

			],

			'type'	=> 'node',

		],

		// Create node
		TRANSLATIONS[ $ipl ]['Create Wiki Node'] => [

			'title' => TRANSLATIONS[ $ipl ]['Create Wiki Node'],
			'route' => '/wiki/create/',
			'node'  => '#wiki_create',
			'id'	=> 'wiki_create',

			'param_check' => [

				'menu'     => 0,

			],

			'type'	=> 'node',

		],

		// Create node
		TRANSLATIONS[ $ipl ]['Moderation'] => [

			'title' => TRANSLATIONS[ $ipl ]['Moderation'],
			'route' => '/moderation/',
			'node'  => '#moderation',
			'id'	=> 'moderation',

			'param_check' => [

				'auth'     => 1,
				'menu'     => 1,
				'isAdmin'  => 1,
				'isWriter' => 1

			],

			'type'	=> 'node',

		],

		// User profile
		TRANSLATIONS[ $ipl ]['User'] => [

			'title' => TRANSLATIONS[ $ipl ]['Account profile'],
			'descr'	=> TRANSLATIONS[ $ipl ]['User area'],
			'rel'	=> 'bookmark',

			'param_check' => [

				'auth' => 1,
				'menu' => 1

			],

			'route' => '/user/',
			'node'  => '#user',
			'type'	=> 'node',
			'id'	=> 1

		],

		// Messages
		TRANSLATIONS[ $ipl ]['Messages'] => [

			'title' => TRANSLATIONS[ $ipl ]['Private Messages'],
			'rel'	=> 'bookmark',

			'param_check' => [

				'auth' 	=> 1,
				'menu' 	=> 1

			],

			'route' => '/user/messages/',
			'node'  => '#user',
			'type'	=> 'node',
			'id'	=> 'messages'

		],

		// Authorization
		TRANSLATIONS[ $ipl ]['Login'] => [

			'title' => TRANSLATIONS[ $ipl ]['Login'],
			'descr'	=> TRANSLATIONS[ $ipl ]['User login area'],

			'param_check' => [

				'installed' => 1,
				'auth' 		=> 0,
				'menu' 		=> 1

			],

			'route' => '/user/auth/',
			'node'  => '#user',
			'type'	=> 'node',
			'id'	=> 2

		],

		// Registration
		TRANSLATIONS[ $ipl ]['Register'] => [

			'title' => TRANSLATIONS[ $ipl ]['Account registration'],
			'descr'	=> TRANSLATIONS[ $ipl ]['User register area'],

			'param_check' => [

				'installed' => 1,
				'auth' 		=> 0,
				'menu' 		=> 1

			],

			'route' => '/user/register/',
			'node'  => '#user',
			'type'	=> 'node',
			'id'	=> 3

		],

		// Recovery account
		TRANSLATIONS[ $ipl ]['Recovery'] => [

			'title' => TRANSLATIONS[ $ipl ]['Account recovery'],
			'descr'	=> TRANSLATIONS[ $ipl ]['User recovery area'],

			'param_check' => [

				'installed' => 1,
				'auth' 		=> 0,
				'menu' 		=> 1

			],

			'route' => '/user/recovery/',
			'node'  => '#user',
			'type'	=> 'node',
			'id'	=> 4

		],

		// Dashboard
		TRANSLATIONS[ $ipl ]['Dashboard'] => [

			'title' => TRANSLATIONS[ $ipl ]['Dashboard'],

			'param_check' => [

				'isAdmin' => 1,
				'auth'    => 1,
				'menu'    => 1

			],

			'route'	=> '/dashboard/',
			'node' 	=> '#dashboard',
			'type'	=> 'node',
			'id'  	=> 5

		],

		// Dashboard
		TRANSLATIONS[ $ipl ]['Terminal'] => [

			'title' => TRANSLATIONS[ $ipl ]['Terminal'],

			'param_check' => [

				'installed' => 1,
				'isAdmin'	=> 1,
				'auth'		=> 1,
				'menu'		=> 1

			],

			'route'		=> '/terminal/',
			'node'		=> '#terminal',
			'id'		=> 'terminal',
			'type'		=> 'node',

		],

		// Attendance
		TRANSLATIONS[ $ipl ]['Attendance'] => [

			'title' => TRANSLATIONS[ $ipl ]['Attendance'],

			'param_check' => [

				'installed' => 1,
				'isAdmin'   => 1,
				'auth'      => 1,
				'menu'      => 1

			],

			'route'	=> '/attendance/',
			'node' 	=> '#attendance',
			'id'   	=> 'attendance',
			'type'	=> 'node',

		],

		// Categories
		TRANSLATIONS[ $ipl ]['Categories'] => [

			'title' => TRANSLATIONS[ $ipl ]['Categories'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Categories'],

			'param_check' => [

				'installed' => 1,
				'menu'      => 1

			],

			'route'	=> '/categories/',
			'node' 	=> '#categories',
			'type'	=> 'node',
			'id'   	=> 'categories'

		],

		// WIKI
		TRANSLATIONS[ $ipl ]['Wiki'] => [

			'title' => TRANSLATIONS[ $ipl ]['Wiki'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Wiki'],

			'param_check' => [

				'menu'      => 1,

			],

			'route'	=> '/wiki/',
			'node' 	=> '#wiki',
			'type'	=> 'node',
			'id'   	=> 'wiki'

		],

		// Blog
		TRANSLATIONS[ $ipl ]['Blog'] => [

			'title' => TRANSLATIONS[ $ipl ]['Blog'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Blog'],

			'param_check' => [

				'menu'      => 1,

			],

			'route'	=> '/blog/',
			'node' 	=> '#blog',
			'type'	=> 'node',
			'id'   	=> 'blog'

		],

		// Store
		TRANSLATIONS[ $ipl ]['Store'] => [

			'title' => TRANSLATIONS[ $ipl ]['Store'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Store'],

			'param_check' => [

				'menu'      => 1,

			],

			'route'	=> '/store/',
			'node' 	=> '#store',
			'type'	=> 'node',
			'id'   	=> 'store'

		],

		// Chat
		TRANSLATIONS[ $ipl ]['Talk'] => [

			'title' => TRANSLATIONS[ $ipl ]['Talk'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Talk'],

			'param_check' => [

				'menu'      => 1,

			],

			'route'	=> '/talk/',
			'node' 	=> '#talk',
			'type'	=> 'node',
			'id'   	=> 'talk'

		],

		// Store basket
		TRANSLATIONS[ $ipl ]['Basket'] => [

			'title' => TRANSLATIONS[ $ipl ]['Basket'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Basket'],

			'param_check' => [

				'menu'      => 0,

			],

			'route'	=> '/basket/',
			'node' 	=> '#basket',
			'type'	=> 'node',
			'id'   	=> 'basket'

		],

		// Store orders
		TRANSLATIONS[ $ipl ]['Orders'] => [

			'title' => TRANSLATIONS[ $ipl ]['Orders'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Orders'],

			'param_check' => [

				'auth'     => 1,
				'menu'     => 1,
				'isAdmin'  => 1,
				'isWriter' => 1

			],

			'route'	=> '/orders/',
			'node' 	=> '#orders',
			'type'	=> 'node',
			'id'   	=> 'orders'

		],

		// Forum
		TRANSLATIONS[ $ipl ]['Forum'] => [

			'title' => TRANSLATIONS[ $ipl ]['Forum'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Forum'],

			'param_check' => [

				'menu'      => 1,

			],

			'route'	=> '/forum/',
			'node' 	=> '#forum',
			'type'	=> 'node',
			'id'   	=> 'forum'

		],

		// search engine service
		TRANSLATIONS[ $ipl ]['Pick'] => [

			'title' => TRANSLATIONS[ $ipl ]['Pick'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Pick'],

			'param_check' => [

				'menu'		=> 1

			],

			'route'  => '/pick/',
			'node'   => '#pick',
			'type'	 => 'node',
			'id'	 => 'pick',

		],

		/* Services notes
		TRANSLATIONS[ $ipl ]['Services'] => [

			'title' => TRANSLATIONS[ $ipl ]['Services'],
			'descr'	=> 'Company services',

			'param_check' => [

				'menu' 		=> 1

			],

			'route'	=> '/services-en/',
			'node' 	=> '#services-en',
			'type'	=> 'node',
			'id'   	=> 'services-en'

		],
		*/

		// Services RU
		'Услуги' => [

			'title' => 'Услуги',
			'descr'	=> 'Услуги компании',

			'param_check' => [

				'menu' 		=> 1

			],

			'route'	=> '/services-ru/',
			'node' 	=> '#services-ru',
			'type'	=> 'node',
			'id'   	=> 'services-ru'

		],

		// Privacy notes
		TRANSLATIONS[ $ipl ]['Privacy notes'] => [

			'title' => TRANSLATIONS[ $ipl ]['Privacy notes'],
			'descr'	=> TRANSLATIONS[ $ipl ]['Privacy notes'],

			'param_check' => [

				'menu' 		=> 0,
				'hidden' 	=> 1

			],

			'route'	=> '/privacy/',
			'node' 	=> '#privacy',
			'type'	=> 'node',
			'id'   	=> 'privacy-notes',


		],

		// Logout
		TRANSLATIONS[ $ipl ]['Logout'] => [

			'title' => TRANSLATIONS[ $ipl ]['Account logout'],

			'param_check' => [

				'installed' => 1,
				'auth' 		=> 1,
				'menu' 		=> 1

			],

			'route' => '/logout/',
			'geta'  => 'notification=soon',
			'node'  => '#user',
			'type'	=> 'node',
			'id'	=> 6

		],

		/* Service routes */

		// Terminal service
		'terminal-s' => [

			'title' => 'Terminal service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/terminal-s/',
			'node'   => '#terminal-s',
			'type'	 => 'service',
			'id'	 => 'terminal-s',

		],

		/* Dispatch routes */

		// Comments dispatch
		'comments-d' => [

			'title' => 'Comment dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/comments-d/',
			'node'   => '#comments-d',
			'type'	 => 'service',
			'id'	 => 'comments-d',

		],

		// Comments forum dispatch
		'forum-comments-d' => [

			'title' => 'Forum comment dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/forum-comments-d/',
			'node'   => '#forum-comments-d',
			'type'	 => 'service',
			'id'	 => 'forum-comments-d',

		],

		// Comments blog dispatch
		'blog-comments-d' => [

			'title' => 'Blog comment dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/blog-comments-d/',
			'node'   => '#blog-comments-d',
			'type'	 => 'service',
			'id'	 => 'blog-comments-d',

		],

		// Comments store dispatch
		'store-comments-d' => [

			'title' => 'Store comment dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/store-comments-d/',
			'node'   => '#store-comments-d',
			'type'	 => 'service',
			'id'	 => 'store-comments-d',

		],

		// Comments store edit dispatch
		'store-comments-edit-d' => [

			'title' => 'Store comment edit dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/store-comments-edit-d/',
			'node'   => '#store-comments-edit-d',
			'type'	 => 'service',
			'id'	 => 'store-comments-edit-d',

		],


		// Contents dispatch
		'contents-d' => [

			'title' => 'Contents dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/contents-d/',
			'node'   => '#contents-d',
			'type'	 => 'service',
			'id'	 => 'contents-d',

		],

		// Contents dispatch
		'topic-d' => [

			'title' => 'Forum topic dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/topic-d/',
			'node'   => '#topic-d',
			'type'	 => 'service',
			'id'	 => 'topic-d',

		],

		// Contents dispatch
		'blog-d' => [

			'title' => 'Blog item dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/blog-d/',
			'node'   => '#blog-d',
			'type'	 => 'service',
			'id'	 => 'blog-d',

		],

		// Category dispatch
		'category-d' => [

			'title' => 'Category dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/category-d/',
			'node'   => '#category-d',
			'type'	 => 'service',
			'id'	 => 'category-d',

		],

		// Store category dispatch
		'store-category-d' => [

			'title' => 'Store category dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/store-category-d/',
			'node'   => '#store-category-d',
			'type'	 => 'service',
			'id'	 => 'store-category-d',

		],

		// Store goods dispatch
		'store-goods-d' => [

			'title' => 'Store goods dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/store-goods-d/',
			'node'   => '#store-goods-d',
			'type'	 => 'service',
			'id'	 => 'store-goods-d',

		],

		// Store goods edit dispatch
		'store-goods-edit-d' => [

			'title' => 'Store goods dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/store-goods-edit-d/',
			'node'   => '#store-goods-edit-d',
			'type'	 => 'service',
			'id'	 => 'store-goods-edit-d',

		],

		// Forum containers dispatch
		'forum-d' => [

			'title' => 'Forum dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/forum-d/',
			'node'   => '#forum-d',
			'type'	 => 'service',
			'id'	 => 'forum-d',

		],

		// Forum rooms dispatch
		'forum-room-d' => [

			'title' => 'Forum rooms dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/forum-room-d/',
			'node'   => '#forum-room-d',
			'type'	 => 'service',
			'id'	 => 'forum-room-d',

		],


		// Category dispatch
		'user-d' => [

			'title' => 'User dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/user-d/',
			'node'   => '#user-d',
			'type'	 => 'service',
			'id'	 => 'user-d',

		],

		// Category dispatch
		'wiki-d' => [

			'title' => 'Wiki dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/wiki-d/',
			'node'   => '#wiki-d',
			'type'	 => 'service',
			'id'	 => 'wiki-d',

		],

		// Category dispatch
		'wiki-node-d' => [

			'title' => 'Wiki node dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/wiki-node-d/',
			'node'   => '#wiki-node-d',
			'type'	 => 'service',
			'id'	 => 'wiki-node-d',

		],

		// Rating dispatch
		'rating-d' => [

			'title' => 'Contents rating dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/rating-d/',
			'node'   => '#rating-d',
			'type'	 => 'service',
			'id'	 => 'rating-d',

		],

		// Quick edit dispatch
		'quedit-d' => [

			'title' => 'Contents quick edit dispatch service',

			'param_check' => [

				'menu'		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/quedit-d/',
			'node'   => '#quedit-d',
			'type'	 => 'service',
			'id'	 => 'quedit-d',

		],

		/* Service routes */

		// secure service
		'secure' => [

			'title' => 'Secure service',

			'param_check' => [

				'menu'		=> 0,
				'hidden' 	=> 1

			],

			'route'  => '/secure/',
			'node'   => '#secure',
			'type'	 => 'service',
			'id'	 => 'secure',

		],

		// search service
		'search' => [

			'title' => 'Search service',

			'param_check' => [

				'menu'		=> 0,
				'hidden' 	=> 1

			],

			'route'  => '/search/',
			'node'   => '#search',
			'type'	 => 'service',
			'id'	 => 'search',

		],

		// serarch engine crawler
		'picker' => [

			'title' => 'Search engine crawler',

			'param_check' => [

				'menu'		=> 0,
				'hidden' 	=> 1

			],

			'route'  => '/picker/',
			'node'   => '#picker',
			'type'	 => 'service',
			'id'	 => 'picker',

		],

		// Talker service
		'talker' => [

			'title' => 'Talker service',

			'param_check' => [

				'menu'		=> 0,
				'hidden' 	=> 1

			],

			'route'  => '/talker-s/',
			'node'   => '#talker',
			'type'	 => 'service',
			'id'	 => 'talker',

		],

		// preview service
		'preview' => [

			'title' => 'Preview service',

			'param_check' => [

				'menu' 		=> 0,
				'hidden'	=> 1

			],

			'route'  => '/preview/',
			'node'   => '#preview',
			'type'	 => 'preview',
			'id'	 => 'preview',

		],

		// sitemap service
		'sitemap' => [

			'title' => 'Sitemap service',

			'param_check' => [

				'menu' 		=> 0,
				'hidden' 	=> 1

			],

			'route'  => '/sitemap/',
			'node'   => '#sitemap',
			'type'	 => 'service',
			'id'	 => 'sitemap',

		],

		// Recent contents aggregator service
		'aggregator' => [

			'title' => 'Aggregator service',

			'param_check' => [

				'menu' 		=> 0,
				'hidden' 	=> 1

			],

			'route'  => '/aggregator/',
			'node'   => '#aggregator',
			'type'	 => 'service',
			'id'	 => 'aggregator',

		],

		// Manifest service
		'manifest' => [

			'title' => 'Manifest service',

			'param_check' => [

				'menu' 		=> 0,
				'hidden' 	=> 1

			],

			'route'  => '/manifest/',
			'node'   => '#manifest',
			'type'	 => 'service',
			'id'	 => 'manifest',

		],

		// Manifest service
		'resources' => [

			'title' => 'Resources service',

			'param_check' => [

				'menu' 		=> 0,
				'hidden' 	=> 1

			],

			'route'  => '/resources/',
			'node'   => '#resources',
			'type'	 => 'service',
			'id'	 => 'resources',

		],

		'install' => [

			'title' => 'RevolveR CMF Setup',

			'param_check' => [

				'auth' => 0,
				'menu' => 0

			],

			'route' => '/setup/',
			'node'	=> '#setup',
			'type'	=> 'node',
			'id'	=> 'setup'

		]

	], $extensionsRoutes )

);

?>
