<?php

/** 
  * 
  * RevolveR Ads Extension ads
  *
  * v.2.0.1.3
  *
  */

$contents  = '';

if( INSTALLED ) {

	$extension_id = $extension_enabled_set = $extension_cache_enabled_set = $extension_install_set = $extension_uninstall_set = 0;

	$enabled_lock = $cache_lock = $installed = $enabled = null;

	if( isset(SV['p']) ) {

		if( isset( ACCESS['role'] ) ) {

			if( ACCESS['role'] === 'Admin' ) {

				if( isset(SV['p']['revolver_ads_enabled']) ) {

					if( (bool)SV['p']['revolver_ads_enabled']['valid'] ) {

						if( SV['p']['revolver_ads_enabled']['value'] === 'on' ) {

							$extension_enabled_set = 1;

						}

					}

				}

				if( isset(SV['p']['revolver_ads_cache_enabled']) ) {

					if( (bool)SV['p']['revolver_ads_cache_enabled']['valid'] ) {

						if( SV['p']['revolver_ads_cache_enabled']['value'] === 'on' ) {

							$extension_cache_enabled_set = 1;

						}

					}

				}

				if( isset(SV['p']['revolver_ads_install']) ) {

					if( (bool)SV['p']['revolver_ads_install']['valid'] ) {

						if( SV['p']['revolver_ads_install']['value'] === 'on' ) {

							$extension_install_set = 1;

							$installed = true;

						}

					}

				}

				if( isset(SV['p']['revolver_ads_uninstall']) ) {

					if( (bool)SV['p']['revolver_ads_uninstall']['valid'] ) {

						if( SV['p']['revolver_ads_uninstall']['value'] === 'on' ) {

							$extension_uninstall_set = 1;

						}

					}

				}

				if( isset(SV['p']['revolver_captcha']) ) {

					if( (bool)SV['p']['revolver_captcha']['valid'] ) {

						if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

							define('form_pass', 'pass');

						}

					}

				}

			}

		}

	}

	foreach( EXTENSIONS_SETTINGS as $e ) {

		if( $e['name'] === ltrim(ROUTE['node'], '#') ) {

			$extension_id = $e['id'];

			$extension_enabled = $e['enabled'];

			$extension_cache_enabled = $e['cache'];

			// Set lock 
			$enabled_lock = (bool)$e['enabled'] ? true : null;

			// Enabled 
			$enabled = (bool)$e['enabled'];

			// Installed
			$installed = true;

			break;

		}

	}

}

