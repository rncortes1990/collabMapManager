<?php
/**
*
* Metaboxes
*
*/ 

function layerstore_options_add_meta_box() {
  global $post;
  $pageTemplate = get_post_meta($post->ID, '_wp_page_template', true);
  if ( $pageTemplate == 'template-home-carousel.php' && class_exists( 'WooCommerce' ) ) {
  	add_meta_box(
  		'layerstore_options-layerstore-options',
  		__( 'Homepage Options', 'layerstore' ),
  		'layerstore_options_html',
  		'page',
  		'normal',
  		'high'
  	);
	}
}
add_action( 'add_meta_boxes', 'layerstore_options_add_meta_box' );

function layerstore_options_html( $post) {
	wp_nonce_field( '_layerstore_options_nonce', 'layerstore_options_nonce' ); ?>

	<p>
		<label for="layerstore_carousel_cat"><?php esc_html_e( 'Carousel category', 'layerstore' ); ?></label><br>
		<select name="layerstore_carousel_cat" id="layerstore_carousel_cat">
      <option value=""><?php esc_html_e( 'Select product category', 'layerstore' ); ?></option>
      <?php layerstore_get_cats(); ?>
		</select>
	</p><?php
}

function layerstore_options_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['layerstore_options_nonce'] ) || ! wp_verify_nonce( $_POST['layerstore_options_nonce'], '_layerstore_options_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['layerstore_carousel_cat'] ) )
		update_post_meta( $post_id, 'layerstore_carousel_cat', esc_attr( $_POST['layerstore_carousel_cat'] ) );
}
add_action( 'save_post', 'layerstore_options_save' );



function layerstore_get_cats() {
  /*GET LIST OF CATEGORIES*/
  $args = array(
         'taxonomy'     => 'product_cat',
         'orderby'      => 'name',
         'show_count'   => 1,
  );
  $layercats = get_categories($args); 
  $newList = array();
  foreach($layercats as $category) {
  ?>
      <option <?php echo ( get_post_meta( get_the_ID(), 'layerstore_carousel_cat', true ) === esc_attr( $category->cat_name ) ) ? 'selected' : '' ?>><?php echo esc_attr( $category->cat_name ) ?></option>
  <?php   
  }
  return $newList; 
}