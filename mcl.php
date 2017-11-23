<?php
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
        // it's enabled so put footer stuff here
        function mcl_top() {
	        $credits 	 = "";
	        $button_extra = "";

            $mcl_options = get_option('mcl');

            $button_style = "width:65px; height:65px; border-radius:80px; border:2px solid #fff; bottom:15px;";

            if($mcl_options['app_position'] == 'full') {
                $button_app_position = "width:100%;left:0;bottom:0;height:60px;border-top:1px solid ".modifyColor($mcl_options['color'], 'lighter')."; border-bottom:1px solid ".modifyColor($mcl_options['color'], 'darker').";";
                $button_extra = "body {padding-bottom:60px;}";
            }
            elseif($mcl_options['app_position'] == 'left'  ) { $button_app_position = $button_style . "left:20px;"; }
            elseif($mcl_options['app_position'] == 'middle') { $button_app_position = $button_style . "left:50%; margin-left:-33px;"; }
            else 									   { $button_app_position = $button_style . "right:20px;"; }

            $credits = $credits ."<style>";
            $credits .= "#call_link {display:none;} @media screen and (max-width:650px){#call_link {display:block; position:fixed; text-decoration:none; z-index:9999;";
            $credits .= $button_app_position;
            $credits .= "background:url(data:image/svg+xml;base64,".loadsvg(modifyColor($mcl_options['color'], 'darker') ).") center/50px 50px no-repeat ".$mcl_options['color'].";";
            $credits .= "}" . $button_extra . "}";
            $credits .= "</style>\n";

            echo $credits;
        }

        add_action('wp_head', 'mcl_top');

        function mcl_bottom() {
            $alloptions = get_option('mcl');
            date_default_timezone_set('Australia/Brisbane');
            $callmenow = true;

            $start_time = $finish_time = 0;

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

            $holidays = array_map('trim', explode(',', $alloptions['holiday']));
            $today_day = date('d/m');
            $current_time = strtotime('now');

            if(isset($alloptions['display']) && $alloptions['display'] != "") {
                $display = explode(',', str_replace(' ', '' ,$alloptions['display']));
                $limited = 1;
            } else {
                $limited = 0;
            }

            if (in_array($today_day, $holidays) || $current_time >= $finish_time || $current_time <= $start_time) {
                $callLink = '';
            ?>

            <a href="#call-me-back" id="call_link" class="call-me-back-button"><img width="40px" class="call_link_image" src= "<?php echo MCL_PLUGIN_URL; ?>/email.png" alt="" /></span></a>
            <div id="call-me-back" class="call-me-back-popup mfp-hide" >
                <form action="/#wpcf7-f47-o1" method="post" class="wpcf7-form" novalidate="novalidate">
                    <div style="display: none;">
                    <input type="hidden" name="_wpcf7" value="47">
                    <input type="hidden" name="_wpcf7_version" value="4.9">
                    <input type="hidden" name="_wpcf7_locale" value="en_US">
                    <input type="hidden" name="_wpcf7_unit_tag" value="wpcf7-f47-o1">
                    <input type="hidden" name="_wpcf7_container_post" value="0">
                    </div>
                    <h3>It is out of our business hour, please leave your contact details and our friendly staff will contact you ASAP.</h3>
                    <p><label> Your Name (required)<br>
                        <span class="wpcf7-form-control-wrap your-name"><input type="text" name="your-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Your Name:"></span> </label></p>
                    <p><label> Your Phone Number (required)<br>
                        <span class="wpcf7-form-control-wrap your-phonenumber"><input type="tel" name="your-phonenumber" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-required wpcf7-validates-as-tel callback_number" id="callback_number" aria-required="true" aria-invalid="false" placeholder="Your Phone Number:"></span> </label></p>
                    <p><label> Comments<br>
                        <span class="wpcf7-form-control-wrap your-comments"><input type="text" name="your-comments" value="" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false" placeholder="Your Comments:"></span> </label></p>
                    <p><input type="submit" value="Send" class="wpcf7-form-control wpcf7-submit call-me-button" id="call-me-button"><span class="ajax-loader"></span></p>
                    <div class="wpcf7-response-output wpcf7-display-none"></div>
                </form>
            </div>
            <?php
                $callmenow = false;
            } else {
                $callLink = '<a href="tel:'.$alloptions['number'].'" id="call_link"><img width="40px" class="call_link_image" src= "<?php echo MCL_PLUGIN_URL; ?>/phone.png" alt="" /></a>';
            }

            if($limited) {
                if(is_single($display) || is_page($display)) {
                    echo $callLink;
                }
            } else {
                echo $callLink;
            }

            wp_deregister_script('mcl-js');
            wp_register_script('mcl-js', MCL_PLUGIN_URL . '/mcl.js', false, '0.0.1');
            wp_enqueue_script('mcl-js');

	        wp_deregister_style('mcl-css');
	        wp_register_style('mcl-css', MCL_PLUGIN_URL . '/mcl.css', false, '0.0.1');
	        wp_enqueue_style('mcl-css');

            if (!$callmenow) {
                wp_deregister_style('link-style');
                wp_register_style('link-style', MCL_PLUGIN_URL . '/link_style.css', false, '0.0.1');
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


	function loadsvg($button_color) {
//		$phone_icon = '<path d="M256,7.9C117.4,7.9,5,119.8,5,257.9c0,52.3,16.1,100.8,43.6,140.9l-33,98.5l98.9-32.8
//	c40.3,27.4,89,43.5,141.4,43.5c138.6,0,251-111.9,251-250C507,119.8,394.6,7.9,256,7.9z" fill="'.$button_color.'"/><path d="M375.7,332.5c-19.3-19.3-40-35.6-40-35.6l-30.2,30.2L184.9,206.4l30.2-30.2c0,0-16.3-20.7-35.6-40
//	c-19.3-19.3-40-35.6-40-35.6s-36.1,33.2-38.6,59.6c-3.3,35.3,34.1,99.5,92.8,158.1s122.8,96,158.1,92.8
//	c26.4-2.4,59.6-38.6,59.6-38.6S395.1,351.9,375.7,332.5z" fill="#fff"/>';
//		$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60">' . $phone_icon . '</svg>';
//		return base64_encode($svg_icon);

		$phone_icon = '<path d="M256,7.9C117.4,7.9,5,119.8,5,257.9c0,52.3,16.1,100.8,43.6,140.9l-33,98.5l98.9-32.8
	c40.3,27.4,89,43.5,141.4,43.5c138.6,0,251-111.9,251-250C507,119.8,394.6,7.9,256,7.9z" fill="'.$button_color.'"/><path d="M7.104 13.032l6.504-6.505c0.896-0.895 2.334-0.678 3.1 0.35l5.563 7.8 c0.738 1 0.5 2.531-0.36 3.426l-4.74 4.742c2.361 3.3 5.3 6.9 9.1 10.699c3.842 3.8 7.4 6.7 10.7 9.1 l4.74-4.742c0.897-0.895 2.471-1.026 3.498-0.289l7.646 5.455c1.025 0.7 1.3 2.2 0.4 3.105l-6.504 6.5 c0 0-11.262 0.988-25.925-13.674C6.117 24.3 7.1 13 7.1 13" fill="#fff"/>';
		$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60">' . $phone_icon . '</svg>';
		return base64_encode($svg_icon);
	}
}