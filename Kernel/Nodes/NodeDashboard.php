<?php

 /*
  * RevolveR Dashboard Node
  *
  * v.2.0.1.3
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

if( isset( SV['p'] ) && ROLE === 'Admin' )  {

	$actions = [];

	if( isset( SV['p']['revolver_settings_brand'] ) ) {

		if( (bool)SV['p']['revolver_settings_brand']['valid'] ) {

			$xbrand = SV['p']['revolver_settings_brand']['value'];

		}

	}

	if( isset( SV['p']['revolver_settings_title'] ) ) {

		if( (bool)SV['p']['revolver_settings_title']['valid'] ) {

			$xtitle = SV['p']['revolver_settings_title']['value'];

		}

	}

	if( isset( SV['p']['revolver_settings_description']) ) {

		if( (bool)SV['p']['revolver_settings_description']['valid'] ) {

			$xdescription = SV['p']['revolver_settings_description']['value'];

		}

	}

	if( isset( SV['p']['revolver_settings_email'] ) ) {

		if( (bool)SV['p']['revolver_settings_email']['valid'] ) {

			$xemail = SV['p']['revolver_settings_email']['value']; 

		}

	}

	if( isset( SV['p']['revolver_settings_country_code'] ) ) {

		if( (bool)SV['p']['revolver_settings_country_code']['valid'] ) {

			$xplanguage = SV['p']['revolver_settings_country_code']['value'];

		}

	}

	if( isset( SV['p']['interface_language'] ) ) {

		if( (bool)SV['p']['interface_language']['valid'] ) {

			$xilanguage = SV['p']['interface_language']['value'];

		}

	}

	if( isset( SV['p']['revolver_settings_template'] ) ) {

		if( (bool)SV['p']['revolver_settings_template']['valid'] ) {

			$xtemplate = SV['p']['revolver_settings_template']['value'][0];

		}

	}


	if( isset( SV['p']['revolver_settings_icache'] ) ) {

		if( (bool)SV['p']['revolver_settings_icache']['valid'] ) {

			$actions[] = 'icache';

		}

	}

	if( isset( SV['p']['revolver_settings_dbcache'] ) ) {

		if( (bool)SV['p']['revolver_settings_dbcache']['valid'] ) {

			$actions[] = 'dbcache';

		}

	}

	if( isset( SV['p']['revolver_settings_tcache'] ) ) {

		if( (bool)SV['p']['revolver_settings_tcache']['valid'] ) {

			$actions[] = 'tcache';

		}

	}

	if( isset( SV['p']['revolver_settings_dbreindex'] ) ) {

		if( (bool)SV['p']['revolver_settings_dbreindex']['valid'] ) {

			$actions[] = 'reindex';

		}

	}

	if( isset( SV['p']['revolver_settings_dboptimize'] ) ) {

		if( (bool)SV['p']['revolver_settings_dboptimize']['valid'] ) {

			$actions[] = 'optimize';

		}

	}

	if( isset( SV['p']['revolver_settings_dbalter'] ) ) {

		if( (bool)SV['p']['revolver_settings_dbalter']['valid'] ) {

			$actions[] = 'alter';

		}

	}

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $RKI->Captcha::verify( SV['p']['revolver_captcha']['value'] ) ) {

				if( (bool)SV['p']['identity']['validity'] ) {

					$RKI->Model::set('settings', [

						'id'					=> 1,
						'criterion'				=> 'id',

						'site_brand'			=> $xbrand,
						'site_title'			=> $xtitle,
						'site_description'		=> $xdescription,
						'site_email'			=> $xemail,

						'site_language'			=> $xplanguage,
						'site_template'			=> $xtemplate,

						'interface_language'	=> $xilanguage

					]);

				}

				$RKI->Notify::set('status', '<div>Settings applyed</div>', null);

				// Apply optimizations
				if( count($actions) > 0 ) {

					foreach( $actions as $a ) {

						switch( $a ) {

							case 'dbcache':

								$RKI->Notify::set('status', '<div>Data Base cache will be updated when using.</div>', null);

								foreach( $RKI->File::getDir($_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache/') as $f ) {

									unlink( $_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache/' . $f );

								}

								rmdir($_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache');
								mkdir($_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache', 0775);

								break;

							case 'tcache':

								$RKI->Notify::set('status', '<div>Template cache will be updated when using.</div>', null);

								foreach( $RKI->File::getDir($_SERVER['DOCUMENT_ROOT'] . '/cache/tplcache/') as $f ) {

									unlink( $_SERVER['DOCUMENT_ROOT'] . '/cache/tplcache/' . $f );

								}

								rmdir($_SERVER['DOCUMENT_ROOT'] . '/cache/tplcache');
								mkdir($_SERVER['DOCUMENT_ROOT'] . '/cache/tplcache', 0775);

								break;

							case 'icache':

								$RKI->Notify::set('status', '<div>Interface cache refreshed.</div>', null);

								foreach( $RKI->File::getDir($_SERVER['DOCUMENT_ROOT'] . '/cache/scripts/') as $f ) {

									unlink( $_SERVER['DOCUMENT_ROOT'] . '/cache/scripts/' . $f );

								}

								foreach( $RKI->File::getDir($_SERVER['DOCUMENT_ROOT'] . '/cache/styles/') as $f ) {

									unlink( $_SERVER['DOCUMENT_ROOT'] . '/cache/styles/' . $f );

								}

								rmdir($_SERVER['DOCUMENT_ROOT'] . '/public/cache/scripts');
								rmdir($_SERVER['DOCUMENT_ROOT'] . '/public/cache/styles');

								mkdir($_SERVER['DOCUMENT_ROOT'] . '/public/cache/scripts', 0775);
								mkdir($_SERVER['DOCUMENT_ROOT'] . '/public/cache/styles', 0775);

								break;

							// Make refresh indexes of Data Base tables structure with schema diff
							case 'reindex':

								$notify::set('status', '<div>Data Base indexes refreshed.</div>', null);

								foreach( $DBX_KERNEL_SCHEMA as $tbl_n => $tbl_f ) {

									$dbx::query('index', 'revolver__'. $tbl_n,  $DBX_KERNEL_SCHEMA[ $tbl_n ]);

									$RKI->Notify::set('active', '<p>Table revolver::['. $tbl_n .'] indexes refreshed.</p>', null);

								}

								break;

							// Make optimization of INNO DB tables
							case 'optimize':

								$RKI->Notify::set('status', '<div>Data Base optimizations success.</div>', null);

								foreach( $DBX_KERNEL_SCHEMA as $tbl_n => $tbl_f ) {

									$STRUCT['extra_select_sql'] = 'ALTER TABLE `revolver__'. $tbl_n .'` ENGINE="InnoDB";';

									$dbx::query('p', 'revolver__'. $tbl_n, $DBX_KERNEL_SCHEMA[ $tbl_n ]); // be carefull becuase this query unescaped

									$RKI->Notify::set('inactive', '<p>Table revolver::['. $tbl_n .'] optimized.</p>', null);

								}

								break;

							// Make modify of Data Base tables structure with schema diff
							case 'alter':

								$RKI->Notify::set('notice', '<div>Data Base structure modified.</div>', null);

								rmdir($_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache');
								mkdir($_SERVER['DOCUMENT_ROOT'] . '/cache/dbcache', 0777);

								foreach( $DBX_KERNEL_SCHEMA as $tbl_n => $tbl_f ) {

									$dbx::query('alter', 'revolver__'. $tbl_n, $DBX_KERNEL_SCHEMA[ $tbl_n ]);

									$RKI->Notify::set('active', '<p>Table revolver::['. $tbl_n .'] altered.</p>', null);

								}

								$RKI->Notify::set('status', '<div>Data Base cache will be updated when using.</div>', null);

								break;

						}

					}

				}

			}

		}

	}

}

$settings = iterator_to_array(

	$RKI->Model::get('settings', [

		'criterion'	=> 'id::1',

		'bound'		=> [

			1,

		],

		'course' => 'backward',
		'sort'	 => 'id'

	])

)['model::settings'];

if( $settings ) {

	$set = $settings[ 0 ];

	$form_parameters = [

		'id'		=> 'node-dashboard-form',
		'class'		=> 'revolver__node-dashboard-form revolver__new-fetch',
		'action'	=> '/dashboard/',
		'method'	=> 'post',
		'encrypt'	=> true,
		'captcha'	=> true,
		'submit'	=> 'Set',

		// Tabs
		'tabs' => [

			'tab_1' => [

				// Tab title
				'title'  => 'Main settings',
				'active' => true,

				// Include fieldsets
				'fieldsets' => [

					// Fieldset contents parameters
					'fieldset_1' => [

						'title' => 'Primary website settings',
						
						// Wrap fields into label
						'labels' => [

							'label_1' => [

								'title'		=> 'Website brand',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'			=> 'input:text',
										'name'			=> 'revolver_settings_brand',
										'placeholder'	=> 'Website brand',

										'required'		=> true,

										'value'			=> $set['site_brand']

									],

								],

							],

							'label_2' => [

								'title'  => 'Website title',
								'access' => 'preferences',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type'			=> 'input:text',
										'name'			=> 'revolver_settings_title',
										'placeholder'	=> 'Website title',

										'required'		=> true,

										'value'			=> $set['site_title']

									],

								],

							],

							'label_3' => [

								'title'  => 'Website description',
								'access' => 'preferences',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type'			=> 'input:text',
										'name'			=> 'revolver_settings_description',
										'placeholder'	=> 'Website description',

										'required'		=> true,

										'value'			=> $set['site_description']

									],

								],

							],

							'label_4' => [

								'title'  => 'Website service email',
								'access' => 'preferences',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type'			=> 'input:text',
										'name'			=> 'revolver_settings_email',
										'placeholder'	=> 'Website service email',

										'required'		=> true,

										'value'			=> $set['site_email']

									],

								],

							],

						],

					],

				],

			],

			'tab_2' => [

				// Tab title
				'title'  => 'Main language settings',

				// Include fieldsets
				'fieldsets' => [

					// Fieldset contents parameters
					'fieldset_2' => [

						'title' => 'Website inteface language'

					],

					// Fieldset contents parameters
					'fieldset_3' => [

						'title' => 'Website contents language by default'

					],

				],

			],

			'tab_3' => [

				// Tab title
				'title'  => 'Template settings',

				// Include fieldsets
				'fieldsets' => [

					// Fieldset contents parameters
					'fieldset_4' => [

						'title' => 'Main template by default',
						
						// Wrap fields into label
						'labels' => [

							'label_7' => [

								'title'		=> 'Template',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'select',
										'name'		=> 'revolver_settings_template',

										'required'	=> true,

										'value'		=> $set['site_template']

									],

								],

							],

						],

					],

				],

			],

			'tab_4' => [

				// Tab title
				'title'  => 'Performance',

				// Include fieldsets
				'fieldsets' => [

					// Fieldset caches parameters
					'fieldset_5' => [

						'title' => 'Caches reload',
						
						// Wrap fields into label
						'labels' => [

							'label_8' => [

								'title'		=> 'Template cache',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_tcache',
										'value'		=> 'clean'

									],

								],

							],

							'label_9' => [

								'title'		=> 'Data Base cache',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_dbcache',
										'value'		=> 'clean'

									],

								],

							],

							'label_10' => [

								'title'		=> 'Interface cache',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_icache',
										'value'		=> 'clean'

									],

								],

							],

						],

					],

					// Fieldset caches parameters
					'fieldset_6' => [

						'title' => 'Data Base service futures',
						
						// Wrap fields into label
						'labels' => [

							'label_11' => [

								'title'		=> 'Refresh Data Base tables index',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_dbreindex',
										'value'		=> 'reindex'

									],

								],

							],

							'label_12' => [

								'title'		=> 'Optimize Data Base tables',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_dboptimize',
										'value'		=> 'optimize'

									],

								],

							],

							'label_13' => [

								'title'		=> 'Schema alter Data Base',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_settings_dbalter',
										'value'		=> 'alter'

									],

								],

							],

						],

					],

				],

			],

			'tab_5' => [

				// Tab title
				'title'  => 'Info',

				// Include fieldsets
				'fieldsets' => [

					// Fieldset caches parameters
					'fieldset_7' => [

						'title' => 'PHP Server info',
						
						// Wrap fields into label
						'labels' => [

							'label_8' => [

								'title'		=> 'PHP Server info',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'html:contents' => '<p>PHP Info served here: <a target="_blank" href="http://'. $_SERVER['HTTP_HOST'] .'/info/">PHP Info</a></p>'

									],

								],

							],

						],

					],

				],

			],

		]

	];


	// Avalilable languages
	$lng_data = $RKI->Language::getLanguageData('*');

	$lc = 5;

	// Interface  language switch
	foreach( TRANSLATIONS as $lng => $l ) {

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['fields'][0]['type'] = 'input:radio:'. ( $set['interface_language'] === $lng ? 'checked' : 'unchecked' );
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['fields'][0]['name'] = 'interface_language';
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['fields'][0]['value'] = $lng;

		foreach( $lng_data as $country => $c ) {

			if( $lng === $c['code_length_2'] || $c['code_length_2'] === 'US' ) {

				$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['access'] = 'preferences';
				$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['auth'] = 1;

				$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_2']['labels'][ 'label_lng_'. $lc ]['title:html'] = $RNV->lang['Language'] .' <span class="revolver__stats-system">[ '. $c['code_length_3'] .' :: '. $c['code_length_2'] .' :: '. $c['hreflang'] .' ]</span> <i class="state-attribution laguage-list-item revolver__sa-iso-'. strtolower( $c['code_length_2'] ) .'"></i>'. $RNV->lang['contents country'] .' <span class="revolver__stats-country">['. $c['name'] .']</span>';

			}

		}

		$lc++;

	}

	// Get list of available country codes for existed contents
	$country_codes = [];

	foreach( $all_nodes as $p ) {

		$l = $RKI->Language::getLanguageData( $p['country'] );

		$country_codes[ $l['name'] ] = [

			'name'     =>  $l['name'],
			'cipher'   =>  $l['cipher'],
			'code_2'   =>  $l['code_length_2'],
			'code_3'   =>  $l['code_length_3'],
			'hreflang' =>  $l['hreflang']

		];

	}

	// Build list of avalible laguages
	foreach( $country_codes as $c ) {

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['fields'][0]['type'] = 'input:radio:'. ( LANGUAGE === $c['cipher'] ? 'checked' : 'unchecked' );
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['fields'][0]['name'] = 'revolver_settings_country_code';
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['fields'][0]['value'] = $c['cipher'];

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['access'] = 'preferences';
		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['auth'] = 1;

		$form_parameters['tabs']['tab_2']['fieldsets']['fieldset_3']['labels'][ 'label_lng_c_'. $lc ]['title:html'] = $RNV->lang['Language'] .' <span class="revolver__stats-system">[ '. $c['code_3'] .' :: '. $c['code_2'] .' :: '. $c['hreflang'] .' ]</span> <i class="state-attribution laguage-list-item revolver__sa-iso-'. strtolower( $c['code_2'] ) .'"></i>'. $RNV->lang['contents country'] .' <span class="revolver__stats-country">['. $c['name'] .']</span>';

		$lc++;

	}

	// Scan for templates
	$toption = '';

	$template_path = './Templates/';

	if( is_readable('./Templates/') ) {

		$templates = scandir( $template_path, 1 );

		if( count( $templates ) > 0 ) {

			foreach( $templates as $template ) {

				if( !in_array( $template, [ '.DS_Store', '.', '..' ] ) ) {

					if(  $set['site_template'] === $template ) {

						$toption .= '<option value="'. $template  .'" selected="selected">'. $template .'</option>';

					}

					else {

						$toption .= '<option value="'. $template .'">'. $template .'</option>';

					}

				}

			}

		}

		$form_parameters['tabs']['tab_3']['fieldsets']['fieldset_4']['labels']['label_7']['fields'][0]['value:html'] = $toption;

	}

}

$contents .= $RKI->HTMLForm::build( $form_parameters, true );

$node_data[] = [

	'title'		=> $RNV->lang['Dashboard panel'] .' :: v.'. rr_version,
	'route'		=> '/dashboard/',
	'id'		=> 'dashboard',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'time'		=> null,
	'published' => 1

];

?>