if( isset(ACCESS['role']) ) {

	if( ACCESS['role'] === 'Admin' )  {

		// Settings manage
		if( defined('form_pass') ) {

			if( form_pass === 'pass' ) {

				if( (bool)$extension_uninstall_set ) {

					if( ROLE === 'Admin' ) {

						$RKI->Model::erase('extensions', [

							'criterion' => 'id::'. $extension_id

						]);

						$dbx::query('d', 'revolver__ads_categories', $DBX_KERNEL_SCHEMA['ads_categories']);

						$dbx::query('d', 'revolver__ads_items', $DBX_KERNEL_SCHEMA['ads_items']);							

						$dbx::query('d', 'revolver__ads_files', $DBX_KERNEL_SCHEMA['ads_files']);

						$RKI->Notify::set('notice', 'Extension ads now uninstalled');

						$installed = null;

					}

				}
				else {

					if( ROLE === 'Admin' ) {

						$RKI->Model::set('extensions', [

							'id'		=> $extension_id,
							'name'		=> 'ads',
							'enabled'	=> $extension_enabled_set,
							'cache'		=> $extension_cache_enabled_set,
							'criterion' => 'name'

						]);

						// Installed status
						if( (bool)$extension_install_set ) {

							$RKI->Notify::set('status', 'Extension ads now installed');

							$dbx::query('c', 'revolver__ads_categories', $DBX_KERNEL_SCHEMA['ads_categories']);

							$dbx::query('c', 'revolver__ads_items', $DBX_KERNEL_SCHEMA['ads_items']);							

							$dbx::query('c', 'revolver__ads_files', $DBX_KERNEL_SCHEMA['ads_files']);

						}

						// Enabled status
						if( (bool)$extension_enabled_set && !(bool)$extension_enabled ) {


							$RKI->Notify::set('status', 'Extension ads now enabled');

							// Enabled cache status
							if( ROLE === 'Admin' ) {

								if( (bool)$extension_cache_enabled ) {

									if( (bool)$extension_cache_enabled_set ) {

										$RKI->Notify::set('status', 'Extension ads cache enabled');

										$RKI->Notify::set('active', 'Extension ads cache now active');

									}

								}

								// Disabled cache status
								if( !(bool)$extension_cache_enabled && !(bool)$extension_cache_enabled_set ) {

									$RKI->Notify::set('status', 'Extension ads now enabled');

									$RKI->Notify::set('notice', 'Extension ads cache not enabled');

									$RKI->Notify::set('inactive', 'Extension ads cache now inactive');

								}

							}

							$enabled = true;

						}

						// Disabled status
						if( !(bool)$extension_enabled_set && (bool)$extension_enabled ) {

							$RKI->Notify::set('notice', 'Extension ads now disabled');

							$RKI->Notify::set('notice', 'Extension ads cache not enabled');

							$RKI->Notify::set('inactive', 'Extension ads cache now inactive');

						}

						// Enabled cache status
						if( (bool)$extension_cache_enabled_set && !(bool)$extension_cache_enabled ) {

							if( $enabled  ) {	

								$RKI->Notify::set('status', 'Extension ads now enabled');

								$RKI->Notify::set('status', 'Extension ads cache enabled');

								$RKI->Notify::set('active', 'Extension ads cache now active');

							}

						}

						// Disabled cache status
						if( !(bool)$extension_cache_enabled_set && (bool)$extension_cache_enabled ) {

							$RKI->Notify::set('status', 'Extension ads now enabled');

							$RKI->Notify::set('notice', 'Extension ads cache not enabled');

							$RKI->Notify::set('inactive', 'Extension ads cache now inactive');

						}

					}

					$extension_enabled = $extension_enabled_set;

					$extension_cache_enabled = $extension_cache_enabled_set;

					$enabled_lock = (bool)$extension_enabled ? true : null;

				}

			}
			else {

				$RKI->Notify::set('notice', 'Security check not pass');

			}

		}

		if( !$installed ) {

			$form_parameters = [

				'id'		=> 'ads-settings',
				'class'		=> 'revolver__ads-settings-form revolver__new-fetch',
				'action'	=> '/ads/',
				'method'	=> 'POST',
				'captcha'	=> true,
				'encrypt'	=> true,
				'submit'	=> 'Install',

				'fieldsets' => [

					'fieldset_1' => [

						'title' => 'ads extension install',

						'labels' => [

							'label_1' => [

								'title'		=> 'Install',
								'access'	=> 'preferences',
								'auth'		=> 1,

								'fields' => [

									0 => [

										'type'		=> 'input:hidden',
										'name'		=> 'revolver_ads_enabled',
										'value'		=> 1

									],

									1 => [

										'type'		=> 'input:hidden',
										'name'		=> 'revolver_ads_cache_enabled',
										'value'		=> 1

									],

									2 => [

										'type'		=> 'input:checkbox:unchecked',
										'name'		=> 'revolver_ads_install'

									]

								],

							],

						],

					],

				]

			];

		} 
		else {

			$form_parameters = [

				'id'		=> 'ads-settings',
				'class'		=> 'revolver__ads-settings-form revolver__new-fetch',
				'action'	=> '/ads/',
				'method'	=> 'POST',
				'captcha'	=> true,
				'encrypt'	=> true,
				'submit'	=> 'Set',

				'fieldsets' => [

					'fieldset_1' => [

						'title' => 'ads settings',

						'labels' => [

							'label_1' => [

								'title'  => (bool)$extension_enabled ? 'Enabled' : 'Disabled',
								'access' => 'preferences',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type'	=> 'input:checkbox:'. ( (bool)$extension_enabled ? 'checked' : 'unchecked' ),
										'name'	=> 'revolver_ads_enabled'

									]

								],

							],

							'label_2' => [

								'title'  => (bool)$extension_cache_enabled ? 'Cache enabled' : 'Cache disabled',
								'access' => 'preferences',
								'auth'   => 1,

								'fields' => [

									0 => [

										'type'		=> 'input:checkbox:'. ( (bool)$extension_cache_enabled ? 'checked' : 'unchecked' ),
										'name'		=> 'revolver_ads_cache_enabled',
										'disabled'	=> true
									]

								],

							],

						],

					],

				]

			];

		}

		if( !$enabled_lock && $installed ) {

			$form_parameters['fieldsets']['fieldset_1']['labels']['label_3'] = [

				'title'		=> 'Uninstall',
				'access'	=> 'preferences',
				'auth'		=> 1,

				'fields' => [

					0 => [

						'type'	=> 'input:checkbox:unchecked',
						'name'	=> 'revolver_ads_uninstall'

					]

				]

			];

		}

		$contents .= '<h2 class="revolver__collapse-form-legend revolver__collapse-form-legend-form-free">'. etranslations[ $ipl ]['ads settings'] .'</h2>';

		$contents .= '<output class="revolver__collapse-form-contents" style="overflow: hidden; width: 0px; height: 0px; line-height: 0px; display: inline-block; min-height: 0px; opacity: 0; transform: scaleX(1) scaleY(1) scaleZ(1);">';
		$contents .= $RKI->HTMLForm::build( $form_parameters, null, etranslations );
		$contents .= '</output>';

	}

}

