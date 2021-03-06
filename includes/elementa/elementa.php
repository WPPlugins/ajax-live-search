<?php

/**
 * Elemnta main Class
 *
 * Rename the Elementa class & the EL() function to something unique
 *
 */

if ( ! defined( 'ABSPATH' ) ) {	
	exit; // Exit if accessed directly.
}

// Incase the class is already loaded

if ( class_exists( 'WP_Elements_Als' ) ) {
	return;
}


/**
 * Main WP_Elements Class.
 *
 * @class WP_Elements
 * @version	0.1.0
 */
class WP_Elements_Als {

	/**
	 * Current version.
	 *
	 * @var string
	 */
	public $version = '0.1.1';

	/**
	 * The single instance of the class.
	 *
	 * @var Object
	 * @since 0.1.0
	 */
	protected static $_instance = null;
	
	/**
	 * Unique registered instances (ids)
	 *
	 * This is simply an array of options 
	 * belonging to each unique id passed along when calling EL()
	 *
	 * array(
	 * 		'elements' => array(), Contains an array of all elements enqueud to this instance
	 *		'defaults' => array(), Contains all the default values declared for this instance 
	 *		'user_settings' => array(), Contains the values a user sets 
	 * )
	 *
	 * @var array
	 */
	protected $instances = array();
	
	/**
	 * Unique hook suffixes
	 *
	 * This is simply an array of hooks suffixes
	 * belonging to each unique id passed along when calling EL()
	 *
	 * Used to conditionally load assets
	 * @see self::set_instance_args()
	 * @var array
	 */
	protected $hook_suffixes = array();
	
	/**
	 * An array of all registered elements
	 *
	 * @see self::register_element()
	 * @var array
	 */
	protected $elements = array();
	
	/**
	 * Current instance id
	 * Evaluates to the value passed onto EL() when accessing this object
	 *
	 * @see self::instance()
	 * @var string
	 */
	protected $instance_id = false;
	
	/**
	 * The plugin base url
	 *
	 * Used when loading assets. If you decide to customize elementa assets;
	 * Copy them to another path and pass the path here. This makes it easy to 
	 * upgrade to the next version of elementa
	 *
	 * @see self::__construct()
	 * @var string
	 */
	public $base_url;
	
	/**
	 * Callbacks used to retrieve custom form data such as users and posts
	 *
	 * You can register your own callback by calling self::register_data_callback()
	 * @see self::get_data()
	 * @var string
	 */
	public $data_callbacks = array();

	/**
	 * Main Elementa Instance.
	 *
	 * Ensures only one instance of Elementa is loaded or can be loaded.
	 * 
	 * @since 0.1.0
	 * @return Elementa - Main instance.
	 */
	public static function instance( $instance_id = false ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		// If an id is provided and does not exist in the list of instances; add it
		if ( $instance_id !== false && !isset( self::$_instance->instances[$instance_id] )) {
			self::$_instance->instances[$instance_id] = array();
			self::$_instance->instances[$instance_id]['elements'] = array();
			self::$_instance->instances[$instance_id]['defaults'] = array();
		}
		
		// Change the value of the current instance id since most functions depend on it 

		self::$_instance->instance_id = $instance_id;
		return self::$_instance;
	}


	/**
	 * Elementa Constructor.
	 *
	 */
	public function __construct() {
		
		$this->register_core_elements();
		$this->register_core_data_callbacks();
		$this->base_url = plugins_url( '/', __FILE__ );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqeue_scripts'), 5);
		
	}
	
	/**
	 * Registers core elements
	 *
	 *
	 */
	public function register_core_elements() {
		
		$elements = array(
		
			'title' => array(
				'callback' => array( $this, 'default_cb'),
				'render_default_markup' => false,
			),
			
			'textarea' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'text' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'email' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			//Displays a color picker
			'color' => array(
				'callback' => array( $this, 'default_cb'),
				'enque' => array( $this, 'enque_color'),
			),
			
			//Displays a date picker
			'date' => array(
				'callback' => array( $this, 'default_cb'),
				'enque' => array( $this, 'enque_date'),
			),
			
			'search' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'number' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'password' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			//Displays a selectize select box
			'select' => array(
				'callback' => array( $this, 'default_cb'),
				'enque' => array( $this, 'enque_select'),
			),
			
			'multiselect' => array(
				'callback' => array( $this, 'default_cb'),
				'enque' => array( $this, 'enque_select'),
			),
			
			'button' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			//Displays the save and reset buttons
			'save' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			//Displays the import and export buttons
			'import' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'checkbox' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'radio' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			//Displays a yes / no
			'switch' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'list_group' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'alert' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			'card' => array(
				'callback' => array( $this, 'default_cb'),
			),
			
			//Offers full flexibility
			'raw' => array(
				'callback' => array( $this, 'default_cb'),
			),
		);

		$this->register_multiple_elements( $elements );
		
	}
	
