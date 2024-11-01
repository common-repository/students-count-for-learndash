<?php

/*
* NUMBER OF STUDENTS ENROLLED
*/
function trsc_get_students_number( $course_id, $hours = 0, $force_refresh = false ) {

	if(!$hours && !$force_refresh) {
		$hours = (!TRSC_COUNT_UPDATE) ? 12 : (int)TRSC_COUNT_UPDATE;
		$force_refresh = empty(TRSC_COUNT_UPDATE);
	} //Not for shortcode. Shortcode will be explicit about all args

	$transient_expire_time = (int)$hours * HOUR_IN_SECONDS;
	
	$course_enrolled_students_number = get_transient( 'trsc_get_students_number_' . $course_id );
	if ( true === $force_refresh || false === $course_enrolled_students_number ) {
	  $members_arr = learndash_get_users_for_course( $course_id, [], false );
	  if ( ( $members_arr instanceof \WP_User_Query ) && ( property_exists( $members_arr, 'total_users' ) ) && ( ! empty( $members_arr->total_users ) ) ) {
		$course_enrolled_students_number = $members_arr->total_users;
	  } else {
		$course_enrolled_students_number = 0;
	  }
	  set_transient( 'trsc_get_students_number_' . $course_id, $course_enrolled_students_number, $transient_expire_time );
	}
	return (int) $course_enrolled_students_number;		
}

/*
* TEXT
*/
function trsc_get_enrolled_students_text($number) {
	if(!is_numeric($number)) {
		return false;
	}

	$final_array = [];
	$final_array['class'] = '';
	$final_array['text'] = '';
	$final_array['output'] = '';

	switch ((int)$number) {
		case 0:
			$text = (TRSC_ZERO_TEXT) ? TRSC_ZERO_TEXT : "No students enrolled yet";
            $to_hide = TRSC_ZERO_HIDE;
			$to_style = TRSC_ZERO_STYLING;
			break;
		case 1:
			$text = (TRSC_SINGULAR_TEXT) ? TRSC_SINGULAR_TEXT : "student enrolled";
            $to_hide = TRSC_SINGULAR_HIDE;
			$to_style = TRSC_SINGULAR_STYLING;
			break;
		default:
			$text = (TRSC_PLURAL_TEXT) ? TRSC_PLURAL_TEXT : "students enrolled";
            $to_hide = TRSC_PLURAL_HIDE;
			$to_style = TRSC_PLURAL_STYLING;
			break;
	}
	
	if($to_hide) {
		return $final_array;
	}

	$final_array['text'] = $text;
	$css_class = ($to_style) ? "ld-course-students-count" : "";
	$final_array['class'] = $css_class;
	$output = ($number > 0) ? "$number " : "";
	$output .= $text;
	$final_array['output'] = $output;
	
	return $final_array;
}

/*
* SINGLE COURSE PAGE
*/
function trsc_students_enrolled_single( $course_id, $user_id ) {

	if(is_admin()) {
		return;
	}

    if(!TRSC_SINGLE_SHOW) {
		return;
	}

    if(TRSC_WHO_CAN_SEE && TRSC_WHO_CAN_SEE == 'trsc_who_logged' && !is_user_logged_in()) {
        return;
    }

    if(TRSC_WHO_CAN_SEE && TRSC_WHO_CAN_SEE == 'trsc_who_visitors' && is_user_logged_in()) {
        return;
    }

	$number = trsc_get_students_number($course_id);
	$array_text = trsc_get_enrolled_students_text($number);
	if(!$array_text || empty($array_text['text'])) {
		return;
	}

	$output = "<span class='" . $array_text['class'] . " trsc-students-count-single'>";
	$output .= $array_text['output'];
	$output .= "</span>";
	
	echo $output;
}

