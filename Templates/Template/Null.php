
<?php 

    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');

    header('Status: 404 Not Found');

    if( N ) {

        // Title for 404
        $title = 'This URL not found on this host :: 404';

        // Brand for 404
        $brand = '!_found ';

        // Website description
        $descr = 'Requested URL is not accesible on this host for now.';    

    }


?>

<!-- RevolveR :: head -->
<?php include('Head.php'); ?>

<?php // Scalable design class

    $main_class = 'revolver__scalable-main';

    $auth_class = $authFlag ? 'revolver__authorized' : 'revolver__not-authorized';

?>

    <body>

        <!-- RevolveR :: root -->
        <main id="RevolverRoot" class="<?php print $main_class; ?>">

            <?php include('Header.php'); ?>

            <!-- RevolveR :: main -->
            <section class="revolver__main-contents <?php print $main_class; ?> <?php print $auth_class; ?>">

            <?php 

                if( CONTENTS_FLAG && $RKV->request !== '/' ) {

                    $RKI->Template::$b[] = '<article class="revolver__article article-id-404">';
                    $RKI->Template::$b[] = '<header class="revolver__article-header">';
                    $RKI->Template::$b[] = '<h1>'. $RKV->lang['Route not found'] .'</h1>';
                    $RKI->Template::$b[] = '</header>';

                    $RKI->Template::$b[] = '<div class="revolver__article-contents">';
                    $RKI->Template::$b[] = '<p>'. $RKV->lang['Route'];
                    $RKI->Template::$b[] = ' <b>'. $RKV->request .'</b> '. $RKV->lang['was not found on this host'] .'!</p>';
                    $RKI->Template::$b[] = '<p><a href="/">'. $RKV->lang['Begin at homepage'] .'!</a>';
                    $RKI->Template::$b[] = '</p></div>';

                    $RKI->Template::$b[] = '<footer class="revolver__article-footer">';
                    $RKI->Template::$b[] = '<nav><ul><li><a title="'. $RKV->lang['Homepage'] .'" href="/">'. $RKV->lang['Homepage'] .'</a></li></ul></nav>';
                    $RKI->Template::$b[] = '</footer></article>';

                }

                print implode("\n", $RKI->Template::$b);

                $RKI->Template::$b = [];

            ?>

            </section>

            <?php include('Footer.php'); ?>

        </main>

<?="\n\n";

foreach( $scripts as $s ) {

    print $s ."\n"; 

}

?>
