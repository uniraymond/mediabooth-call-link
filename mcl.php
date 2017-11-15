<?php
if(get_option('mcl') && !is_admin()) {

    // Color functions to calculate borders
    function changeColor($color, $direction) {
        if(!preg_match('/^#?([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/i', $color, $parts));
        if(!isset($direction) || $direction == "lighter") { $change = 45; } else { $change = -50; }
        for($i = 1; $i <= 3; $i++) {
            $parts[$i] = hexdec($parts[$i]);
            $parts[$i] = round($parts[$i] + $change);
            if($parts[$i] > 255) { $parts[$i] = 255; } elseif($parts[$i] < 0) { $parts[$i] = 0; }
            $parts[$i] = dechex($parts[$i]);
        }
        $output = '#' . str_pad($parts[1],2,"0",STR_PAD_LEFT) . str_pad($parts[2],2,"0",STR_PAD_LEFT) . str_pad($parts[3],2,"0",STR_PAD_LEFT);
        return $output;
    }

    $mcl_options = get_option('mcl');

    if(isset($mcl_options['activity'])) $enabled = $mcl_options['activity']; else $enabled = 0;
    if($enabled == '1') {
        // it's enabled so put footer stuff here
        function mcl_head() {
            $mcl_options = get_option('mcl');
            if(isset($mcl_options['classic'])) $classic = $mcl_options['classic']; else $classic = 0;
            $credits 	 = "";
            $ButtonExtra = "";
            if($classic == 1) {

                // OLD BUTTON DESIGN
                if($mcl_options['appearance'] == 'full' || $mcl_options['appearance'] == 'middle') {
                    $ButtonAppearance = "width:100%;left:0;";
                    $ButtonExtra = "body {padding-bottom:60px;}";
                }
                elseif($mcl_options['appearance'] == 'left') { $ButtonAppearance = "width:100px;left:0;border-bottom-right-radius:40px; border-top-right-radius:40px;"; }
                else { $ButtonAppearance = "width:100px;right:0;border-bottom-left-radius:40px; border-top-left-radius:40px;";	}

                $credits .= "<style>#callnowbutton {display:none;} @media screen and (max-width:650px){#callnowbutton {display:block; ".$ButtonAppearance." height:80px; position:fixed; bottom:-20px; border-top:2px solid ".changeColor($mcl_options['color'],'lighter')."; background:url(data:image/svg+xml;base64,".svg(changeColor($mcl_options['color'], 'darker') ).") center 2px no-repeat ".$mcl_options['color']."; text-decoration:none; box-shadow:0 0 5px #888; z-index:9999;background-size:58px 58px}".$ButtonExtra."}</style>\n";

            } else {

                // NEW BUTTON DESIGN
                $credits = "";
                $ButtonShape = "width:65px; height:65px; border-radius:80px; border:2px solid #fff; bottom:15px;";
                if($mcl_options['appearance'] == 'full') {
                    $ButtonAppearance = "width:100%;left:0;bottom:0;height:60px;border-top:1px solid ".changeColor($mcl_options['color'], 'lighter')."; border-bottom:1px solid ".changeColor($mcl_options['color'], 'darker').";";
                    $ButtonExtra = "body {padding-bottom:60px;}";
                }
                elseif($mcl_options['appearance'] == 'left'  ) { $ButtonAppearance = $ButtonShape . "left:20px;"; }
                elseif($mcl_options['appearance'] == 'middle') { $ButtonAppearance = $ButtonShape . "left:50%; margin-left:-33px;"; }
                else 									   { $ButtonAppearance = $ButtonShape . "right:20px;"; }

                $credits = $credits ."<style>";
                $credits .= "#callnowbutton {display:none;} @media screen and (max-width:650px){#callnowbutton {display:block; position:fixed; text-decoration:none; z-index:9999;";
                $credits .= $ButtonAppearance;
//                $credits .= "background:url(data:image/svg+xml;base64,".svg(changeColor($mcl_options['color'], 'darker') ).") center/50px 50px no-repeat ".$mcl_options['color'].";";
                $credits .= "}" . $ButtonExtra . "}";
                $credits .= "</style>\n";
            }
            echo $credits;
        }
        add_action('wp_head', 'mcl_head');

        function mcl_footer() {
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

            if(isset($alloptions['show']) && $alloptions['show'] != "") {
                $show = explode(',', str_replace(' ', '' ,$alloptions['show']));
                $limited = 1;
            } else {
                $limited = 0;
            }

            if(array_key_exists('tracking', $alloptions) && $alloptions['tracking'] == '1') {
                $tracking = "onclick=\"_gaq.push(['_trackEvent', 'Contact', 'Call Now Button', 'Phone']);\"";
            } elseif(array_key_exists('tracking', $alloptions) && $alloptions['tracking'] == '2') {
                $tracking = "onclick=\"ga('send', 'event', 'Contact', 'Call Now Button', 'Phone');\"";
            } else {
                $tracking = "";
            }

            if (in_array($today_day, $holidays) || $current_time >= $finish_time || $current_time <= $start_time) {
                $callLink = '';
            ?>
                <a href="#call-me-back" id="callnowbutton" class="call-me-back-button">&nbsp;</a>
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
                $callLink = '<a href="tel:'.$alloptions['number'].'" id="callnowbutton" '.$tracking.'>&nbsp;</a>';
            }

            if($limited) {
                if(is_single($show) || is_page($show)) {
                    echo $callLink;
                }
            } else {
                echo $callLink;
            }

            wp_deregister_script('mcl-js');
            wp_register_script('mcl-js', MCL_PLUGIN_URL . '/mcl.js', false, '0.0.1');
            wp_enqueue_script('mcl-js');

            if (!$callmenow) {
                wp_deregister_style('link-style');
                wp_register_style('link-style', MCL_PLUGIN_URL . '/link_style.css', false, '0.0.1');
                wp_enqueue_style('link-style');
            }
        }
        add_action('wp_footer', 'mcl_footer');
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
}