/*
* GRID PAGE
*/
function trsc_students_enrolled_grid($item_html, $post, $shortcode_atts, $user_id) {

	if(!TRSC_GRID_SHOW) {
		return $item_html;
	}

    if(TRSC_WHO_CAN_SEE && TRSC_WHO_CAN_SEE == 'trsc_who_logged' && !is_user_logged_in()) {
        return $item_html;
    }

    if(TRSC_WHO_CAN_SEE && TRSC_WHO_CAN_SEE == 'trsc_who_visitors' && is_user_logged_in()) {
        return $item_html;
    }

	$course_id = $post->ID;
    $number = trsc_get_students_number($course_id);
	$array_text = trsc_get_enrolled_students_text($number);
	if(!$array_text || empty($array_text['text'])) {
		return $item_html;
	}
	$output = $array_text['output'];
	
	$classes_to_find = [
		'entry-content',
		'course-progress-wrap',
		'ld_course_grid_button',
		'learndash-widget'
	];

	$item_html = mb_convert_encoding($item_html, 'HTML-ENTITIES', "UTF-8");
	@$dom = new DOMDocument();
	@$dom->loadHTML($item_html);
	if(!$dom) {
		return $item_html;
	}	
	$xpath = new DomXPath($dom);
	if(!$xpath) {
		return $item_html;
	}
	foreach ($classes_to_find as $cl) {
		$nodeList = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $cl ')]");
		if($nodeList && $nodeList->length) { 
			break;
		}
	} 
	if(!$nodeList || !$nodeList->length) {
		return $item_html;
	}
	
	$target_element = $nodeList->item(0);
	$span = $dom->createElement('span', $output);
	if(!$span) {
		return $item_html;
	}
	$span->setAttribute('class', $array_text['class'] . ' trsc-students-count-grid');
	$parent = $target_element->parentNode;
	if(!$parent) {
		return $item_html;
	}
	$parent->insertBefore($span, $target_element);
	
	return $dom->saveHTML();
}

/*
* SHORTCODE
*/
function trsc_shortcode( $atts, $content = null ) {
  
	$course_id = get_the_ID();
	$who_possible_values = TRSC_WHO_POSSIBLE_VALUES;
	$update_possible_values = TRSC_UPDATE_POSSIBLE_VALUES;

	// Attributes
	$a = shortcode_atts( 
			array(
				//Defining defaults
				'course_id' => $course_id,
				'class' => '',
				'who' => 'all', // or $who_possible_values separated by commas
				'number_only' => 'false',
				'text_singular' => 'student enrolled',
				'text_plural' => 'students enrolled',
				'text_zero' => 'No student enrolled yet',
				'hide_singular' => 'false',
				'hide_plural' => 'false',
				'hide_zero' => 'false',
				'style_singular' => 'false',
				'style_plural' => 'false',
				'style_zero' => 'false',
				'update_in_hours' => '12'
			), 
			$atts ,
			'shortcodeTRSC'
		);

	$course_id = $a['course_id'];
	if(!$course_id || !is_numeric($course_id)) {
		return;
	}
	$class = esc_attr($a['class']);
	$who_attribute = $a['who'];
	$who = explode(',',$who_attribute); //so that, in the future, we can aggregate two or more conditions here (separated by commas)
	if( !count( array_intersect( $who_possible_values, $who ) ) ) {
		return; //no intersect = not even 1 valid value...
	}
	//Check if the user can see and returning early
	if(in_array('visitors', $who) and is_user_logged_in()) {
		return;
	}
	if(in_array('logged', $who) and !is_user_logged_in()) {
		return;
	}
	$number_only = 'true' === $a['number_only']; //boolean
	$text_singular = esc_html($a['text_singular']);
	$text_plural = esc_html($a['text_plural']);
	$text_zero = esc_html($a['text_zero']);
	$hide_singular = 'true' === $a['hide_singular']; //boolean
	$hide_plural = 'true' === $a['hide_plural']; //boolean
	$hide_zero = 'true' === $a['hide_zero']; //boolean
	$style_singular = 'true' === $a['style_singular']; //boolean
	$style_plural = 'true' === $a['style_plural']; //boolean
	$style_zero = 'true' === $a['style_zero']; //boolean

	if(!is_numeric($a['update_in_hours']) || !in_array($a['update_in_hours'], $update_possible_values) || empty($a['update_in_hours']) ) {
		$update_in_hours = 12;
	} else {
		$update_in_hours = (int)$a['update_in_hours'];
	}

	$force_refresh = empty($a['update_in_hours']);

	//Get number of course enrolled students
	$number = trsc_get_students_number( $course_id, $update_in_hours, $force_refresh );

	if(!is_int($number)) {
		return;
	}
	switch ($number) {
		case 0:
            $to_hide = $hide_zero;
			$to_style = $style_zero;
			$text = $text_zero;
			break;
		case 1:
            $to_hide = $hide_singular;
			$to_style = $style_singular;
			$text = $text_singular;
			break;
		default:
            $to_hide = $hide_plural;
			$to_style = $style_plural;
			$text = $text_plural;
			break;
	}
	if($to_hide) {
		return;
	}

	$css_class = ($to_style) ? "trsc-course-students-count " : "";
	if($class) {
		$css_class .= $class;
	}
	
	//Building the output
	$output = ($number !== 0) ? "$number $text" : $text;
	if($number_only) {
		$output = $number;
	}

	return "<span class='$css_class'>$output</span>";
  }
  add_shortcode( 'ld_students_count', 'trsc_shortcode' ); 

