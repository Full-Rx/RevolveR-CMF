<?php

if( ACCESS['role'] === 'Admin' ) {

	foreach( iterator_to_array(

		$RKI->Model::get( 'users', [

			'criterion' => 'id::*',
			'course'	=> 'forward',
			'sort' 		=> 'id'

		])

	)['model::users'] as $k => $v ) {

		if( PASS[ 2 ] == $v['id'] ) {

			$RKI->Template::$b[] = '<article class="revolver__article">';
			$RKI->Template::$b[] = '<header class="revolver__article-header">'; 
			$RKI->Template::$b[] = '<h1>'. $RKV->lang['Account manage'] .' :: '. $v['nickname'] .'</h1>';
			$RKI->Template::$b[] = '</header>';
			$RKI->Template::$b[] = '<div class="revolver__article-contents">';
			$RKI->Template::$b[] = '<figure class="revolver__user-profile-avatar">';

			if( $v['avatar'] === 'default') {

				$RKI->Template::$b[] = '<img src="/public/avatars/default.png" alt="'. $v['nickname'] .'" />';

			}
			else {

				$RKI->Template::$b[] = '<img src="'. $v['avatar'] .'" alt="'. $v['nickname'] .'" />';

			}

			$RKI->Template::$b[] = '<figcaption>';

			$RKI->Template::$b[] = '<p>'. $RKV->lang['User Name'] .': <i>'. $v['nickname'] .'</i></p>';
			$RKI->Template::$b[] = '<p>'. $RKV->lang['User Email'] .': <i>'. $v['email']  .'</i></p>';
			$RKI->Template::$b[] = '<p>'. $RKV->lang['Telephone'] .': <i>'. $v['telephone']  .'</i></p>';
			$RKI->Template::$b[] = '<p>'. $RKV->lang['Permissions'] .': <i>'. $v['permissions'] .'</i></p>';

			$RKI->Template::$b[] = '</figcaption>';
			$RKI->Template::$b[] = '</figure>';

			$roles_allowed = iterator_to_array(

				$RKI->Model::get( 'roles', [

					'criterion' => 'id::*',
					'course'	=> 'forward',
					'sort' 		=> 'id'

				])

			)['model::roles'];

			$roles = ['User', 'Admin', 'Writer', 'Banned'];

			if( $v['permissions'] === 'Admin' ) {

				$roles = ['User', 'Admin', 'Writer'];

			}

			$render_node_options = '';

			foreach( $roles as $r ) {

				$render_node_options .= ( $v['permissions'] === $r ) ? '<option value="'. $r .'" selected="selected">'. $r .'</option>' : '<option value="'. $r .'">'. $r .'</option>';

			}

			$form_parameters = [

				// Main parameters
				'id' 	  => 'profile-edit-form',
				'class'	  => 'revolver__profile-edit-form revolver__new-fetch',
				'action'  => '/user-d/',
				'method'  => 'post',
				'encrypt' => true,
				'captcha' => true,
				'submit'  => 'Set',

				// Include fieldsets
				'fieldsets' => [

					// Fieldset contents parameters
					'fieldset_1' => [

						'title' => 'Edit permissions',

						// Wrap fields into label
						'labels' => [

							'label_1' => [

								'title'  => 'Role',
								'access' => 'profile',
								'auth'	 => 1,

								'fields' => [

									0 => [

										'type' 			=> 'select',
										'name' 			=> 'revolver_user_edit_role',
										'required'		=> true,
										'value:html' 	=> $render_node_options

									],

									1 => [

										'type' 			=> 'input:hidden',
										'name' 			=> 'revolver_user_edit_id',
										'required'		=> true,
										'value' 		=> $v['id']

									],

								],

							],

						],

					],

				]

			];

			$RKI->Template::$b[] = $RKI->HTMLForm::build( $form_parameters );

			$RKI->Template::$b[] = '</div>';
			$RKI->Template::$b[] = '</article>';

		}

	}

}

?>
