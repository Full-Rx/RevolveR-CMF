
<!-- RevolveR :: breadcrumb -->
<nav class="revolver__main-breadcrumb">

	<ul itemscope itemtype="https://schema.org/BreadcrumbList">

		<?php if( !defined('ROUTE') && $RKV->installed  ): ?>

			<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

				<a itemprop="item" href="/" title="<?php print $RKV->lang['Home']; ?>">
					<b itemprop="name"><?php print $RKV->lang['Home']; ?></b>
				</a>

				<meta itemprop="position" content="1" />

			</li>

			<li>
				<span>›</span>
			</li>

			<li>
				<em><?php print $RKV->title; ?></em>	
			</li>

		<?php endif;?>

		<?php if( defined('ROUTE') && isset(PASS[ 2 ]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemprop="item" href="/" title="<?php print $RKV->lang['Home']; ?>">
				<b itemprop="name"><?php print $RKV->lang['Home']; ?></b>
			</a>

			<meta itemprop="position" content="1" />

		</li>

		<li>
			<span>›</span>
		</li>

		<?php if( PASS[ 1 ] === 'store' && isset(PASS[ 3 ]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print $RKV->host .'/store/'; ?>" href="/store/" title="<?php print $RKV->lang['Store']; ?>" itemscope>
				<b itemprop="name"><?php print $RKV->lang['Store']; ?></b>
			</a>

			<meta itemprop="position" content="2" />

		</li>

		<li>
			<span>›</span>
		</li>

		<?php endif;?>

		<?php if( PASS[ 1 ] === 'wiki' && isset(PASS[ 4 ]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print $RKV->host .'/wiki/'; ?>" href="/wiki/" title="<?php print $RKV->lang['Wiki']; ?>" itemscope>
				<b itemprop="name"><?php print $RKV->lang['Wiki']; ?></b>
			</a>

			<meta itemprop="position" content="2" />

		</li>

		<li>
			<span>›</span>
		</li>

		<?php endif;?>

		<?php if( PASS[ 1 ] === 'blog' && isset(PASS[ 3 ]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print $RKV->host .'/blog/'; ?>" href="/blog/" title="<?php print $RKV->lang['Blog']; ?>" itemscope>
				<b itemprop="name"><?php print $RKV->lang['Blog']; ?></b>
			</a>

			<meta itemprop="position" content="2" />

		</li>

		<li>
			<span>›</span>
		</li>

		<?php endif;?>

		<?php if( PASS[ 1 ] === 'forum' && isset(PASS[ 3 ]) ): ?>

		<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

			<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print $RKV->host .'/forum/'; ?>" href="/forum/" title="<?php print $RKV->lang['Forum']; ?>" itemscope>
				<b itemprop="name"><?php print $RKV->lang['Forum']; ?></b>
			</a>

			<meta itemprop="position" content="2" />

		</li>

		<li>
			<span>›</span>
		</li>

			<?php if( isset(PASS[ 4 ]) ): ?>

			<?php

				$forum_breadcrumb = iterator_to_array(

					$RKI->Model::get('forums', [

						'criterion' => 'id::'. (int)PASS[ 2 ],
						'course'	=> 'forward',
						'sort'		=> 'id'

					])

				)['model::forums'];

				$b_title = 'undefined';

				if( $forum_breadcrumb ) {

					$b_title = $forum_breadcrumb[0]['title'];

				}

			?>

			<li itemprop="itemListElement" itemtype="https://schema.org/ListItem" itemscope>

				<a itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php print $RKV->host .'/forum/' . PASS[ 2 ] .'/'; ?>" href="/forum/<?php print PASS[ 2 ] .'/'; ?>" title="<?php print $b_title; ?>" itemscope>
					<b itemprop="name"><?php print $b_title; ?></b>
				</a>

				<meta itemprop="position" content="3" />

			</li>

			<li>
				<span>›</span>
			</li>

			<?php endif;?>

		<?php endif;?>

		<?php

			// Extend breadcrumb with an extension
			if( defined('ROUTE') && $RKV->installed ) {

				if( isset(ROUTE['ext']) ) {

					if( (bool)ROUTE['ext'] ) {

						$tpl = $_SERVER['DOCUMENT_ROOT'] .'/Extensions/'. PASS[ 1 ] .'/Template/Breadcrumb.php';

						if( file_exists( $tpl ) ) {

							require_once( $tpl );

						} 

					}

				}

			}  

		?>
		
		<li>
			<em><?php print $RKV->title; ?></em>	
		</li>

		<?php endif;?>

	</ul>
		
</nav>
