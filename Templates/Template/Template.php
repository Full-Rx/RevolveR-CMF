<?php include('Head.php'); ?>

<?php // Scalable design class

    $main_class = 'revolver__scalable-main';
    $auth_class = $authFlag ? 'revolver__authorized' : 'revolver__not-authorized';

	if( (PASS[ 1 ] === 'blog' && !empty( PASS[ 2 ] )) || (PASS[ 1 ] === 'wiki' && !empty( PASS[ 3 ] )) ) {

		$descr = $node_data[0]['description'];

	}

?>

<body>

<main id="RevolverRoot" class="<?php print $main_class; ?>">

<?php

    include('Header.php');

    include('Main.php');

    include('Footer.php');

?>

</main>

<?php 

$RKI->Template::$b[] = "\n\n";

foreach( $scripts as $s ) {

    $RKI->Template::$b[] = $s ."\n"; 

}

$RKI->Template::print();

?>
