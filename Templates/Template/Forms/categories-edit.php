<?php

if( in_array(ACCESS['role'], ['Admin', 'Writer'], true) ) { 

	$category = iterator_to_array(

		$RKI->Model::get('categories', [

			'criterion' => 'id::'. PASS[ 2 ]

		])

	)['model::categories'][0];

	$form_parameters = [

		// main parameters
		'id' 	  => 'categories-edit-form',
		'class'	  => 'revolver__categories-edit-form revolver__new-fetch',
		'action'  => '/category-d/',
		'method'  => 'post',
		'encrypt' => true,
		'captcha' => true,
		'submit'  => 'Submit',

		// included fieldsets
		'fieldsets' => [

			// fieldset contents parameters
			'fieldset_1' => [

				'title' => 'Edit category',

				// wrap fields into label
				'labels' => [

					'label_1' => [

						'title'  => 'Category title',
						'access' => 'categories',
						'auth'	 => 1,

						'fields' => [

							0 => [

								'type' 			=> 'input:text',
								'name'			=> 'revolver_category_title',
								'placeholder' 	=> 'Type category name',
								'required'		=> true,
								'value'			=> $category['title']

							],

						],

					],

					'label_2' => [

						'title'  => 'Category description',
						'access' => 'categories',
						'auth'	 => 1,

						'fields' => [

							0 => [

								'type' 			=> 'input:text',
								'name'			=> 'revolver_category_description',
								'placeholder' 	=> 'Type category description',
								'required'		=> true,
								'value'			=> $category['description']

							],

							1 => [

								'type' 			=> 'input:hidden',
								'name'			=> 'revolver_category_edit',
								'required'		=> true,
								'value'			=> $category['id']

							],

						],

					],

					'label_3' => [

						'title'  => 'Delete category',
						'access' => 'categories',
						'auth'	 => 1,

						'fields' => [

							0 => [

								'type' 			=> 'input:checkbox:unchecked',
								'name'			=> 'revolver_category_action_delete',
								'placeholder' 	=> 'Type category description',
								'value'			=> 1

							],

						],

					],

				],

			],

		],

	];

	$RKI->Template::$b[] = '<article class="revolver__article article-categories-edit">';
	$RKI->Template::$b[] = '<header class="revolver__article-header">'; 
	$RKI->Template::$b[] = '<h1>'. $RKV->lang['Categories'] .'</h1>';
	$RKI->Template::$b[] = '</header>';

	$RKI->Template::$b[] = $RKI->HTMLForm::build( $form_parameters );

	$RKI->Template::$b[] = '</article>';

}

?>
