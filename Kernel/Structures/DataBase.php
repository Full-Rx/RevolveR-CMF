<?php

 /* 
  * 
  * RevolveR CMF Data Base schema
  *
  * v.2.0.0.5
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
  *
  */

$STRUCT_SITE = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_site_brand' => [

		'type'   => 'text',   // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_site_title' => [

		'type'   => 'text',   // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_site_description' => [

		'type'   => 'text',   // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_site_email' => [

		'type'   => 'text',   // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_site_language' => [

		'type'   => 'text',   // varchar
		'length' => 3,
		'fill'   => true

	],

	'field_interface_language' => [

		'type'   => 'text',   // varchar
		'length' => 5,
		'fill'   => true

	],

	'field_site_template' => [

		'type'	 => 'text',    // varchar
		'length' => 50,
		'fill'   => true

	]

];

$STRUCT_EXTENSIONS = [

	'field_id' => [

		'type'   => 'bignum', // int
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_name' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_cache' => [

		'type'   => 'minnum', // smallint
		'length' => 1,
		'fill'   => true

	],

	'field_enabled' => [

		'type'   => 'minnum', // smallint
		'length' => 1,
		'fill'   => true

	]

];

$STRUCT_FILES = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_name' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_node' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	]

];

$STRUCT_FROOM_FILES = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_name' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_froom' => [

		'type'   => 'bignum', // varchar
		'length' => 255,
		'fill'   => true

	]

];

$STRUCT_GOODS_FILES = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_name' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_node' => [

		'type'   => 'bignum', // varchar
		'length' => 255,
		'fill'   => true

	]

];


$STRUCT_MESSAGES_FILES = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_file' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true

	],

	'field_message_id' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	]

];

$STRUCT_BLOG_FILES = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_node' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true,

	],

	'field_name' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

];

$STRUCT_CATEGORIES = [

	'field_id' => [

		'type'   => 'bignum', // varchar
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_title' => [

		'type'   => 'text', // varchar
		'length' => 500,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_description' => [

		'type'   => 'text', // varchar
		'length' => 2500,
		'fill'   => true
	]

];

$STRUCT_FORUMS = [

	'field_id' => [

		'type'   => 'bignum', // varchar
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_title' => [

		'type'   => 'text', // varchar
		'length' => 500,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_description' => [

		'type'   => 'text', // varchar
		'length' => 2500,
		'fill'   => true
	]

];

$STRUCT_FORUM_ROOMS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_title' => [

		'type'   => 'text',   // varchar
		'length' => 150,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_description' => [

		'type'   => 'text',   // varchar
		'length' => 5000,
		'fill'   => true

	],

	'field_content' => [

		'type'   => 'text',   // varchar
		'length' => 9000,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_user' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true

	],

	'field_forum_id' => [

		'type'	 => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

];


$STRUCT_USER = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_nickname' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_email' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_password' => [

		'type'   => 'text', // varchar
		'length' => 150,
		'fill'   => true

	],

	'field_permissions' => [

		'type'   => 'text', // varchar
		'length' => 20,
		'fill'	 => true

	],

	'field_session_id' => [

		'type'   => 'text', // varchar
		'length' => 200,
		'fill'	 => null

	],

	'field_interface_language' => [

		'type'   => 'text',   // varchar
		'length' => 5,
		'fill'   => true

	],

	'field_avatar' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'	 => true

	],

	'field_telephone' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'	 => null

	]

];

$STRUCT_ROLES = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,  	  // auto increment
		'length' => 255

	],

	'field_name' => [

		'type'   => 'text', // text
		'fill'   => true,
		'length' => 255,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_access' => [

		'type'   => 'text', // text
		'fill'   => true,
		'length' => 255

	]

];

$STRUCT_MESSAGES = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255,
		'fill'	 => true

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_to' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_from' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_message' => [

		'type'   => 'text', // varchar
		'length' => 5000,
		'fill'   => true

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'	 => true

	]

];

$STRUCT_NODES = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_title' => [

		'type'   => 'text',   // varchar
		'length' => 150,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_description' => [

		'type'   => 'text',   // varchar
		'length' => 5000,
		'fill'   => true

	],

	'field_content' => [

		'type'   => 'text',   // varchar
		'length' => 9000,
		'fill'   => true

	],

	'field_country' => [

		'type'   => 'text',   // varchar
		'length' => 3,
		'fill'   => true

	],

	'field_user' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true

	],

	'field_route' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'	 => true

	],

	'field_category' => [

		'type'	 => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_published' => [

		'type'   => 'num', // int
		'length' => 1,
		'fill'   => null

	],

	'field_mainpage' => [

		'type'   => 'num', // int
		'length' => 1,
		'fill'   => null

	]

];

