<?php
/**
 * Register post types:
 */
function register_post_types() {

	register_post_type(
		'branch',
		$args = array(
			'label'               => __( 'Branch', 'bedrock' ),
			'description'         => __( 'Branch', 'bedrock' ),
			'labels'              => array(
				'name'           => __( 'Branches', 'bedrock' ),
				'singular_name'  => __( 'Branch', 'bedrock' ),
				'menu_name'      => __( 'Branches', 'bedrock' ),
				'name_admin_bar' => __( 'Branch', 'bedrock' ),
			),
			'supports'            => array( 'title', 'editor', 'revisions' ),
			'hierarchical'        => false,                     // default: false
			'public'              => true,                      // default: false
			'show_ui'             => true,                      // default: value of $public
			'show_in_menu'        => true,                      // default: value of $show_in_menu
			'menu_position'       => 5,                         // default: null
			'show_in_admin_bar'   => true,                      // default: value of $public
			'show_in_nav_menus'   => true,                      // default: value of $public
			'can_export'          => true,                      // default: true
			'has_archive'         => true,                      // default: false
			'exclude_from_search' => false,                     // default: opposite value of $public
			'publicly_queryable'  => true,                      // default: false
			'show_in_graphql'     => true,
			'graphql_single_name' => 'branch',
			'graphql_plural_name' => 'branches',
		)
	);

}
add_action( 'init', 'register_post_types', 0 );

/**
 * Register taxonomies:
 */
function register_taxonomies() {

	register_taxonomy(
		'branch_cat',
		array( 'branch' ),
		$args = array(
			'labels'              => array(
				'name'          => __( 'Branches Categories', 'bedrock' ),
				'singular_name' => __( 'Branch Category', 'bedrock' ),
				'menu_name'     => __( 'Branches Categories', 'bedrock' ),
			),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_admin_column'   => true,
			'show_in_nav_menus'   => true,
			'show_tagcloud'       => true,
			'show_in_rest'        => true,
			'show_in_graphql'     => true,
			'graphql_single_name' => 'branchCat',
			'graphql_plural_name' => 'branchCats',

		)
	);
}
add_action( 'init', 'register_taxonomies', 0 );
