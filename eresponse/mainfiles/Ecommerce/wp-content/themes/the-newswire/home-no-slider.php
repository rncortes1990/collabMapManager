<?php /*Template Name: Home without Slider*/ ?>
<?php get_header(); ?>

    <div id="content" class="clearfix">
        
        <div id="main" class="no-slider clearfix" role="main">

        	<?php 
	        	if( is_home() || is_front_page() ):
				$paged = get_query_var('page');
				$temp = $wp_query;
				$wp_query= null;
				$wp_query = new WP_Query();
				$wp_query->query('post_type=post&paged=' . $paged);
				else:
				$innerpaged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$wp_query = new WP_Query("post_type=post&paged=$innerpaged");
				endif;
             ?>
        
			<?php if ( $wp_query->have_posts() ) : ?>
            	
                <div id="grid-wrap-2" class="clearfix">
				<?php /* Start the Loop */ ?>
				 <?php while ( $wp_query->have_posts() ) : $wp_query->the_post();  ?>

					<div class="grid-box">
					<?php
						/* Include the Post-Format-specific template for the content.
						 * If you want to overload this in a child theme then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					?>

                    </div>

				<?php endwhile; ?>
					<div id="sidebar" class="widget-area-wrap">
					<?php if ( ! dynamic_sidebar( 'sidebar-home' ) ) : ?>

						<aside id="categories" class="widget">
							<div class="widget-title"><?php _e( 'Categories', 'newswire' ); ?></div>
							<ul>
								<?php wp_list_categories( array( 
								'title_li' => '',
								'hierarchical' => 1
								) ); ?>
							</ul>
						</aside>

						<aside id="recent-posts" class="widget">
							<div class="widget-title"><?php _e( 'Latest Posts', 'newswire' ); ?></div>
							<ul>
								<?php
								$args = array( 'numberposts' => '10', 'post_status' => 'publish' );
								$recent_posts = wp_get_recent_posts( $args );

								foreach( $recent_posts as $recent ){
									if ($recent["post_title"] == '') {
										 $recent["post_title"] = __('Untitled', 'newswire');
									}
									echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' . $recent["post_title"] .'</a> </li> ';
								}
								?>
							</ul>
						</aside>

						<aside id="archives" class="widget">
							<div class="widget-title"><?php _e( 'Archives', 'newswire' ); ?></div>
							<ul>
							<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
							</ul>
						</aside>

					<?php endif; // end sidebar widget area ?>
                	</div> 
                	<div class="gutter-sizer"></div>

                </div>
                <?php if (function_exists("newswire_pagination")) {
							newswire_pagination(); 
				} elseif (function_exists("newswire_content_nav")) { 
							newswire_content_nav( 'nav-below' );
				}?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'newswire' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content post-content">
						<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'newswire' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			<?php wp_reset_query(); ?>

        </div> <!-- end #main -->

    </div> <!-- end #content -->
        
<?php get_footer(); ?>