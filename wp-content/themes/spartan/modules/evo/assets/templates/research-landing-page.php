<?php
	$categories = App::module('ResourceItem')->getCategoryStack( Input::param('taxonomyName') );
	$mainCategory = '';
?>

<?php get_header(); ?>
	<div id="content">

		<div id="inner-content" class="wrap cf">

				<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

						<header class="article-header">

							<h1><?php echo ucfirst(Input::param('taxonomyName')); ?></h1>


						</header> <?php // end article header ?>

						<section class="entry-content cf" itemprop="articleBody">
							<?php foreach ( $categories as $index => $category ) : ?>
								<div class="resource-bucket">
									<h3><?php echo $category["name"] ?></h3>
									<ul>
										<?php foreach ($category["posts"] as $index => $post): ?>
											<li>
												<?php
													echo "<a class='resource-link' href='{$post->resourceLink}'>{$post->post_title}</a>";
													if( $post->extraText ) echo "<span class='resource-link-extra-text'> ({$post->extraText})</span>";
													if( $post->post_content ) echo "<p>{$post->post_content}</p>";
												 ?>
											</li>
										<?php endforeach ?>
									</ul>
								</div>
							<?php endforeach; ?>
						</section> <?php // end article section ?>

					</article>

				</main>

				<?php get_sidebar(); ?>

		</div>

	</div>

<?php get_footer(); ?>