	/**
	 * A catch all callback that renders default elements
	 *
	 */
	public function default_cb( $args ) {
		if ( isset ( $args['type'] ) )
			include "elements/{$args['type']}.php";
	}
	
	/**
	 * Enques styles for select elements
	 *
	 */
	public function enque_select() {
		wp_enqueue_script( 'selectize', $this->base_url . '/assets/js/selectize.min.js', array( 'jquery' ), '4.0.3', false );	
		wp_enqueue_style( 'selectize-bootstrap3', $this->base_url . '/assets/css/selectize.bootstrap3.css' );
	}
	
	/**
	 * Enques styles for date elements
	 *
	 */
	public function enque_date() {
		wp_enqueue_script( 'wp-datepicker', $this->base_url . '/assets/js/datepicker.min.js', array( 'jquery' ), '0.4.0', true );
		wp_enqueue_style( 'wp-datepicker', $this->base_url . '/assets/css/datepicker.css' );
	}
	
	/**
	 * Enques styles for color elements
	 *
	 */
	public function enque_color() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
	}
	
	/**
	 * Registers multiple elements at once
	 *
	 */
	public function register_multiple_elements( $args = array() ) {
		if( !is_array( $args ) )
			return;		
		
		foreach ( $args as $element => $options ) {			
			$this->register_element( $element, $options );			
		}
		
	}
	
	/**
	 * Registers a new element
	 *
	 * An element needs a render callback at minimum
	 */
	public function register_element( $element_type = false, $args = array() ) {
		if( $element_type !== false )
			$this->elements[$element_type] = $args;		
	}
	
	/**
	 * Returns a list of all registered elements
	 *
	 */
	public function get_registered_elements(  ) {
		return array_keys( $this->elements );
	}
	
	/**
	 * Updates an existing element
	 *
	 */
	public function update_element( $element_type = false, $key = false, $value = false ) {
		if( $element_type !== false && isset( $this->elements[$element_type] ) )
			$this->elements[$element_type][$key] = $value;		
	}
	
		
											
	/**
	 * @Deprecated. 
	 *
	 * @see self::queue_element
	 * @since 0.1.0
	 * @access public
	 */
	public function queue_control( $element_id = false, $args = array() ) {
		$this->queue_element( $element_id, $args );
						
	}

	/**
	 * Queues an element for rendering
	 *
	 * @since 0.1.1
	 * @access public
	 */
	public function queue_element( $element_id = false, $args = array() ) {
		
		if( $element_id !== false && $this->instance_id !== false) {
			
			$args['id'] = $element_id;
			$this->instances[$this->instance_id]['elements'][] = array(
				'id' => $element_id,
				'args' => $args
			);
			
			//Set it in a separate array to allow easy access; no need to loop the above  array
			if ( isset ( $args['default'] )) {
				$this->instances[$this->instance_id]['defaults'][$element_id] = $args['default'];
			}
						
		}
						
	}
	
	/**
	 * Renders a registered element
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function render_element( $args = array() ) {
	
		//If no element is provided or it lacks a render callback return early
		if ( !isset ( $args['type'] ) || !isset ( $this->elements[ $args['type'] ][ 'callback' ] ) )
			return;
		
		if ( !is_array ( $args ) )
			$args = array();
						
		//Normalize the user args
		$args = $this->clean_args( $args );
		
		$element = $this->elements[ $args['type'] ];
		
		//Optionally render a default markup
		$default_markup = ( !isset ( $element['render_default_markup'] ) || $element['render_default_markup'] == true );
		
		if( $default_markup ) {
			$this->render_wrapper_open( $args );
		}	
		
		//Call the element's render function
		call_user_func( $this->elements[ $args['type'] ][ 'callback' ], $args );
		
		if( $default_markup ) {			
			$this->render_wrapper_end( $args );			
		}
			
	}
	
	/**
	 * Outputs the settings page
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function render_wrapper_open( $args ) {
	
		$is_full_field = ( isset( $args['full_width'] ) && $args['full_width'] == true );
		$content_class = 'col s12';
		$class = 'elementa-row';
		
		if( isset ( $args['section'] ) &&  $args['section'] )			
			$class .= ' wp-section-wrapper-' . sanitize_html_class( $args['section'] );
		
		echo "<div class='$class'>";
		
		//If a title is set; reduce the content class
		if ( isset( $args['title'] ) ) {
		
			$content_class = 'col s12 m8';
			$title_class = 'col s12 m4';
			if ( $is_full_field ) {
				$title_class = 'col s12';
				$content_class = 'col s12';
			}
			
			$title = '<strong>' . $args['title']. '</strong>';
			
			if ( isset( $args['subtitle'] ) ) {
				$title .= "<br />{$args['subtitle']}";
			}
			
			echo "<div class='$title_class'>$title</div>";
			
		}
				
		echo "<div class='$content_class'>";		
		
	}
	
	/**
	 * Outputs the closing wrapper around rendered elements
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function render_wrapper_end( $args ) {	
		echo '</div></div>';
	}
			
	/**
	 * Normalizes element render args
	 *
	 * @since 0.1.0
	 * @access public
	 */
	private function clean_args( $args ) {
					
		//Data attibutes
		if(! isset( $args['custom_attributes'] )) {
			$args['custom_attributes'] = array();
		}	
		
		$args['_custom_attributes'] = '';
		
		foreach ( $args['custom_attributes'] as $attr => $value ) {
			$attr = esc_attr( $attr );
			$value = esc_attr( $value );
			$args['_custom_attributes'] .= " $attr='$value'";
		}
				
		//Default
		if(! isset( $args['default'] )) {
			$args['default'] = '';
		}
		
		//Description
		if(! isset( $args['description'] )) {
			$args['description'] = '';
		}
		
		//Placeholder
		if(! isset( $args['placeholder'] )) {
			$args['placeholder'] = '';
		}
		
		//Option details for select etc
		if(! isset( $args['options'] )) {
			$args['options'] = array();
		}
		
		//Data args
		if(! isset( $args['data_args'] )) {
			$args['data_args'] = array();
		}
		
		//Data, replaces the options field 
		if( isset( $args['data'] ) ) {
			
			$data = $this->get_data( $args['data'], $args['data_args'] );
			if(! empty( $data ) ) {
				$args['options'] = $data;
			}		
		}
		
		//Class
		if(! isset( $args['class'] )) {
			$args['class'] = '';
		}
		
		//Value == current value
		$args['_value'] = $args['_current'] = $this->get_option( $args['id'] );
		
		//Id attribute
		$args['__id'] = esc_attr( $args['id'] );
		
		return $args;
	}
		
	/**
	 * Returns the data provided by a given data callback
	 */
	public function get_data( $type = '', $args = array()) {
		
		if( empty ( $type ) || !is_string( $type ) || !is_array( $args ))
			return array();
		
		$type = strtolower( $type );
		$callbacks = $this->data_callbacks;

		if( !isset ( $this->data_callbacks[ $type ] ) )
			return array();
		
		return call_user_func( $this->data_callbacks[$type], $args );

	}
	
	/**
	 * Registers data callbacks  
	 *
	 * Take a look at self::register_core_data_callbacks  to see how it works.
	 * If the data callback exists; it will be overwritten. Make sure that 
	 * The callback returns an array of name=>label pairs
	 *
	 * @var $data string Required. The type of data e.g post
	 * @var $callback array Required. The arguments used to fetch your data callback
	 */
	public function register_data_callback( $data, $callback = array() ) {		
		if (is_string( $data ) ) {			
			$this->data_callbacks[$data] = $callback;			
		}	
	}
	
	/**
	 * Returns an array of all registered data callbacks
	 */
	public function get_registered_data_callbacks( ) {		
		return $this->data_callbacks;
	}
	
	/**
	 * Registers multiple data callbacks  
	 */
	public function register_core_data_callbacks() {
		
		//Categories
		$this->register_data_callback( 'category', array( $this, 'get_categories' ));
		$this->register_data_callback( 'categories', array( $this, 'get_categories' ));
		
		//Tags
		$this->register_data_callback( 'tag', array( $this, 'get_tags' ));
		$this->register_data_callback( 'tags', array( $this, 'get_tags' ));
		$this->register_data_callback( 'post_tag', array( $this, 'get_tags' ));
		
		//Taxonomies
		$this->register_data_callback( 'taxonomy', array( $this, 'get_taxonomies' ));
		$this->register_data_callback( 'taxonomies', array( $this, 'get_taxonomies' ));
		
		//Posts 
		$this->register_data_callback( 'post', array( $this, 'get_posts' ));
		$this->register_data_callback( 'posts', array( $this, 'get_posts' ));
		
		//Menus
		$this->register_data_callback( 'menus', array( $this, 'get_menus' ));
		$this->register_data_callback( 'menu', array( $this, 'get_menus' ));
		
		//Pages 
		$this->register_data_callback( 'page', array( $this, 'get_pages' ));
		$this->register_data_callback( 'pages', array( $this, 'get_pages' ));
		
		//Post types 
		$this->register_data_callback( 'post_types', array( $this, 'get_post_types' ));
		$this->register_data_callback( 'post_type', array( $this, 'get_post_types' ));
		
		//Post statuses
		$this->register_data_callback( 'post_statuses', array( $this, 'get_post_statuses' ));
		$this->register_data_callback( 'post_status', array( $this, 'get_post_statuses' ));
		
		//Users
		$this->register_data_callback( 'user', array( $this, 'get_users' ));
		$this->register_data_callback( 'users', array( $this, 'get_users' ));
		
		//Roles
		$this->register_data_callback( 'roles', array( $this, 'get_roles' ));
		$this->register_data_callback( 'role', array( $this, 'get_roles' ));
		$this->register_data_callback( 'user_roles', array( $this, 'get_roles' ));
		$this->register_data_callback( 'user_role', array( $this, 'get_roles' ));
		
		//Capabilities
		$this->register_data_callback( 'capabilities', array( $this, 'get_capabilities' ));
		$this->register_data_callback( 'capability', array( $this, 'get_capabilities' ));
		$this->register_data_callback( 'user_capabilities', array( $this, 'get_capabilities' ));
		$this->register_data_callback( 'user_capability', array( $this, 'get_capabilities' ));
		
		//Countries
		$this->register_data_callback( 'country', array( $this, 'get_countries' ));
		$this->register_data_callback( 'countries', array( $this, 'get_countries' ));

	}
	
	/**
	 * A helper function to modify custom data
	 *
	 * It extracts the key and value fields from the data 
	 */
	protected function modify_custom_data( $data, $key, $value ) {
		
		$return = array();
		$data = $data;
		
		foreach ( $data as $single ) {
			
			if ( is_array( $single ) ) {
				$return[ $single[$key] ] = $single[$value];
			} 
			
			if ( is_object( $single ) ) {
				$return[ $single->$key ] = $single->$value;
			}
			
		}
		
		return $return;
		
	}
	
	/**
	 * Returns an array of post categories
	 */
	public function get_categories( $args ) {
		return $this->modify_custom_data( get_categories( $args ), 'term_id', 'name' );
	}
	
	/**
	 * Returns an array of post tags
	 */
	public function get_tags( $args ) {
		return $this->modify_custom_data( get_tags( $args ), 'term_id', 'name' );
	}
	
	/**
	 * Returns an array of taxonomies
	 */
	public function get_taxonomies( $args ) {
		return $this->modify_custom_data( get_taxonomies( $args, false ), 'name', 'label' );
	}
	
	/**
	 * Returns an array of posts
	 */
	public function get_posts( $args ) {
		return $this->modify_custom_data( get_posts( $args ), 'ID', 'post_title' );
	}
	
	/**
	 * Returns an array of menus
	 */
	public function get_menus( $args ) {
		return $this->modify_custom_data( wp_get_nav_menus( $args ), 'term_id', 'name' );
	}
	
	/**
	 * Returns an array of pages
	 */
	public function get_pages( $args ) {
		return $this->modify_custom_data( get_pages( $args ), 'ID', 'post_title' );
	}
	
	/**
	 * Returns an array of post types
	 */
	public function get_post_types( $args ) {
		return $this->modify_custom_data( get_post_types( $args, false ), 'name', 'label' );
	}
	
	/**
	 * Returns an array of post statuses
	 */
	public function get_post_statuses( $args ) {
		
		global $wp_post_statuses;
		$return = array();
							
		foreach($wp_post_statuses as $status => $details ) {
			$return[ $status ] = $details->label;
		}
							
		return $return;
		
	}
	
	/**
	 * Returns an array of countries
	 */
	public function get_countries( $args ) {
		return require( 'data/countries.php' );
	}
	
	/**
	 * Returns an array of users
	 */
	public function get_users( $args ) {
		return $this->modify_custom_data( get_users( $args, false ), 'ID', 'display_name' );
	}
	
	/**
	 * Returns an array of user roles
	 */
	public function get_roles( $args ) {
		global $wp_roles;						
		return $wp_roles->role_names;
	}
	
	/**
	 * Returns an array of all user capabilities or capabilities for the given user type
	 */
	public function get_capabilities( $args ) {
		
		global $wp_roles;						
		$capabilities = array();
		
		if( !isset( $args['user_type'] ) ) {

			foreach ( $wp_roles->roles as $role) {
				
				foreach ( $role['capabilities'] as $cap => $bool ) {									
					if( $bool == true )
						$capabilities[$cap] = $cap;										
				}
				
			}
		} else {							
			if ( isset ($wp_roles->roles[$args['user_type']]) ){
								
				foreach ( $wp_roles->roles[$args['user_type']]['capabilities'] as $cap => $bool ) {									
					if( $bool == true )
						$capabilities[$cap] = $cap;										
				}
								
			}							
		}
		
		return array_map( array( $this, 'titalize' ) , $capabilities);
		
	}
	
	/**
	 * Converts a string to readable form
	 */
	public function titalize( $string ) {				
		return ucfirst( str_replace( '_', ' ', $string ) );
	}	
	
	/**
	 * Sets the rendering template
	 */
	public function set_template( $template = false ) {
		if ( $this->instance_id !== false && $template !== false )
			$this->instances[$this->instance_id]['template'] = $template;		
	}
	
	/**
	 * Outputs the settings page
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function render() {
		
		if( $this->instance_id === false )
			return;
		
		//Save settings if data has been posted
		if ( ! empty( $_POST ) )			
			$this->save();
					
		$template = 'template.php';
		if ( isset ( $this->instances[ $this->instance_id ]['template'] ) )
			$template = $this->instances[ $this->instance_id ]['template'];
		
		$elements = array();
		if ( isset ( $this->instances[ $this->instance_id ]['elements'] ) )
			$elements = $this->instances[ $this->instance_id ]['elements'];
		
		require_once ( $template );

	}
	
	/**
	 * Saves submitted data
	 *
	 * This is method is called when the render function is called to ensure that data 
	 * is saved only when the right page is requested.
	 *
	 * @since  0.1.0
	 * @access protected
	 */
	protected function save() {
		
		//Will forever evaluate to true.
		if( $this->instance_id === false )
			return;
		
		//Make sure to always include an elementa nonce field in your templates unless you save your own settings
		if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'elementa' ) )			
			return;
		
		//Data is being imported.
		if ( isset( $_POST['elementa-imported-data'] ) ){
			return $this->_save( (array) json_decode( wp_unslash( $_POST['elementa-imported-data'] ) ) );
		}
		
		//Data is reset.
		if ( isset( $_POST['wpe-reset'] ) ){
			return $this->_save( $this->get_defaults( $this->instance_id ) ) ;
		}
		
		return $this->_save( $_POST ); 

	}
	
	/**
	 * Saves dava to the db
	 *
	 *
	 * @since 0.1.1
	 * @access protected
	 */
	protected function _save( $data ) {

		$data = wp_unslash( $data );

		if ( is_array ( $data )) {
					
			unset( $data['_wp_http_referer'] );
			unset( $data['_wpnonce'] );
			unset( $data['wpe-import'] );
			unset( $data['wpe-export'] );
			update_option( $this->instance_id, $data );
		
			//Update cached data with our new values
			$this->instances[$this->instance_id]['user_settings'] = $data;
		}

	}

	
	/**
	 * Returns all default values for the current instance
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function get_defaults( $instance_id ) {

		//Pull default data
		if( isset ( $this->instances[$instance_id]['defaults'] )){			
			return $this->instances[$instance_id]['defaults'];			
		}
		
		return array();		
	}
	
	/**
	 * Returns default values for the current instance
	 *
	 * @since 0.1.1
	 * @access public
	 */
	public function get_options() {
		
		if( $this->instance_id === false )
			return array();
		
		//Maybe pull data from the db?
		if (! isset( $this->instances[$this->instance_id]['user_settings'] ) ) {
			$this->instances[$this->instance_id]['user_settings'] = get_option( $this->instance_id, false );			
		}
		
		//data not yet saved to the database
		if (! $this->instances[$this->instance_id]['user_settings'] ) {
			return $this->get_defaults( $this->instance_id );
			
		}
		return $this->instances[$this->instance_id]['user_settings'];
		
	}
	
		
	/**
	 * gets a user defined option
	 */
	public function get_option( $option = false ) {
		
		if( $option === false OR $this->instance_id === false )
			return null;
		
		//Maybe pull data from the db?
		if (! isset( $this->instances[$this->instance_id]['user_settings'] ) ) {
			$this->instances[$this->instance_id]['user_settings'] = get_option( $this->instance_id, false );			
		}
		
		$user_settings = $this->instances[$this->instance_id]['user_settings'];
		
		//If data saved, return it
		if ( $user_settings && isset( $user_settings[$option] ) ) {
			return $user_settings[$option];
		}
		
		//If data not saved, maybe return default
		$defaults = $this->get_defaults( $this->instance_id );
		if (! $user_settings && isset( $defaults[$option] ) ) {
			return $defaults[$option];
			
		}
		
		//Nothing to return
		return null;
	}
	
	/** Set special arguments for the instance
	 * @since  0.1.0
	 */
	public function set_instance_args( $args ) {
		
		if ( $this->instance_id === false OR !is_array( $args ) )
			return;
		
		// Used to conditionally load data
		if ( isset( $args['hook_suffix'] ) &&  $args['hook_suffix'] ) {
			
			$this->hook_suffixes[ $args['hook_suffix'] ] = array();
			
			if ( isset( $args['element_types'] ) && is_array( $args['element_types'] ) ) {				
				$this->hook_suffixes[ $args['hook_suffix'] ] = $args['element_types'];				
			}			 
			
		}		
	}
	
	
	/**
	 * Adds stylesheets to the queue
	 * @since  0.1.0
	 */
	public function enqeue_scripts( $hook_suffix ) {
		
		//Only enque styles on the pages that we render
		if (! isset( $this->hook_suffixes[ $hook_suffix ] ) )
			return;
	
		//Main css file
		wp_enqueue_style( 'elementa', $this->base_url . 'assets/css/elementa.css');
				
		//Finally enque additional styles needed by the current hook_suffix	
		foreach ( $this->hook_suffixes[ $hook_suffix ] as $element ) {
			if ( isset ( $this->elements[$element]['enque'] ) )
				call_user_func( $this->elements[$element]['enque'] );
		}
		
		//Main js is enqueued last since it depends on the above scripts
		wp_enqueue_script( 'elementa', $this->base_url . 'assets/js/elementa.js', array( 'jquery', 'underscore'), '0.1.1', true );
		
		$elementa_translate = array(
			'emptyData' => __( 'Please provide the import data.', 'elementa' ),
			'emptyJson' => __( 'You provided an empty object so nothing was imported.', 'elementa' ),
			'badFormat' => __( 'The data you provided is not it the right format. Sorry.', 'elementa' ),
			'importing' => __( 'Importing data...', 'elementa' ),
			'finalising' => __( 'Almost done.', 'elementa' ),
			'finished' => __( 'Done. Please wait for the page to reload.', 'elementa' ),
		);
		
		wp_localize_script( 'elementa', 'elementa_translate', $elementa_translate );
	}
	
	
	/**
	 * Plucks a given property from all instances
	 *
	 * @since 0.1.0
	 * @access public
	 */
	public function element_pluck( $property ) {
		
		$return = array();
		if( $this->instance_id !== false && isset( $this->instances[$this->instance_id]['elements'] )) {			
			
			foreach( $this->instances[$this->instance_id]['elements'] as $element ) {				
				if ( isset( $element['args'][$property] ) && !empty( $element['args'][$property] ) )
					$return[] = $element['args'][$property];				
			}
						
		}
		return $return;			
	}

}

//Goodbye World!!!!!!!
