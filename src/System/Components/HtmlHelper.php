<?php
/**
 * HTML Helper class
 *
 * @author James Byrne <jamesbwebdev@gmail.com>
 */

namespace Jay\System\Components;

use Jay\System\HtmlHelper as HtmlHelperInterface;

class HtmlHelper implements HtmlHelperInterface
{
    public function asset($path)
    {
        if (!BASE) {
            echo "/assets/$path";
            return;
        }

        echo '/'.BASE."/assets/$path";
        return;
    }

    public function link($path)
    {
        if (stripos($path, 'http')) {
            echo $path;
            return;
        }

        if (!BASE) {
            echo "/$path";
            return;
        }

        echo '/'.BASE."/$path";
        return;       
    }
}