$STRUCT_GOODS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_title' => [

		'type'   => 'text',   // varchar
		'length' => 150,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_description' => [

		'type'   => 'text',   // varchar
		'length' => 5000,
		'fill'   => true

	],

	'field_vendor' => [

		'type'   => 'text',   // varchar
		'length' => 150,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_country' => [

		'type'   => 'text',   // varchar
		'length' => 3,
		'fill'   => true

	],

	'field_quantity' => [

		'type'   => 'bignum',   // varchar
		'length' => 255,
		'fill'   => null

	],

	'field_price' => [

		'type'   => 'num',   // varchar
		'length' => 20,
		'fill'   => true

	],

	'field_rebate' => [

		'type'   => 'num',   // varchar
		'length' => 3,
		'fill'   => null

	],

	'field_tax' => [

		'type'   => 'num',   // varchar
		'length' => 3,
		'fill'   => null

	],

	'field_delivery' => [

		'type'   => 'minnum', // big int
		'length' => 1,
		'fill'	 => null

	],

	'field_pickup' => [

		'type'   => 'minnum', // big int
		'length' => 1,
		'fill'	 => null

	],

	'field_service' => [

		'type'   => 'minnum', // big int
		'length' => 1,
		'fill'	 => null

	],

	'field_content' => [

		'type'   => 'text',   // varchar
		'length' => 9000,
		'fill'   => true

	],

	'field_user' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_category' => [

		'type'	 => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_published' => [

		'type'   => 'num', // int
		'length' => 1,
		'fill'   => null

	],


];

$STRUCT_NODES_RATINGS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true

	],

	'field_node_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_rate' => [

		'type'   => 'minnum', // big int
		'length' => 1,
		'fill'	 => true

	]

];

$STRUCT_COMMENTS_RATINGS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true

	],

	'field_comment_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_rate' => [

		'type'   => 'minnum', // big int
		'length' => 1,
		'fill'	 => true

	]

];

$STRUCT_WIKI_NODES = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_title' => [

		'type'   => 'text',   // varchar
		'length' => 150,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_description' => [

		'type'   => 'text',   // varchar
		'length' => 5000,
		'fill'   => true

	],

	'field_content' => [

		'type'   => 'text',   // varchar
		'length' => 9000,
		'fill'   => true

	],

	'field_country' => [

		'type'   => 'text',   // varchar
		'length' => 3,
		'fill'   => true

	],

	'field_user' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true

	],

	'field_route' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'	 => true

	],

	'field_category' => [

		'type'	 => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_published' => [

		'type'   => 'num', // int
		'length' => 1,
		'fill'   => null

	]

];

$STRUCT_BLOG_NODES = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_title' => [

		'type'   => 'text',   // varchar
		'length' => 150,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_description' => [

		'type'   => 'text',   // varchar
		'length' => 5000,
		'fill'   => true

	],

	'field_content' => [

		'type'   => 'text',   // varchar
		'length' => 9000,
		'fill'   => true

	],

	'field_user' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_route' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'	 => true

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true

	],

	'field_published' => [

		'type'   => 'num', // int
		'length' => 1,
		'fill'   => null

	]

];

$STRUCT_COMMENTS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_node_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]
	],

	'field_country' => [

		'type'   => 'text', // varchar
		'length' => 3,
		'fill'   => true

	],

	'field_user_name' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_content' => [

		'type'   => 'text', // varchar
		'length' => 9000,
		'fill'   => true

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true

	],

	'field_published' => [

		'type' 	 => 'num', // int
		'length' => 1,
		'fill'	 => null

	]

];

$STRUCT_BLOG_COMMENTS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_node_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]
	],

	'field_content' => [

		'type'   => 'text', // varchar
		'length' => 9000,
		'fill'   => true

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true

	],

	'field_published' => [

		'type' 	 => 'num', // int
		'length' => 1,
		'fill'	 => null

	]

];

$STRUCT_FORUM_COMMENTS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_froom_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]
	],

	'field_content' => [

		'type'   => 'text', // varchar
		'length' => 9000,
		'fill'   => true

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true

	],

	'field_published' => [

		'type' 	 => 'num', // int
		'length' => 1,
		'fill'	 => null

	]

];

$STRUCT_SUBSCRIPTIONS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_node_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_user_name' => [

		'type'   => 'text', // varchar
		'length' => 150,
		'fill'	 => true

	],

	'field_user_email' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	]

];

$STRUCT_BLOG_SUBSCRIPTIONS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_node_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_user_name' => [

		'type'   => 'text', // varchar
		'length' => 150,
		'fill'	 => true

	],

	'field_user_email' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	]

];

$STRUCT_STATISTICS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_date' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_time' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true

	],

	'field_track' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_route' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'	 => true

	],

	'field_user_agent' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'	 => true,

	],

	'field_ip' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'	 => true,

		'index' => [

			'type' => 'full'

		]

	],
	
	'field_referer' => [

		'type'   => 'text', // varchar
		'length' => 500,
		'fill'	 => true,
		'index'	 => [

			'type' => 'simple'

		]

	]

];

$STRUCT_STORE_ORDERS = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_customer_name' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_customer_last_name' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_customer_email' => [

		'type'   => 'text', // varchar
		'length' => 60,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_customer_telephone' => [

		'type'   => 'text', // varchar
		'length' => 20,
		'fill'   => true

	],

	'field_customer_address' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'   => true

	],

	'field_customer_comment' => [

		'type'   => 'text', // varchar
		'length' => 5000,
		'fill'   => true,

	],

	'field_processed' => [

		'type'   => 'minnum', // big int
		'length' => 1,
		'fill'	 => true

	],

	'field_paid' => [

		'type'   => 'minnum', // big int
		'length' => 1,
		'fill'	 => true

	]

];

