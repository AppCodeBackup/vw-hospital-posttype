<?php 
/*
 Plugin Name: VW Hospital Pro Posttype
 Plugin URI: https://www.vwthemes.com/
 Description: Creating new post type for VW Hospital Theme
 Author: VW Themes
 Version: 1.1
 Author URI: https://www.vwthemes.com/
*/

define( 'VW_Hospital_Pro_Posttype_POSTTYPE_VERSION', '1.1' );

/* Testimonials */
add_action( 'init', 'vw_hospital_create_mypost_type' );
function vw_hospital_create_mypost_type() {
  register_post_type( 'testimonials',
    array(
		'labels' => array(
			'name' => __( 'testimonials','vw-hospital' ),
			'singular_name' => __( 'testimonials','vw-hospital' )
		),
		'capability_type' =>  'post',
		'menu_icon'  => 'dashicons-groups',
		'public' => true,
		'supports' => array(
		'title',
		'editor',
		'excerpt',
		'trackbacks',
		'custom-fields',
		'revisions',
		'thumbnail',
		'author',
		'page-attributes',
        )
    )
  );
}

/* Testimonials shorthcode */
function vw_hospital_team_func( $atts ) {
	$testimonial = '';
	
	$query = new wp_query( array( 'post_type' => 'testimonials' ) );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials'); 

	$testimonial .= '<div class="row">';

	while ($new->have_posts()) : $new->the_post();
      	$post_id = get_the_ID();

		$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'medium' );

		if(has_post_thumbnail()) { $thumb_url = $thumb['0']; } else { $thumb_url = get_template_directory_uri(); }

		$excerpt = vw_hospital_pro_string_limit_words(get_the_excerpt(),20);


    	$testimonial .= '
			<div class="col-md-4 col-sm-4 col-xs-4">
				<div class="client">
					<img class="client-img" src="'.$thumb_url.'" alt="testimonial-thumbnail" />
					<div class="client_name"><a href="'.get_permalink().'">'.get_the_title().'</a></div>
					<div class="client_description">'.$excerpt.'</div>
	                <div class="clearfix"></div>
				</div>
			</div>';
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>'; 
		} 
      $k++;			
  endwhile;
  $testimonial .= '</div>';
  else :
  	$testimonial = '<h2 class="center">Not Found</h2>';
  endif;

  return $testimonial;
}
add_shortcode( 'vw-testimonial', 'vw_hospital_team_func' );

//custom Doctor registered post
add_action( 'init','vw_hospital_create_mypost_typeDoc' );
add_action( 'init', 'createBrand', 0 );
add_action( 'add_meta_boxes', 'vw_hospital_cs_custom_meta' );
add_action( 'save_post', 'vw_hospital_bn_metadesig_saveDoc' );

// function vw_hospital_create_mypost_typeDoc() {
//   register_post_type( 'doctors',
//     array(
//       'labels' => array(
//         'name' => __( 'Doctors', 'vw-hospital' ),
//         'singular_name' => __( 'Doctors', 'vw-hospital' )
//       ),
//       'menu_icon'  => 'dashicons-plus',
//       'public' => true,
//       'has_archive' => true,
//       'supports' => array(
// 		'title',
// 		'editor',
// 		'excerpt',
// 		'custom-fields',
// 		'revisions',
// 		'thumbnail',
// 		'author',
// 		'page-attributes',
//         )
//     )
//   );
// }

function vw_hospital_create_mypost_typeDoc() {
  register_post_type( 'Doctors',
    array(
      'labels' => array(
        'name' => __( 'Doctors','vw-hospital' ),
        'singular_name' => __( 'Doctors','vw-hospital' )
      ),
        'capability_type' => 'post',
        'menu_icon'  => 'dashicons-businessman',
        'public' => true,
        'supports' => array( 
          'title',
          'editor',
          'thumbnail'
      )
    )
  );
}


// -----------------------------///
/* Single testimonials Post Type */