if( $installed && !$enabled && !Auth ) { 

	$contents .= '<p>'. etranslations[ $ipl ]['ads not enabled for now'] .'.</p>';

}

if( INSTALLED ) {

	if( $installed && $enabled ) {

		if( ROLE !== 'none' ) {

			if( ROLE === 'Admin' || ROLE === 'Writer' ) {

				$contents .= '<p style="text-align:center;">... [ <a href="/ads/addcat/">'. etranslations[ $ipl ]['Add ads category'] .'</a> ] ...</p>';

			}

		}

		$categories = iterator_to_array(

				$RKI->Model::get('ads_categories', [

					'criterion' => 'id::*',
					'course'	=> 'forward',
					'sort'		=> 'id'

				])

			)['model::ads_categories'];

		if( $categories ) {

			$contents .= '<dl class="revolver__categories">';

			foreach( $categories as $cat ) {

				if( ROLE !== 'none' ) {

					if( ROLE === 'Admin' || ROLE === 'Writer' ) {

						$contents .= '<dt>&#8226; <a href="/ads/view/?id='. $cat['id'] .'">'. $cat['title'] .'</a> &#8226; <span style="float:right">[ <a href="/ads/editcat/?id='. $cat['id'] .'">'. etranslations[ $ipl ]['Edit'] .'</a> ]</span></dt>';

					}

				}
				else {

					$contents .= '<dt>&#8226; <a href="/ads/view/?id='. $cat['id'] .'">'. $cat['title'] .'</a></dt>';

				}

				$contents .= '<dd><p>'. $cat['description'] .'</p>';

				$contents .= '<div style="text-align:center">...[ <a href="/ads/additem/">'. etranslations[ $ipl ]['Add ads'] .'</a> ]...</div>';

				$items = iterator_to_array(

						$RKI->Model::get('ads_items', [

							'criterion' => 'ads_category::'. $cat['id'],

							'bound'		=> [

								3

							],

							'course'	=> 'backward',
							'sort'		=> 'id'

						])

					)['model::ads_items'];

				if( $items ) {

					$contents .= '<ul>';

					foreach( $items as $i ) {

						$language = $RKI->Language::getLanguageData( $i['ads_country'] );

						$files = iterator_to_array(

								$RKI->Model::get('ads_files', [

									'criterion' => 'ads_hash::'. $i['ads_hash'],
									'course'	=> 'forward',
									'sort'		=> 'id'

								])

							)['model::ads_files'];

						$contents .= '<li>';
						$contents .= '<h2>'. $i['ads_title'] .' <span style="float:right">'. $i['ads_time'] .'</span></h2>';

						$contents .= '<div style="display:table">';
						$contents .= '<figure style="display:table-cell; vertical-align: middle; width: 10%">';

						if( $files ) {

							foreach( $files as $f ) {   

								$contents .= '<img itemprop="image" src="/Extensions/ads/uploads/'. $f['file'] .'" />';

							}

						}
						else {


							$contents .= '<img src="/Extensions/ads/uploads/ads.png" alt="Ads have no cover" />';

						}

						$contents .= '</figure>';

						$contents .= '<div style="display:table-cell; width: 90%">';
						$contents .= '<p style="text-shadow: 0 0 .1vw var(--article-header-text-color);">'. $i['ads_description'] .'</p>';

						$contents .= $RKI->HTML::Markup(

							htmlspecialchars_decode(

								html_entity_decode(

									$RKI->HTML::metaHash( $i['ads_content'] )

								)

							), ['lazy' => 1]);

						$contents .= '<p style="text-align:center; text-shadow: 0 0 .1vw var(--article-header-text-color);">';
						$contents .= '<span style="color: #b00000;">'. $i['sender_email'] .'</span>; ';
						$contents .= '<span style="color: #30349e;">'. $i['sender_phone'] .'</span>; ';
						$contents .= '<span style="color: #675716;">'. $i['sender_name'] .'</span> ';
						$contents .= '<span style="float:right; color: #efefef">'. $i['ads_price'] .'<em>'. $language['currency_symb'] .'</em></span>';
						$contents .= '</p>';
						$contents .= '</div>';
						$contents .= '</div>';
						$contents .= '</li>';

					}

					$contents .= '</ul>';

				}

				$contents .= '</dd>';

			}

			$contents .= '</dl>';

		}

	}

}

$node_data[] = [

	'title'		=> etranslations[ $ipl ]['Ads'],
	'id'		=> 'ads',
	'route'		=> '/ads/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'time'		=> null,
	'published'	=> 1

];

?>
