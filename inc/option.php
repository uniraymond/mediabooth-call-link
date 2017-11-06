<?php

/**
 * Created by PhpStorm.
 * User: raymond
 * Date: 6/11/17
 * Time: 12:04 PM
 */

/**
 * @package MediaboothCallLinkPlugin
 *
 */

namespace Inc;

class Option
{
    public static function mcl_get_options()
    { // Checking and setting the default options
        if (!get_option('mcl')) {
            $default_options = array(
                'active',
                'number' => '',
                'color' => '#009900',
                'appearance' => 'right',
                'show' => '',
                'version' => MCL_VERSION
            );

            // add option to 'mcl'
            add_option('mcl', $default_options);
        }

        $mcl_options = get_option('mcl');
        return $mcl_options;
    }

    public static function set_basic_options() {
        if(!array_key_exists('version', get_option('mcl'))) {
            $mcl_options = get_option('mcl');
            $mcl_options['active'] = isset($mcl_options['active']) ? 1 : 0;
            $default_options = array(
                'active' => $mcl_options['active'],
                'number' => $mcl_options['number'],
                'color' => $mcl_options['color'],
                'appearance' => $mcl_options['appearance'],
                'show' => $mcl_options['show'],
                'version' => MCL_VERSION
            );
            update_option('mcl',$default_options);
            return true;  // plugin was updated
        } else {
            return false; // no update
        }
    }
}