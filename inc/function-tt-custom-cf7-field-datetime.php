<?php
/**
 * Functions to add custom field to Contact Form 7
 * Ref: //https://contactform7.com/2015/01/10/adding-a-custom-form-tag/
 * Ref: //https://stackoverflow.com/questions/42792051/how-to-make-custom-form-tag-in-contact-form-7-required
 * Field DateTime
 * 
 * @since 1.0
 * 
 */

namespace Logically_Tech\Time_Tracker\Inc;


/**
 * Create Custom CF7 Form Tag, DateTime
 * 
 */
add_action( 'wpcf7_init', 'Logically_Tech\Time_Tracker\Inc\custom_add_form_tag_datetime' );


/**
 * Initialize DateTime as a custom CF7 form tag
 * 
 */
function custom_add_form_tag_datetime()  {
  wpcf7_add_form_tag( 'datetime', 'Logically_Tech\Time_Tracker\Inc\custom_datetime_form_tag_handler', array('name-attr'=>true));
}


/**
 * Define callback for time form tag
 * 
 */
function custom_datetime_form_tag_handler( $tag ) {
  
  $tag = new \WPCF7_FormTag($tag);
  $class = wpcf7_form_controls_class($tag->type);

  $now = new \DateTime();
  $tz = get_option('timezone_string');
  if ($tz) {
  	$now = $now->setTimezone(new \DateTimeZone($tz));		
  }
  $now = $now->format('n/j/y g:i A');

  $atts = array(
    'type' => 'datetime',
    'name' => $tag->name,
    'class' => $tag->get_class_option($class),
    'id' => $tag->get_id_option(),
    'default' => $tag->get_default_option(),
    'value' => $now,
  );

  $atts = wpcf7_format_atts($atts);
  $html = sprintf( '<input %s></input>', $atts);
  return $html;
}