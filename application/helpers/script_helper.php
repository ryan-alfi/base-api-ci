<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * script_tag
 *
 * Generates link to a JS file
 *
 * @access    public
 * @param    mixed    javascript srcs or an array
 * @param    string    type
 * @param    boolean    should index_page be added to the js path
 * @return    string
 */
if ( ! function_exists('script_tag'))
{
    function script_tag($src = '', $type = 'text/javascript', $index_page = FALSE)
    {
        $CI =& get_instance();

        $link = '';
        if (is_array($src))
        {
            foreach ($src as $v)
            {
                $link .= script_tag($v,$type,$index_page);
            }

        }
        else
        {
            $link = '<script ';
            if ( strpos($src, '://') !== FALSE)
            {
                $link .= 'src="'.$src.'" ';
            }
            elseif ($index_page === TRUE)
            {
                $link .= 'src="'.$CI->config->site_url($src).'" ';
            }
            else
            {
                $link .= 'src="'.$CI->config->slash_item('base_url').$src.'" ';
            }

            $link .= " type='{$type}'></script>";
        }
        return $link;
    }
}


