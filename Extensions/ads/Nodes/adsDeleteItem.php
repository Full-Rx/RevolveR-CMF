<?php

 /* 
  * 
  * RevolveR Create new node
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

if( $RNV->auth ) {

	if( in_array(ROLE, ['Admin', 'Writer'], true) ) {

		if( isset( SV['g']['id'] ) ) {

			$id = SV['g']['id']['value'];

			$item = iterator_to_array(

				$RKI->Model::get('ads_items', [

					'criterion' => 'id::'. $id,
					'course'	=> 'backward',
					'sort'		=> 'id'

				])

			)['model::ads_items'];

			if( $item ) {

				$hash = $item[ 0 ]['ads_hash'];


			}

		}

        $files = iterator_to_array(

            $RKI->Model::get('ads_files', [

              'criterion' => 'ads_hash::'. $hash,
              'course'  => 'forward',
              'sort'    => 'id'

            ])

          )['model::ads_files'];

        if( $files ) {

          foreach( $files as $f ) {   

            unlink( $_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/uploads/'. $f['file'] );

          }

          // Delete from database
          $RKI->Model::erase('ads_files', [

            'criterion' => 'ads_hash::'. $hash

          ]);

        }

		$RKI->Model::erase('ads_items', [

			'criterion' => 'id::'. $id

		]);

		header( 'Location: /ads/' );

		$node_data[] = [

			'title'		=> etranslations[ $ipl ]['Delete ads'],
			'id'		=> 'edit',
			'route'		=> '/ads/delete/',
			'contents'	=> $contents,
			'teaser'	=> null,
			'footer'	=> null,
			'published' => 1

		];

	}

}

?>
