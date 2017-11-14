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
    if(isset($mcl_options['active'])) $enabled = $mcl_options['active']; else $enabled = 0;
    if($enabled == '1') {
        // it's enabled so put footer stuff here
        function mcl_head() {
            $mcl_options = get_option('mcl');
            if(isset($mcl_options['classic'])) $classic = $mcl_options['classic']; else $classic = 0;
            $credits 	 = "\n<!-- Call Now Button ".mcl_VERSION." by Jerry Rietveld (callnowbutton.com) -->\n";
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
                $credits = "\n<!-- Call Now Button ".mcl_VERSION." by Jerry Rietveld (callnowbutton.com) -->\n";
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
                $credits .= "background:url(data:image/svg+xml;base64,".svg(changeColor($mcl_options['color'], 'darker') ).") center/50px 50px no-repeat ".$mcl_options['color'].";";
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
                    $monday = explode('-', $alloptions['monday']);
                    $start_time = strtotime('today ' . trim($monday[0]));
                    $finish_time = strtotime('today ' . trim($monday[1]));
                    break;
                case 2:
                    $tuesday = explode('-', $alloptions['tuesday']);
                    $start_time = strtotime('today ' . trim($tuesday[0]));
                    $finish_time = strtotime('today ' . trim($tuesday[1]));
                    break;
                case 3:
                    $wednesday = explode('-', $alloptions['wednesday']);
                    $start_time = strtotime('today ' . trim($wednesday[0]));
                    $finish_time = strtotime('today ' . trim($wednesday[1]));
                    break;
                case 4:
                    $thursday = explode('-', $alloptions['thursday']);
                    $start_time = strtotime('today ' . trim($thursday[0]));
                    $finish_time = strtotime('today ' . trim($thursday[1]));
                    break;
                case 5:
                    $friday = explode('-', $alloptions['friday']);
                    $start_time = strtotime('today ' . trim($friday[0]));
                    $finish_time = strtotime('today ' . trim($friday[1]));
                    break;
                case 6:
                    $saturday = explode('-', $alloptions['saturday']);
                    $start_time = strtotime('today ' . trim($saturday[0]));
                    $finish_time = strtotime('today ' . trim($saturday[1]));
                    break;
                case 7:
                    $sunday = explode('-', $alloptions['sunday']);
                    $start_time = strtotime('today ' . trim($sunday[0]));
                    $finish_time = strtotime('today ' . trim($sunday[1]));
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

            if($alloptions['tracking'] == '1') {
                $tracking = "onclick=\"_gaq.push(['_trackEvent', 'Contact', 'Call Now Button', 'Phone']);\"";
            } elseif($alloptions['tracking'] == '2') {
                $tracking = "onclick=\"ga('send', 'event', 'Contact', 'Call Now Button', 'Phone');\"";
            } else {
                $tracking = "";
            }

            if (in_array($today_day, $holidays) || $current_time >= $finish_time || $current_time <= $start_time) {
                $callLink = '<a href="#call-me-back" id="callnowbutton" class="call-me-back-button">&nbsp;</a>';
                $callLink .= '<div id="call-me-back" class="call-me-back-popup mfp-hide" >'
                    . do_shortcode("[contact-form-7 id=\"7\" title=\"Call Me Back\"]") . '</div>';
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
            ?>

            <script>

                $(document).ready(function() {
                    $('.call-me-back-button').magnificPopup({
                        type: 'inline',
                        midClick: true
                    });

                });

            </script>
            <style>

                <?php if (!$callmenow): ?>
                @media screen and (max-width: 650px) {

                    #call-me-back {
                        color: #FFF;
                    }

                    #call-me-back form label {
                        color: #FFF;
                    }

                    #call-me-back span.wpcf7-not-valid-tip {
                        color: #CCC
                    }

                    #call-me-back form input {
                        width: 100%;
                    }

                    #call-me-button {
                        background-color: #059AE5;
                    }
                    a#callnowbutton {
                        background-color: #059AE5;
                    }
                }
                <?php endif; ?>
            </style>
        <?php
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
        wp_enqueue_script('jquerymagnificpopupcss');

    }
    add_action('init', 'mcl_get_scripts');
}