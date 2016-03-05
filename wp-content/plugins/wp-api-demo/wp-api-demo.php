<?php
/**
 * Plugin Name: WP API Demo Custom Functions
 * Description: Special stuff just for the demo
 * Author: Ty Fujimura, Cantilever
 * Author URI: https://cantilever.co
 * Version: 0.1
 */


// Add a custom field to the returned output
// Processes the map field on posts and conveniently creates a static map route from it – could watermark, etc.

add_action( 'rest_api_init', 'add_static_map_field' ); // Note that this has to be low priority in order to have the ACF information available to get_static_map();
function add_static_map_field() {
    register_rest_field( 'post',
        'static-map',
        array(
            'get_callback'    => 'get_static_map',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function get_static_map( $object ) {
  return "https://maps.googleapis.com/maps/api/staticmap?center=" . $object["acf"]["location"]["lat"] . "," . $object["acf"]["location"]["lng"] . "&size=800x800&zoom=14&markers=color:red|" . $object["acf"]["location"]["lat"] . "," . $object["acf"]["location"]["lng"];
}


// Register a ToDo List post type, with REST API support
// Based on example at: http://codex.wordpress.org/Function_Reference/register_post_type

add_action( 'init', 'add_todo_list_cpt' );
function add_todo_list_cpt() {
  $labels = array(
      'name'               => _x( 'ToDo Lists', 'post type general name', 'your-plugin-textdomain' ),
      'singular_name'      => _x( 'ToDo List', 'post type singular name', 'your-plugin-textdomain' ),
      'menu_name'          => _x( 'ToDo Lists', 'admin menu', 'your-plugin-textdomain' ),
      'name_admin_bar'     => _x( 'ToDo List', 'add new on admin bar', 'your-plugin-textdomain' ),
      'add_new'            => _x( 'Add New', 'book', 'your-plugin-textdomain' ),
      'add_new_item'       => __( 'Add New ToDo List', 'your-plugin-textdomain' ),
      'new_item'           => __( 'New ToDo List', 'your-plugin-textdomain' ),
      'edit_item'          => __( 'Edit ToDo List', 'your-plugin-textdomain' ),
      'view_item'          => __( 'View ToDo List', 'your-plugin-textdomain' ),
      'all_items'          => __( 'All ToDo Lists', 'your-plugin-textdomain' ),
      'search_items'       => __( 'Search ToDo Lists', 'your-plugin-textdomain' ),
      'parent_item_colon'  => __( 'Parent ToDo Lists:', 'your-plugin-textdomain' ),
      'not_found'          => __( 'No todo lists found.', 'your-plugin-textdomain' ),
      'not_found_in_trash' => __( 'No todo lists found in Trash.', 'your-plugin-textdomain' )
  );

  $args = array(
      'labels'             => $labels,
      'description'        => __( 'Description.', 'your-plugin-textdomain' ),
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'todo-list' ),
      'capability_type'    => 'post',
      'has_archive'        => true,
      'hierarchical'       => false,
      'menu_position'      => null,
      'show_in_rest'       => true,
      'rest_base'          => 'todo-lists',
      'rest_controller_class' => 'WP_REST_Posts_Controller',
      'supports'           => array( 'title' )
  );

  register_post_type( 'todo-list', $args );
}



// Register an attendee post type, with REST API support
// Based on example at: https://codex.wordpress.org/Function_Reference/register_taxonomy

add_action( 'init', 'add_attendee_taxonomy', 30 );
function add_attendee_taxonomy() {

  $labels = array(
      'name'              => _x( 'Attendees', 'taxonomy general name' ),
      'singular_name'     => _x( 'Attendee', 'taxonomy singular name' ),
      'search_items'      => __( 'Search Attendees' ),
      'all_items'         => __( 'All Attendees' ),
      'parent_item'       => __( 'Parent Attendee' ),
      'parent_item_colon' => __( 'Parent Attendee:' ),
      'edit_item'         => __( 'Edit Attendee' ),
      'update_item'       => __( 'Update Attendee' ),
      'add_new_item'      => __( 'Add New Attendee' ),
      'new_item_name'     => __( 'New Attendee Name' ),
      'menu_name'         => __( 'Attendee' ),
  );

  $args = array(
      'hierarchical'      => true,
      'labels'            => $labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array( 'slug' => 'attendees' ),
      'show_in_rest'       => true,
      'rest_base'          => 'attendees',
      'rest_controller_class' => 'WP_REST_Terms_Controller',
  );

  register_taxonomy( 'attendees', 'post', $args );

}


// Add a custom hook to accomodate the todo list post type better than standard ACF
// Normal ACF to WP API Plugin doesn't support updates. So we can make a custom get and set function for the cleanest possible implementation of our lists.

add_action( 'rest_api_init', 'add_todo_list_field', 30 );
function add_todo_list_field() {
    register_rest_field( 'todo-list',
        'tasks',
        array(
            'get_callback'    => 'get_todo_list',
            'update_callback' => 'update_todo_list',
            'schema'          => null,
        )
    );
}

function get_todo_list( $object ) {
  
  $tasks = get_field("tasks", $object['id']);

  return $tasks;

}

function update_todo_list( $value, $object ) {

  $new_values = json_decode(urldecode($value), true);

  update_field("field_569aaee7a7b28", $new_values, $object->ID); // Annoying inconsistency – $object here is the WP post, not the REST object.

}
