<?php
$callmenow = '';
if(get_option('mcl') && !is_admin()) {

    // Color functions to calculate borders
    function modifyColor($color, $direction) {
        if(!preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $color, $elements));

        if(!isset($direction) || $direction == "lighter") {
            $modify = 45;
        } else {
            $modify = -50;
        }

        for($index = 1; $index <= 3; $index++) {
            $elements[$index] = hexdec($elements[$index]);
            $elements[$index] = round($elements[$index] + $modify);

            if($elements[$index] > 255) {
                $elements[$index] = 255;
            } elseif(
                $elements[$index] < 0) { $elements[$index] = 0;
            }
            $elements[$index] = dechex($elements[$index]);
        }
        $result = '#' . str_pad($elements[1],2,"0",STR_PAD_LEFT) . str_pad($elements[2],2,"0",STR_PAD_LEFT) . str_pad($elements[3],2,"0",STR_PAD_LEFT);
        return $result;
    }

    $mcl_options = get_option('mcl');

    if(isset($mcl_options['activity'])) {
        $enabled = $mcl_options['activity'];
    } else {
        $enabled = 0;
    }

    if($enabled == '1') {

        // checking holiday, after hours
	    /**
	     * @return array (call_link: phone or email button,
         * call_me_now: return phone, email or noaction,
         * display_button: weather will diplay the button )
	     */
        function check_email_phone() {

	        $alloptions = get_option('mcl');
	        date_default_timezone_set('Australia/Brisbane');
	        $callmenow = 'phone';
	        $display_button = false;

	        $start_time = $finish_time = 0;

	        //get start and finish time
	        switch (date('N')) {
		        case 1:
			        $monday = '';
			        if ($alloptions['monday']) {
				        $monday = explode('-', $alloptions['monday']);
				        $start_time = strtotime('today ' . trim($monday[0]));
				        $finish_time = strtotime('today ' . trim($monday[1]));
			        }
			        break;
		        case 2:
			        $tuesday = '';
			        if ($alloptions['tuesday']) {
				        $tuesday = explode('-', $alloptions['tuesday']);
				        $start_time = strtotime('today ' . trim($tuesday[0]));
				        $finish_time = strtotime('today ' . trim($tuesday[1]));
			        }
			        break;
		        case 3:
			        $wednesday = '';
			        if ($alloptions['wednesday']) {
				        $wednesday = explode('-', $alloptions['wednesday']);
				        $start_time = strtotime('today ' . trim($wednesday[0]));
				        $finish_time = strtotime('today ' . trim($wednesday[1]));
			        }
			        break;
		        case 4:
			        $thursday = '';
			        if ($alloptions['thursday']) {
				        $thursday = explode('-', $alloptions['thursday']);
				        $start_time = strtotime('today ' . trim($thursday[0]));
				        $finish_time = strtotime('today ' . trim($thursday[1]));
			        }
			        break;
		        case 5:
			        $friday = '';
			        if ($alloptions['friday']) {
				        $friday = explode('-', $alloptions['friday']);
				        $start_time = strtotime('today ' . trim($friday[0]));
				        $finish_time = strtotime('today ' . trim($friday[1]));
			        }

			        break;
		        case 6:
			        $saturday = '';
			        if ($alloptions['saturday']) {
				        $saturday = explode('-', $alloptions['saturday']);
				        $start_time = strtotime('today ' . trim($saturday[0]));
				        $finish_time = strtotime('today ' . trim($saturday[1]));
			        }
			        break;
		        case 7:
			        $sunday = '';
			        if ($alloptions['sunday']) {
				        $sunday = explode('-', $alloptions['sunday']);
				        $start_time = strtotime('today ' . trim($sunday[0]));
				        $finish_time = strtotime('today ' . trim($sunday[1]));
			        }
			        break;

	        }

	        // get all holidays from setting
	        $holidays = array_map('trim', explode(',', $alloptions['holiday']));
	        $today_day = date('d/m');
	        $current_time = strtotime('now');

	        // check there is limit appearance on setting page
	        if(isset($alloptions['display']) && $alloptions['display'] != "") {
		        $display = explode(',', str_replace(' ', '' ,$alloptions['display']));
		        $limited = 1;
	        } else {
		        $limited = 0;
	        }

	        $call_link = '';
	        // checking the holiday or start - finish working time
	        if (in_array($today_day, $holidays) || $current_time >= $finish_time || $current_time <= $start_time) {

		        $contact_form = isset($alloptions['emailafterhour']) ? $alloptions['emailafterhour'] : '';
		        if ($contact_form) {
			        $call_link = '<a href="#call-me-back" id="call_link" class="call-me-back-button"></a>';
			        $call_link .= '<div id="call-me-back" class="call-me-back-popup mfp-hide" >';
			        $call_link .=  do_shortcode($contact_form);
			        $call_link .= '</div>';
		        }

		        $callmenow = 'email';
	        } else {
		        $call_link = '<a href="tel:'.$alloptions['number'].'" id="call_link"></a>';
	        }

	        if($limited) {
		        if(is_single($display) || is_page($display)) {
			        $display_button = true;
		        }
	        } else {
		        $display_button = true;
	        }

	        $items = ['call_link' => $call_link, 'call_me_now' => $callmenow, 'display_button' => $display_button];
	        return $items;
        }

        // it's enabled so put footer stuff here
        function mcl_top() {
	        $credits 	 = "";
	        $button_extra = "";

            $mcl_options = get_option('mcl');
            $get_email_phone = check_email_phone();

            $button_style = "width:65px; height:65px; border-radius:80px; border:2px solid #fff; bottom:15px;";
	        $border_top_color = modifyColor($mcl_options['color'], 'lighter');
	        $border_bottom_color = modifyColor($mcl_options['color'], 'darker');

            $app_position = '';
	        if (isset($mcl_options['app_position'])) {
                $app_position = $mcl_options['app_position'];
            }
            // display button position
            switch ($app_position) {
                case 'full':
	                $button_app_position = "width:100%;left:0;bottom:0;height:60px;border-top:1px solid " . $border_top_color . "; border-bottom:1px solid " . $border_bottom_color . ";";
	                $button_extra = "body {padding-bottom:60px;}";
                    break;
                case 'left':
	                $button_app_position = $button_style . "left:20px;";
	                break;
                case 'middle':
	                $button_app_position = $button_style . "left:50%; margin-left:-33px;";
                    break;
                case 'right':
                case 'default':
                    $button_app_position = $button_style . "right:20px;";
                    break;
            }

            $credits = $credits ."<style>";
            $credits .= "#call_link {display:none;} @media screen and (max-width:650px){#call_link {display:block; position:fixed; text-decoration:none; z-index:9999;";
            $credits .= $button_app_position;

	        $style1 = $style2 = '';
	        // display email or phone icon
            if ($get_email_phone['call_me_now'] == 'email' && $get_email_phone['display_button']) {
	            $style1 = "";
	            $style2 = "M485.211,363.906c0,10.637-2.992,20.498-7.785,29.174L324.225,221.67l151.54-132.584 c5.895,9.355,9.446,20.344,9.446,32.219V363.906z M242.606,252.793l210.863-184.5c-8.653-4.737-18.397-7.642-28.908-7.642H60.651 c-10.524,0-20.271,2.905-28.889,7.642L242.606,252.793z M301.393,241.631l-48.809,42.734c-2.855,2.487-6.41,3.729-9.978,3.729 c-3.57,0-7.125-1.242-9.98-3.729l-48.82-42.736L28.667,415.23c9.299,5.834,20.197,9.329,31.983,9.329h363.911 c11.784,0,22.687-3.495,31.983-9.329L301.393,241.631z M9.448,89.085C3.554,98.44,0,109.429,0,121.305v242.602 c0,10.637,2.978,20.498,7.789,29.174l153.183-171.44L9.448,89.085z";
                $view_box = "0 0 485.211 485.211";
	            $credits .= "background:url(data:image/svg+xml;base64,".loadsvg($style1, $style2, $view_box).") center/50px 50px no-repeat ".$mcl_options['color'].";";
            } elseif ($get_email_phone['call_me_now'] == 'phone' && $get_email_phone['display_button']) {
	            $style1 = "M375.7,332.5c-19.3-19.3-40-35.6-40-35.6l-30.2,30.2L184.9,206.4l30.2-30.2c0,0-16.3-20.7-35.6-40 c-19.3-19.3-40-35.6-40-35.6s-36.1,33.2-38.6,59.6c-3.3,35.3,34.1,99.5,92.8,158.1s122.8,96,158.1,92.8 c26.4-2.4,59.6-38.6,59.6-38.6S395.1,351.9,375.7,332.5z";
	            $style2 = "";
	            $view_box = "0 0 512 512";
	            $credits .= "background:url(data:image/svg+xml;base64,".loadsvg($style2, $style1, $view_box).") center/50px 50px no-repeat ".$mcl_options['color'].";";
            }

            $credits .= "}" . $button_extra . "}";
            $credits .= "</style>\n";

            echo $credits;
        }

        add_action('wp_head', 'mcl_top');

	    /**
	     * bottom part.
	     */
        function mcl_bottom() {

	        $get_email_phone = check_email_phone();

	        if ($get_email_phone['call_me_now'] != 'noaction' && $get_email_phone['display_button']) {
                echo $get_email_phone['call_link'];
            }

            wp_deregister_script('mcl-js');
            wp_register_script('mcl-js', MCL_PLUGIN_URL . '/assets/mcl.js', false, '0.0.1');
            wp_enqueue_script('mcl-js');

	        wp_deregister_style('mcl-css');
	        wp_register_style('mcl-css', MCL_PLUGIN_URL . '/assets/mcl.css', false, '0.0.1');
	        wp_enqueue_style('mcl-css');

            if ($get_email_phone['call_me_now'] == 'email') {
                wp_deregister_style('link-style');
                wp_register_style('link-style', MCL_PLUGIN_URL . '/assets/link_style.css', false, '0.0.1');
                wp_enqueue_style('link-style');
            }
        }
        add_action('wp_footer', 'mcl_bottom');
    }


    function mcl_get_scripts() {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js', false, '1.8.1');
        wp_enqueue_script('jquery');

        wp_deregister_script('jquerymagnificpopupjs');
        wp_register_script('jquerymagnificpopupjs', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', false, '1.1.0');
        wp_enqueue_script('jquerymagnificpopupjs');

        wp_deregister_style('jquerymagnificpopupcss');
        wp_register_style('jquerymagnificpopupcss', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', false, '1.1.0');
        wp_enqueue_style('jquerymagnificpopupcss');

    }
    add_action('init', 'mcl_get_scripts');

	/**
	 * load a svg icon and setting the background
	 *
	 * @param $button_phone_icon_path_d1
	 * @param $button_phone_icon_path_d2
	 * @param $button_phone_viewbox
	 *
	 * @return string
	 *
	 */
	function loadsvg(
            $button_phone_icon_path_d1,
            $button_phone_icon_path_d2,
            $button_phone_viewbox
        ) {
	    if ($button_phone_icon_path_d2) {
		    $button_phone_icon_path = "<path d='". $button_phone_icon_path_d1 ."' fill=''/><path d='". $button_phone_icon_path_d2 ."' fill='#fff'/>";
        } else {
	        $button_phone_icon_path = "<path d='". $button_phone_icon_path_d1 ."' fill=''/>";
        }

		$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="' . $button_phone_viewbox . '">' . $button_phone_icon_path . '</svg>';

		return base64_encode($svg_icon);
	}
}