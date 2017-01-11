<?php
/**
 * The template for displaying search results pages.
 *
 * @package bloger Lite
 */

get_header(); ?>
<div class="bloger-wrapper">
    <div id="primary" class="content-area">
    		<main id="main" class="site-main clearfix" role="main">
    		<?php if ( have_posts() ) : ?>
    
    			<header class="page-header">
    				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'bloger' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
    			</header><!-- .page-header -->
    
    			<?php /* Start the Loop */ ?>
    			<?php while ( have_posts() ) : the_post(); ?>
    
    				<?php
    				/**
    				 * Run the loop for the search to output the results.
    				 * If you want to overload this in a child theme then include a file
    				 * called content-search.php and that will be used instead.
    				 */
    				get_template_part( 'template-parts/content', 'search' );
    				?>
    
    			<?php endwhile; ?>
    
    			<?php the_posts_pagination(); ?>
    
    		<?php else : ?>
    
    			<?php get_template_part( 'template-parts/content', 'none' ); ?>
    		<?php endif; ?>            
            
    		</main><!-- #main -->
      </div>
      <?php if(is_active_sidebar('bloger_right_sidebar')) : ?>
        <div class="secondary">
            <div id="featured-post-container" class="clearfix">
                <?php dynamic_sidebar('bloger_right_sidebar'); ?>
            </div>
         </div>
      <?php endif; ?>
</div>
<?php get_footer(); ?>
