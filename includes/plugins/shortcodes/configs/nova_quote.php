<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}


$shortcode_params = array(
	array(
		"type" => "dropdown",
		"heading" => __("Design", 'nova'),
		"param_name" => "count_style",
		"value" => array(
			__("Style 01",'nova') => "1",
			__("Style 02",'nova') => "2",
			__("Style 03",'nova') => "3",
			__("Style 04",'nova') => "4",
		)
	),
	array(
		"type" => "datetimepicker",
		"heading" => __("Target Time For Countdown", 'nova'),
		"param_name" => "datetime",
		"description" => __("Date and time format (yyyy/mm/dd hh:mm:ss).", 'nova'),
	),
	array(
		"type" => "dropdown",
		"heading" => __("Countdown Timer Depends on", 'nova'),
		"param_name" => "time_zone",
		"value" => array(
			__("WordPress Defined Timezone",'nova') => "wptz",
			__("User's System Timezone",'nova') => "usrtz",
		),
	),
	array(
		"type" => "checkbox",
		"heading" => __("Select Time Units To Display In Countdown Timer", 'nova'),
		"param_name" => "countdown_opts",
		"value" => array(
			__("Years",'nova') => "syear",
			__("Months",'nova') => "smonth",
			__("Weeks",'nova') => "sweek",
			__("Days",'nova') => "sday",
			__("Hours",'nova') => "shr",
			__("Minutes",'nova') => "smin",
			__("Seconds",'nova') => "ssec",
		),
	),

	Novaworks_Shortcodes_Helper::fieldExtraClass(),
	Novaworks_Shortcodes_Helper::fieldElementID(array(
		'param_name' 	=> 'el_id'
	)),
	array(
		"type" => "textfield",
		"heading" => __("Day (Singular)", 'nova'),
		"param_name" => "string_days",
		"value" => "Day",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Days (Plural)", 'nova'),
		"param_name" => "string_days2",
		"value" => "Days",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Week (Singular)", 'nova'),
		"param_name" => "string_weeks",
		"value" => "Week",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Weeks (Plural)", 'nova'),
		"param_name" => "string_weeks2",
		"value" => "Weeks",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Month (Singular)", 'nova'),
		"param_name" => "string_months",
		"value" => "Month",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Months (Plural)", 'nova'),
		"param_name" => "string_months2",
		"value" => "Months",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Year (Singular)", 'nova'),
		"param_name" => "string_years",
		"value" => "Year",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Years (Plural)", 'nova'),
		"param_name" => "string_years2",
		"value" => "Years",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Hour (Singular)", 'nova'),
		"param_name" => "string_hours",
		"value" => "Hrs",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Hours (Plural)", 'nova'),
		"param_name" => "string_hours2",
		"value" => "Hrs",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Minute (Singular)", 'nova'),
		"param_name" => "string_minutes",
		"value" => "Mins",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Minutes (Plural)", 'nova'),
		"param_name" => "string_minutes2",
		"value" => "Mins",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Second (Singular)", 'nova'),
		"param_name" => "string_seconds",
		"value" => "Secs",
		'group' => __( 'Strings Translation', 'nova' ),
	),
	array(
		"type" => "textfield",
		"heading" => __("Seconds (Plural)", 'nova'),
		"param_name" => "string_seconds2",
		"value" => "Secs",
		'group' => __( 'Strings Translation', 'nova' ),
	),
);

return apply_filters(
	'Novaworks/shortcodes/configs',
	array(
		'name'			=> __('Count Down', 'nova'),
		'base'			=> 'la_countdown',
		'icon'          => 'nova-wpb-icon la_countdown',
		'category'  	=> __('La Studio', 'nova'),
		'description' 	=> __('Countdown Timer','nova'),
		'params' 		=> $shortcode_params
	),
    'la_countdown'
);