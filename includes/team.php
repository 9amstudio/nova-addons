<?php
/**
 * Register team member support
 */
class Nova_Team_Member {
  private $post_type = 'team_member';
  private $option = 'nova_team';

  /**
   * The single instance of the class
   */
  protected static $instance = null;

  /**
   * Initialize
   */
  static function init() {
    if ( null == self::$instance ) {
      self::$instance = new self();
    }

    return self::$instance;
  }
  public function __construct() {
    $this->register_post_type();
    add_filter( 'rwmb_meta_boxes', array( $this, 'register_meta_boxes' ));
  }
  public function register_post_type() {
    register_post_type( $this->post_type, array(
			'label'               => __( 'Team Member', 'nova' ),
			'supports'            => array('title','editor','thumbnail'),
			'rewrite'             => array( 'slug' => 'team-member' ),
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 8,                    // below Pages
			'menu_icon'           => 'dashicons-groups', // 3.8+ dashicon option
			'capability_type'     => 'page',
			'query_var'           => true,
			'map_meta_cap'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'hierarchical'        => false,
			'has_archive'         => false,
      'can_export'          => true,
			'show_in_nav_menus'   => true
		) );
  }
  function register_meta_boxes( $meta_boxes ) {
  	// Member Informatio
  	$meta_boxes[] = array(
  		'id'       => 'memeber-information',
  		'title'    => esc_html__( 'Member Information', 'nova' ),
  		'pages'    => array( 'team_member' ),
  		'context'  => 'normal',
  		'priority' => 'high',
  		'fields'   => array(
  			array(
  				'name'  => esc_html__( 'Role', 'nova' ),
  				'id'    => '_nova_team_role',
  				'type'  => 'text',
  			),
        array(
          'name'  => esc_html__( 'Phone Number', 'nova' ),
          'id'    => '_nova_team_phone_number',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'Facebook URL', 'nova' ),
          'id'    => '_nova_team_fb_url',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'Twitter URL', 'nova' ),
          'id'    => '_nova_team_tw_url',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'Pinterest URL', 'nova' ),
          'id'    => '_nova_team_pin_url',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'LinkedIn URL', 'nova' ),
          'id'    => '_nova_team_linkedin_url',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'Dribbble URL', 'nova' ),
          'id'    => '_nova_team_dribbble_url',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'Google Plus URL', 'nova' ),
          'id'    => '_nova_team_gplus_url',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'Youtube URL', 'nova' ),
          'id'    => '_nova_team_youtube_url',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'Instagram URL', 'nova' ),
          'id'    => '_nova_team_instagram_url',
          'type'  => 'text',
        ),
        array(
          'name'  => esc_html__( 'Email', 'nova' ),
          'id'    => '_nova_team_email_address',
          'type'  => 'text',
        ),
  		),
  	);
  	return $meta_boxes;
  }
}
