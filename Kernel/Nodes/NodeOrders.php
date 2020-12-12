<?php

 /* 
  * 
  * RevolveR Node Orders
  *
  * v.2.0.0.0
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

$contents  = '';

if( !empty(SV['p']) ) {

	if( isset(SV['p']['revolver_captcha']) ) {

		if( (bool)SV['p']['revolver_captcha']['valid'] ) {

			if( $RKI->Captcha::verify(SV['p']['revolver_captcha']['value']) ) {

				define('form_pass', 'pass');

			}

		}

	}

	$feedback_process = [];

	for( $i = 0; $i < 100; $i++ ) {

		if( !isset(SV['p'][ 'revolver_process_feedback_'. $i ]) ) {

			$feedback_process[ $i .':process' ][0] = $i .'::0';

		}
		else {

			if( (bool)SV['p'][ 'revolver_process_feedback_'. $i ]['valid'] ) {

				$feedback_process[ $i .':process' ][0] = $i .'::1';

			}
			else {

				$feedback_process[ $i .':process' ][0] = $i .'::0';

			}

		}

		if( !isset(SV['p'][ 'revolver_paid_feedback_'. $i ]) ) {

			$feedback_process[ $i .':paid' ][ 0 ] = $i .'::0';

		}
		else {

			if( (bool)SV['p'][ 'revolver_paid_feedback_'. $i ]['valid'] ) {

				$feedback_process[ $i .':paid' ][ 0 ] = $i .'::1';

			}
			else {

				$feedback_process[ $i .':paid' ][ 0 ] = $i .'::0';

			}

		}

	}

}

$feedbacks = iterator_to_array(

	$RKI->Model::get('store_orders', [

		'criterion' => 'id::*'

	])

)['model::store_orders'];

foreach( $feedback_process as $n => $m ) {

	if( isset($m[0]) ) {

		if( $feedbacks ) {

			foreach( $feedbacks as $k => $f ) {

				$cmd = explode('::', $m[0]);

				if( (int)$f['id'] === (int)$cmd[0] ) {

					if( (int)$f['processed'] !== (int)$cmd[1] ) {

						if( defined('form_pass') ) {

							if( form_pass === 'pass' ) {

								$RKI->Model::set('store_orders', [

									'id'				=> (int)$f['id'],
									'processed' 		=> (int)$cmd[1],
									'criterion' 		=> 'id'

								]);

							}

						}

					}

					if( $f['paid'] !== $cmd[1] ) {

						if( defined('form_pass') ) {

							if( form_pass === 'pass' ) {

								$RKI->Model::set('store_orders', [

									'id'				=> (int)$f['id'],
									'paid' 				=> (int)$cmd[1],
									'criterion' 		=> 'id'

								]);

							}

						}

					}

				}

			}

		}

	}

}

if( in_array(ROLE, ['Admin', 'Writer']) ) {

	$feedback = iterator_to_array(

		$RKI->Model::get('store_orders', [

			'criterion' => 'id::*',
			'course'    => 'backward',
			'sort'      => 'id'

		])

	)['model::store_orders'];

	if( $feedback ) {

		$processing_feedback_form = [

			'id'		=> 'feedback-processing',
			'class'		=> 'revolver__feedback-feedback-processing revolver__new-fetch',
			'action'	=> $RNV->request,
			'method'	=> 'POST',
			'captcha'	=> true,
			'encrypt'	=> true,
			'submit'	=> 'Manage'

		];

		$processing_feedback_form['fieldsets']['fieldset_0']['title'] = 'Manage orders';

		$n = 0;

		
		foreach( $feedback as $f ) {

			$processed = (bool)$f['processed'] ? 'proccessed' : 'unprocessed';

			$contents_feedback  = '<article id="feedback-'. $f['id'] .'" class="revolver__feedback feedback-'. $f['id'] .' '. $processed .'">';
			$contents_feedback .= '<header class="revolver__feedback-header">';

			$contents_feedback .= '<h2>• '. $RNV->lang['Order request'] .' '. $f['id'] .' '. $RNV->lang['by'] .' <span>'. $f['customer_name'] .' '. $f['customer_last_name'] .'</span></h2>';

			$contents_feedback .= '</header>';

			$contents_feedback .= '<div class="revolver__article-contents">';

			$contents_feedback .= '<ul>';
			$contents_feedback .= '<li>'. $f['customer_name'] .' '. $f['customer_last_name'] .';</li>';
			$contents_feedback .= '<li>'. $f['customer_email'] .';</li>';
			$contents_feedback .= '<li>'. $f['customer_telephone'] .';</li>';
			$contents_feedback .= '<li>'. $f['customer_address'] .'.</li>';
			$contents_feedback .= '</ul>';

			$contents_feedback .= $RKI->HTML::Markup( 

					htmlspecialchars_decode( 

						html_entity_decode( 

							$f['customer_comment']

						)

					), ['lazy' => 1] );

			$contents_feedback .= '</div>';


			$contents_feedback .= '<div class="revolver__moderation-flags">';
			$contents_feedback .= '<label id="label_processed_'. $n .'">'. $RNV->lang['Processed'] .':';
			$contents_feedback .= '<input type="checkbox" name="revolver_process_feedback_'. $f['id'] .'" value="'. $f['id'] .'::'. ( (bool)$f['processed'] ? 1 : ((bool)$f['paid'] ? 1 : 0) ) .'" '. ( (bool)$f['processed'] ? 'checked="checked"' : '' ) .' '. ((bool)$f['paid'] ? ' disabled="disabled"' : '')  .' />';
			$contents_feedback .= '</label>';

			$contents_feedback .= '<label id="label_delete_'. $n .'">'. $RNV->lang['Paid'] .':';
			$contents_feedback .= '<input type="checkbox" name="revolver_paid_feedback_'. $f['id'] .'" value="'. $f['id'] .'::'. ( (bool)$f['paid'] ? 1 : 0 ) .'" '. ( (bool)$f['paid'] ? 'checked="checked"' : '' ) . ((bool)$f['paid'] ? ' disabled="disabled"' : '') .'  />';
			$contents_feedback .= '</label>';
			$contents_feedback .= '</div>';

			$contents_feedback .= '</article>';

			$processing_feedback_form['fieldsets']['fieldset_0']['title'] = 'Orders requests';
			$processing_feedback_form['fieldsets']['fieldset_0']['labels']['label_0']['title'] = 'Manage';
			$processing_feedback_form['fieldsets']['fieldset_0']['labels']['label_0']['access'] = 'preferences';
			$processing_feedback_form['fieldsets']['fieldset_0']['labels']['label_0']['auth'] = 1;

			$processing_feedback_form['fieldsets']['fieldset_0']['labels']['label_0']['fields'][0]['html:contents'] .= $contents_feedback;

			$n++;

		}

		$contents .= $RKI->HTMLForm::build( $processing_feedback_form );

	} 
	else {

		$contents .= '<p>'. $RNV->lang['No orders to manage found'] .'.</p>';

	}

}


$node_data[] = [

	'title'		=> $RNV->lang['Orders'],
	'id'		=> 'orders',
	'route'		=> '/orders/',
	'contents'	=> $contents,
	'teaser'	=> null,
	'footer'	=> null,
	'time'		=> null,
	'published'	=> 1

];


?>