function vw_hospital_doctor_func( $atts ) {
  $doctors = '';
  $doctors = '<div class="row all-courses">';
  $query = new WP_Query( array( 'post_type' => 'doctors',
  	'posts_per_page' => -1
	) );

    if ( $query->have_posts() ) :

  $k=1;

  $doctors .= '<div class="row">';


  while ($query->have_posts()) : $query->the_post();

        $post_id = get_the_ID();
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
    
        
        // if(get_post_meta($post_id,'meta-courses-url',true !='')){$custom_url =get_post_meta($post_id,'meta-courses-url',true); } else{ $custom_url = get_permalink(); }

        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; } else { $thumb_url = get_template_directory_uri(); }

		$excerpt = vw_hospital_pro_string_limit_words(get_the_excerpt(),20);
        $doctors .= '
        <div class="col-md-4 col-sm-4 col-xs-4 mt-2">
				<div class="client">
					<img class="doctor-img" src="'.$thumb_url.'" alt="" />
					<div class="doctor_name"><a href="'.get_permalink().'">'.get_the_title().'</a></div>
					<div class="doctor_description">'.$excerpt.'</div>
	                <div class="clearfix mt-2"></div>
				</div>
			</div>';
            
    if($k%3 == 0){
      $doctors.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $doctors = '<h2 class="center">'.esc_html__('Post Not Found','vw_hospital_doctor_func_pro_posttype').'</h2>';
  endif;
  return $doctors;
}
add_shortcode( 'vw-doctors', 'vw_hospital_doctor_func' );


function createBrand() {
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => __( 'Doctors Categories', 'vw-hospital' ),
		'singular_name'     => __( 'Doctors Category', 'vw-hospital' ),
		'search_items'      => __( 'Search Ccats', 'vw-hospital' ),
		'all_items'         => __( 'All Doctors Categories', 'vw-hospital' ),
		'parent_item'       => __( 'Parent Doctors Category', 'vw-hospital' ),
		'parent_item_colon' => __( 'Parent Doctors Category:', 'vw-hospital' ),
		'edit_item'         => __( 'Edit Doctors Category', 'vw-hospital' ),
		'update_item'       => __( 'Update Doctors Category', 'vw-hospital' ),
		'add_new_item'      => __( 'Add New Doctors Category', 'vw-hospital' ),
		'new_item_name'     => __( 'New Doctors Category Name', 'vw-hospital' ),
		'menu_name'         => __( 'Doctors Category', 'vw-hospital' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'createbrand' ),
	);

	register_taxonomy( 'createbrand', array( 'doctors' ), $args );
}

/* Adds a meta box to the post editing screen */
function vw_hospital_cs_custom_meta() {
    add_meta_box( 'cs_meta', __( 'Settings', 'vw-hospital' ),  'vw_hospital_cs_meta_callback' , 'doctors','normal', 'high' );    

}

/* Outputs the content of the meta box */
function vw_hospital_cs_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_day = get_post_meta( $post->ID, 'meta-Day', true );
    $meta_time = get_post_meta( $post->ID, 'meta-Time', true );
    ?>
	<div id="postcustom">
		<table id="list-table" style="background: #fff;padding: 1%;border: 0;">			
			<tbody id="the-list" data-wp-lists="list:meta">				
				<tr id="meta-2">
					<td class="left">
						<?php esc_html_e( 'Working Shifts', 'vw-hospital' )?>
					</td>
					<td class="left" >
						<input type="text" name="meta-Day" id="meta-Day" value="<?php echo esc_attr( $meta_day ); ?>" />
					</td>										
				</tr>
				<tr id="meta-3">
					<td class="left">
						<?php esc_html_e( 'Available From', 'vw-hospital' )?>
					</td>
					<td class="left" >
						<input type="text" name="meta-Time" id="meta-Time" value="<?php echo esc_attr( $meta_time ); ?>" />
					</td>					
				</tr>
			</tbody>
		</table>
	</div>
    <?php
}

