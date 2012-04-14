<?php
class View
{
    public function render($file, $assigns = array())
    {
        extract($assigns);
        ob_start();
        require($file);
        $str = ob_get_contents();
        ob_end_clean();
        return $str;
    }
}
