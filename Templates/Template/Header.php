
<!-- RevolveR :: header -->
<header itemscope itemtype="http://schema.org/Organization" class="revolver__header <?php print $auth_class; ?>">

	<h1 class="revolver__logo" rel="banner">

		<a itemprop="url" title="<?php print $RKV->descr; ?>" href="<?php print $RKV->host ?>">

			<span itemprop="name"><?php print $RKV->brand; ?></span>

		</a>

		<link itemprop="logo" href="/Interface/RCMF.svg" />

	</h1>

	<div class="revolver__exchange-rates">

		<?php 

			if( $RKV->installed ) {

				require_once('./Templates/'. TEMPLATE .'/widgets/ex-rates.inc'); 

			}

		?>

	</div>

	<div class="revolver__search-box">

		<form action="/search/" method="GET">

			<div class="basket_handler">
				<div class="basket_icon" title="<?php print $RKV->lang['Basket'] ?>"><i></i></div>
				<div class="basket_indicator" title="<?php print $RKV->lang['Basket'] ?>"><i></i></div>
			</div>

			<input type="search" name="query" placeholder="<?php print $RKV->lang['Type keywords here'] ?>" required <?php if( !$RKV->installed ): ?>disabled="disabled"<?php endif; ?> />
			<input type="submit" name="revolver-search-submit" value="<?php print $RKV->lang['Search'] ?>" />

		</form>

	</div>

	<!-- RevolveR :: site description -->
	<h2 itemprop="description" class="revolver__site-description"><?php print $RKV->descr; ?></h2>

</header>

<div class="nav_container">
	<div class="revolver__nav-bar">

		<?php require('Menu.php'); ?>

	</div>
</div>

<?php if( $RKV->request !== '/' ): ?>

<div class="revolver__breadcrumb">

	<?php require('Breadcrumb.php') ?>

</div>

<?php endif;?>