/* Saves the custom Designation meta input */
function vw_hospital_bn_metadesig_saveDoc( $post_id ) {
	if( isset( $_POST[ 'meta-Day' ] ) ) {
	    update_post_meta( $post_id, 'meta-Day', $_POST[ 'meta-Day' ] );
	}
	if( isset( $_POST[ 'meta-Time' ] ) ) {
	    update_post_meta( $post_id, 'meta-Time', $_POST[ 'meta-Time' ] );
	}
}

/**********Appointment Setting**************/
add_action( 'init','vw_hospital_create_appointment_type' );
add_action( 'add_meta_boxes', 'vw_hospital_cs_savecustom_meta' );
add_action( 'save_post', 'vw_hospital_bn_appointdesig_saveDoc' );

function vw_hospital_create_appointment_type() {
  register_post_type( 'appointment',
    array(
		'labels' => array(
			'name' => __( 'Appointment','vw-hospital' ),
			'singular_name' => __( 'Appointment','vw-hospital' )
		),
		'capability_type' =>  'post',
		'menu_icon'  => 'dashicons-welcome-write-blog',
		'public' => true,
		'supports' => array(
		'title',		
		'excerpt',
		'thumbnail',
		'page-attributes',
        )
    )
  );
}

/* Adds a meta box to the post editing screen */
function vw_hospital_cs_savecustom_meta() {
    add_meta_box( 'cs_meta', __( 'Settings', 'vw-hospital' ),  'w_hospital_cs_appoint_callback' , 'Appointment','normal', 'high' ); 
}

/**
 * Outputs the content of the meta box
*/
function w_hospital_cs_appoint_callback( $post ) {  
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $meta_area = get_post_meta( $post->ID, 'meta-area', true );
    $meta_pname = get_post_meta( $post->ID, 'meta-pname', true );
    $meta_pemail = get_post_meta( $post->ID, 'meta-pemail', true );
    $term_id= $meta_area; //Category Id
     
	//fetching category id
    $term_data = get_term( $term_id ); ?>
	<div id="postcustom">
		<table id="list-table" style="background: #fff;padding: 1%;border: 0;">			
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta">
					<td class="left">
						<?php  esc_html_e( 'Practice area', 'vw-hospital' )?>
					</td>
					<td class="left">
						<input type="text" name="meta-area" id="meta-area" value="<?php echo esc_attr($term_data->name); ?>" />
					</td>					
				</tr>				
				<tr id="meta-3">
					<td class="left">
						<?php  esc_html_e( 'Patient Name', 'vw-hospital' )?>
					</td>
					<td class="left" >
						<input type="text" name="meta-pname" id="meta-pname" value="<?php echo esc_attr($meta_pname); ?>" />
					</td>					
				</tr>
				<tr id="meta-3">
					<td class="left">
						<?php  esc_html_e( 'Patient Email Address', 'vw-hospital' )?>
					</td>
					<td class="left" >
						<input type="text" name="meta-pemail" id="meta-pemail" value="<?php echo esc_attr($meta_pemail); ?>" />
					</td>					
				</tr>
			</tbody>
		</table>
	</div>
    <?php
}
/* Saves the custom Designation meta input */
function vw_hospital_bn_appointdesig_saveDoc( $post_id ) {
	if( isset( $_POST[ 'meta-area' ] ) ) {
	    update_post_meta( $post_id, 'meta-area', $_POST[ 'meta-area' ] );
	}
	if( isset( $_POST[ 'meta-Time' ] ) ) {
	    update_post_meta( $post_id, 'meta-Time', $_POST[ 'meta-Time' ] );
	}
	if( isset( $_POST[ 'meta-pname' ] ) ) {
	    update_post_meta( $post_id, 'meta-pname', $_POST[ 'meta-pname' ] );
	}
	if( isset( $_POST[ 'meta-pemail' ] ) ) {
	    update_post_meta( $post_id, 'meta-pemail', $_POST[ 'meta-pemail' ] );
	}	
}