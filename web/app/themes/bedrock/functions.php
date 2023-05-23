<?php
/**
 * Theme functions and definitions.
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Defines
 */
define( 'THEME_URI', get_template_directory_uri() );
define( 'THEME_URL', get_template_directory() );
define( 'THEME_VER', wp_get_theme()->get( 'Version' ) );

/**
 * Includes
 */
get_template_part( 'admin/post-types' ); // Post Types


/**
 * Theme Options
 */
function bedrock_acf_init() {

	// Init options page
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			array(
				'page_title'      => __( 'Theme Options', 'bedrock' ),
				'menu_title'      => __( 'Theme Options', 'bedrock' ),
				'menu_slug'       => 'theme-general-settings',
				'capability'      => 'edit_posts',
				'redirect'        => false,
				'show_in_graphql' => true,

			)
		);
	}

	// Init Google Maps
	acf_update_setting( 'google_api_key', get_field( 'google_maps_api_key', 'option' ) );
}
add_action( 'acf/init', 'bedrock_acf_init' );

/**
 * Add ACF Options to Admin Bar
 */
function bedrock_options_adminbar( $wp_admin_bar ) {
	if ( current_user_can( 'administrator' ) ) {
		$args = array(
			'id'    => 'theme_options',
			'title' => __( 'Theme Options', 'bedrock' ),
			'href'  => home_url() . '/wp/wp-admin/admin.php?page=theme-general-settings',
		);
		$wp_admin_bar->add_node( $args );
	}
}
add_action( 'admin_bar_menu', 'bedrock_options_adminbar', 999 );

/**
 * Add basic AUTH token to graphql resquests
 */
add_filter(
	'graphql_jwt_auth_secret_key',
	function() {
		return 'k.?z`sP6#$u9+/4NXYUK8u#)YtAmhf2<|e`HNeLg,WPee*i?F@MkLzi=6+87qx>^';
	}
);



/**
 * Create custom mutation to filter branches by map bounds
*/
function add_custom_mutation() {
	register_graphql_mutation(
		'exampleMutation',
		array(
			'inputFields'         => array(
				'exampleInput' => array(
					'type'        => 'String',
					'description' => __( 'Description of the input field', 'your-textdomain' ),
				),
			),
			'outputFields'        => array(
				'exampleOutput' => array(
					'type'        => 'String', // Custom type for the array of post types
					'description' => __( 'Description of the output field', 'your-textdomain' ),
					'resolve'     => function ( $payload, $args, $context, $info ) {
						return isset( $payload['exampleOutput'] ) ? json_encode( $payload['exampleOutput'] ) : null;
					},
				),
			),
			'mutateAndGetPayload' => function ( $input, $context, $info ) {
				// Do any logic here to sanitize the input, check user capabilities, etc
				$exampleOutput = null;
				if ( ! empty( $input['exampleInput'] ) ) {
					$bounds = json_decode( $input['exampleInput'] );

					$qargs = array(
						'post_type'      => 'branch',
						'posts_per_page' => -1,
					);

					$query = new WP_Query( $qargs );
					$posts = array();
					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$address = get_field( 'address' );

							if ( $address['lat'] >= $bounds->south && $address['lat'] <= $bounds->north && $address['lng'] >= $bounds->west && $address['lng'] <= $bounds->east ) {

								$branch = array(
									'title'         => get_the_title(),
									'branchCats'    => array(),
									'branchDetails' => array(
										'openingHours' => get_field( 'opening_hours' ),
										'phone'        => get_field( 'phone' ),
										'gallery'      => array(),
										'address'      => array(
											'latitude'  => $address['lat'],
											'longitude' => $address['lng'],
											'streetAddress' => $address['street_address'],
										),
									),
								);

								// Retrieve branch categories
								$categories = get_the_terms( get_the_ID(), 'branch_cat' );
								if ( $categories && ! is_wp_error( $categories ) ) {
									foreach ( $categories as $category ) {
										$branch['branchCats']['edges'][] = array(
											'node' => array(
												'id'   => $category->term_id,
												'name' => $category->name,
											),

										);
									}
								}

								// Retrieve gallery images
								$gallery = get_field( 'gallery' );
								if ( $gallery ) {
									foreach ( $gallery as $image ) {
										$branch['branchDetails']['gallery'][] = array(
											'id'           => $image['ID'],
											'mediaItemUrl' => $image['url'],
											'altText'      => $image['alt'],
										);
									}
								}

								$posts[] = $branch;

							}
						}

						wp_reset_postdata();
					}

					$exampleOutput = $posts;
				}
				return array(
					'exampleOutput' => $exampleOutput,
				);
			},
		)
	);
}
add_action( 'graphql_register_types', 'add_custom_mutation' );
