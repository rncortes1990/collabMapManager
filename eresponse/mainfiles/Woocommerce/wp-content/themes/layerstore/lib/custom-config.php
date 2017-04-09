<?php
  if ( !class_exists( 'Kirki' ) ) {
    return;
  }
  if ( class_exists( 'WooCommerce' ) && get_option( 'show_on_front' ) != 'page' ) {
  	Kirki::add_section( 'layerstore_woo_demo_section', array(
  		'title'		 => __( 'WooCommerce Homepage Demo', 'layerstore' ),
  		'priority'	 => 10,
  	) );
  }
  
  Kirki::add_field( 'layerstore_settings', array(
  	'type'			 => 'switch',
  	'settings'		 => 'layerstore_demo_front_page',
  	'label'			 => __( 'Enable Demo Homepage?', 'layerstore' ),
  	'description'	 => sprintf( __( 'When the theme is first installed and WooCommerce plugin activated, the demo mode would be turned on. This will display some sample/example content to show you how the website can be possibly set up. When you are comfortable with the theme options, you should turn this off. You can create your own unique homepage - Check the %s page for more informations.', 'layerstore' ), '<a href="' . admin_url( 'themes.php?page=maxstore-welcome' ) . '"><strong>' . __( 'Theme info', 'layerstore' ) . '</strong></a>' ),
  	'section'		 => 'layerstore_woo_demo_section',
  	'default'		 => 1,
  	'priority'		 => 10,
  ) );
  Kirki::add_field( 'layerstore_settings', array(
  	'type'				 => 'radio-buttonset',
  	'settings'			 => 'layerstore_front_page_demo_style',
  	'label'				 => esc_html__( 'Homepage Demo Styles', 'layerstore' ),
  	'description'		 => sprintf( __( 'The demo homepage is enabled. You can choose from some predefined layouts or make your own %s.', 'layerstore' ), '<a href="' . admin_url( 'themes.php?page=maxstore-welcome' ) . '"><strong>' . __( 'custom homepage template', 'layerstore' ) . '</strong></a>' ),
  	'section'			 => 'layerstore_woo_demo_section',
  	'default'			 => 'style-one',
  	'priority'			 => 10,
  	'choices'			 => array(
  		'style-one'	 => __( 'Layout one', 'layerstore' ),
  		'style-two'	 => __( 'Layout two', 'layerstore' ),
  	),
  	'active_callback'	 => array(
  		array(
  			'setting'	 => 'demo_front_page',
  			'operator'	 => '==',
  			'value'		 => 1,
  		),
  	),
  ) );
  Kirki::add_field( 'layerstore_settings', array(
  	'type'				 => 'switch',
  	'settings'			 => 'layerstore_front_page_demo_carousel',
  	'label'				 => __( 'Homepage carousel', 'layerstore' ),
  	'description'		 => esc_html__( 'Enable or disable demo homepage carousel with random category.', 'layerstore' ),
  	'section'			 => 'layerstore_woo_demo_section',
  	'default'			 => 1,
  	'priority'			 => 10,
  	'active_callback'	 => array(
  		array(
  			'setting'	 => 'demo_front_page',
  			'operator'	 => '==',
  			'value'		 => 1,
  		),
  	),
  ) );


  Kirki::add_field( 'layerstore_settings', array(
  	'type'				 => 'custom',
  	'settings'			 => 'layerstore_demo_page_intro',
  	'label'				 => __( 'Products', 'layerstore' ),
  	'section'			 => 'layerstore_woo_demo_section',
  	'description'		 => esc_html__( 'If you dont see any products or categories on your homepage, you dont have any products probably. Create some products and categories first.', 'layerstore' ),
  	'priority'			 => 10,
  	'active_callback'	 => array(
  		array(
  			'setting'	 => 'demo_front_page',
  			'operator'	 => '==',
  			'value'		 => 1,
  		),
  	),
  ) );
  Kirki::add_field( 'layerstore_settings', array(
  	'type'			 => 'custom',
  	'settings'		 => 'layerstore_demo_dummy_content',
  	'label'			 => __( 'Need Dummy Products?', 'layerstore' ),
  	'section'		 => 'layerstore_woo_demo_section',
  	'description'	 => sprintf( esc_html__( 'When the theme is first installed, you dont have any products probably. You can easily import dummy products with only few clicks. Check %s tutorial.', 'layerstore' ), '<a href="' . esc_url( 'https://docs.woocommerce.com/document/importing-woocommerce-dummy-data/' ) . '" target="_blank"><strong>' . __( 'THIS', 'layerstore' ) . '</strong></a>' ),
  	'priority'		 => 10,
  ) );
  Kirki::add_field( 'layerstore_settings', array(
  	'type'			 => 'custom',
  	'settings'		 => 'layerstore_demo_pro_features',
  	'label'			 => __( 'Need More Features?', 'layerstore' ),
  	'section'		 => 'layerstore_woo_demo_section',
  	'description'	 => '<a href="' . esc_url( 'http://themes4wp.com/product/maxstore-pro/' ) . '" target="_blank" class="button button-primary">' . sprintf( esc_html__( 'Learn more about %s PRO', 'layerstore' ), 'MaxStore' ) . '</a>',
  	'priority'		 => 10,
  ) );