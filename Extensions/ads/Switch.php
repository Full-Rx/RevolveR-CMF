<?php

/** 
  * 
  * RevolveR ads Extension
  *
  * v.2.0.1.3
  *
  */

if( defined('ROUTE') ) {

	switch( ROUTE['node'] ) {

		case '#ads':

			ob_start();

			require_once( $_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/Nodes/adsMain.php' );

			break;

		case '#addcat':

			ob_start();

			require_once( $_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/Nodes/adsAddCat.php' );

			break;

		case '#editcat':

			ob_start();

			require_once( $_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/Nodes/adsEditCat.php' );

			break;

		case '#additem':

			ob_start();

			require_once( $_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/Nodes/adsAddItem.php' );

			break;

		case '#adsview':

			ob_start();

			require_once( $_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/Nodes/adsView.php' );

			break;

		case '#adsdelete':

			ob_start();

			require_once( $_SERVER['DOCUMENT_ROOT'] .'/Extensions/ads/Nodes/adsDeleteItem.php' );

			break;

		break;

	}

}

?>
