<?php

/**
  *
  * Extension :: Feedback Data Base Structure
  *
  * v.2.0.0.0
  *
  */

$STRUCT_ADS_CATEGORY = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_title' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'   => true

	],

	'field_description' => [

		'type'   => 'text', // varchar
		'length' => 1000,
		'fill'   => true

	]

];

$STRUCT_ADS_ITEMS = [

	'field_id' => [

		'type'   => 'bignum', // bigint
		'auto'	 => true,
		'length' => 255,
		'fill'   => true

	],

	'field_ads_hash' => [

		'type'   => 'text', // varchar
		'length' => 100,
		'fill'   => true

	],

	'field_ads_category' => [

		'type'   => 'bignum', // bigint
		'length' => 255,
		'fill'   => true

	],

	'field_ads_title' => [

		'type'   => 'text', // varchar
		'length' => 150,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_ads_description' => [

		'type'   => 'text', // varchar
		'length' => 400,
		'fill'   => true,

		'index'	 => [

			'type' => 'simple'

		]

	],

	'field_ads_price' => [

		'type'   => 'num', // int
		'length' => 10,
		'fill'   => null

	],

	'field_ads_content' => [

		'type'   => 'text', // varchar
		'length' => 10000,
		'fill'   => true,

		'index'	 => [

			'type' => 'full'

		]

	],

	'field_ads_time' => [

		'type'   => 'text', // varchar
		'length' => 20,
		'fill'   => true

	],

	'field_ads_country' => [

		'type'   => 'num', // int
		'length' => 3,
		'fill'   => true

	],

	'field_sender_name' => [

		'type'   => 'text', // varchar
		'length' => 150,
		'fill'   => true

	],

	'field_sender_email' => [

		'type'   => 'text', // varchar
		'length' => 80,
		'fill'   => true

	],

	'field_sender_phone' => [

		'type'   => 'text', // varchar
		'length' => 15,
		'fill'   => null

	]

];

$STRUCT_ADS_FILES = [

	'field_id' => [

		'type'		=> 'bignum', // bigint
		'auto'		=> true,
		'length'	=> 255,
		'fill'		=> true

	],

	'field_file' => [

		'type'		=> 'text', // varchar
		'length'	=> 255,
		'fill'		=> true,

		'index'	 => [

			'type' => 'simple'

		],

	],

	'field_ads_hash' => [

		'type'		=> 'text', // varchar
		'length'	=> 100,
		'fill'		=> null

	]

];

// Compile DBX Extension Schema
$DBX_KERNEL_SCHEMA['ads_categories'] = $STRUCT_ADS_CATEGORY;
$DBX_KERNEL_SCHEMA['ads_items'] 	 = $STRUCT_ADS_ITEMS;
$DBX_KERNEL_SCHEMA['ads_files'] 	 = $STRUCT_ADS_FILES;

?>