$STRUCT_INDEX = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_uri' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'   => true

	],

	'field_host' => [

		'type'   => 'text', // varchar
		'length' => 200,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_date' => [

		'type'   => 'text', // varchar
		'length' => 20,
		'fill'   => true

	],

	'field_hash' => [

		'type'   => 'text', // varchar
		'length' => 50,
		'fill'   => true

	],

	'field_title' => [

		'type'   => 'text', // varchar
		'length' => 600,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_description' => [

		'type'   => 'text', // varchar
		'length' => 250,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_content' => [

		'type'   => 'text', // varchar
		'length' => 9000,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	]

];

$STRUCT_INDEX_RATINGS = [

	'field_id' => [

		'type'   => 'bignum', // big int
		'auto'   => true,     // auto increment
		'length' => 255

	],

	'field_user_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true

	],

	'field_index_id' => [

		'type'   => 'bignum', // big int
		'length' => 255,
		'fill'	 => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_rate' => [

		'type'   => 'minnum', // big int
		'length' => 1,
		'fill'	 => true

	]

];

$STRUCT_TALK = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_user_id' => [

		'type'   => 'bignum', // bigint
		'length' => 255,
		'fill'   => true

	],

	'field_user_nickname' => [

		'type'   => 'text', // varchar
		'length' => 200,
		'fill'   => true

	],

	'field_message' => [

		'type'   => 'text', // varchar
		'length' => 3000,
		'fill'   => true

	],

	'field_audio' => [

		'type'   => 'text', // varchar
		'length' => 200,
		'fill'   => null

	]

];

$STRUCT_RATES = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_currency' => [

		'type'   => 'text', // varchar
		'length' => 5,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_date' => [

		'type'   => 'text', // varchar
		'length' => 10,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_value' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'   => true

	]

];

$STRUCT_TEST = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_test' => [

		'type'   => 'text', // varchar
		'length' => 255,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	]

];

// Compare DBX Schema
$DBX_KERNEL_SCHEMA = [

	'settings'		     	 => $STRUCT_SITE,
	'extensions'	    	 => $STRUCT_EXTENSIONS,

	'statistics'			 => $STRUCT_STATISTICS,

	'forums'		   		 => $STRUCT_FORUMS,
	'forum_rooms'	     	 => $STRUCT_FORUM_ROOMS,
	'froom_files'	    	 => $STRUCT_FROOM_FILES,
	'froom_comments'     	 => $STRUCT_FORUM_COMMENTS,

	'categories'	    	 => $STRUCT_CATEGORIES,

	'nodes'			    	 => $STRUCT_NODES,
	'nodes_ratings'			 => $STRUCT_NODES_RATINGS,
	'files'			    	 => $STRUCT_FILES,
	'comments'		    	 => $STRUCT_COMMENTS,
	'comments_ratings'		 => $STRUCT_COMMENTS_RATINGS,
	'subscriptions'	    	 => $STRUCT_SUBSCRIPTIONS,

	'blog_nodes'	    	 => $STRUCT_BLOG_NODES,
	'blog_ratings'			 => $STRUCT_NODES_RATINGS,
	'blog_files'	    	 => $STRUCT_BLOG_FILES,
	'blog_comments'	    	 => $STRUCT_BLOG_COMMENTS,
	'blog_comments_ratings'	 => $STRUCT_COMMENTS_RATINGS,
	'blog_subscriptions'	 => $STRUCT_BLOG_SUBSCRIPTIONS,

	'wiki_categories'   	 => $STRUCT_CATEGORIES,
	'wiki_nodes'	    	 => $STRUCT_WIKI_NODES,
	'wiki_files'	    	 => $STRUCT_FILES,

	'store_categories'		 => $STRUCT_CATEGORIES,
	'store_goods_files'		 => $STRUCT_GOODS_FILES,
	'store_goods'			 => $STRUCT_GOODS,
	'goods_ratings'			 => $STRUCT_NODES_RATINGS,
	'store_comments'		 => $STRUCT_BLOG_COMMENTS,
	'store_comments_ratings' => $STRUCT_COMMENTS_RATINGS,
	'store_orders' 			 => $STRUCT_STORE_ORDERS,

	'users'			    	 => $STRUCT_USER,
	'roles'			    	 => $STRUCT_ROLES,

	'messages'		    	 => $STRUCT_MESSAGES,
	'messages_files'    	 => $STRUCT_MESSAGES_FILES,

	// Pick index
	'index'					 => $STRUCT_INDEX,
	'index_ratings'			 => $STRUCT_INDEX_RATINGS,

	// Talk
	'talk'					 => $STRUCT_TALK,

	// Exchange rates
	'rates'					 => $STRUCT_RATES,

	'test'			    	 => $STRUCT_TEST

];

?>
