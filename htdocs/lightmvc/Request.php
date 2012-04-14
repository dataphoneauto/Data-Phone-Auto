<?php
class Request
{
    public function getParam($key)
    {
        return filter_var($this->getTaintedParam($key),
                          FILTER_SANITIZE_STRING,
                          FILTER_FLAG_NO_ENCODE_QUOTES);
    }

    public function getParamOrEmpty($key)
    {
        if ($this->isParamSet($key)) {
            return filter_var($this->getTaintedParam($key),
                              FILTER_SANITIZE_STRING,
                              FILTER_FLAG_NO_ENCODE_QUOTES);
        } else {
            return '';
        }
    }

    public function getTaintedParam($key)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[$key])) {
            return $_POST[$key];
        } else {
            return $_GET[$key];
        }
    }

    public function isParamSet($key)
    {
        return (isset($_POST[$key]) || isset($_GET[$key]));
    }

    public function route($requestUri)
    {
        if (empty($requestUri)) {
            return array();
        }

        $path = parse_url($requestUri, PHP_URL_PATH);
        preg_match('#^(/(?P<module>\w+))(/(?P<action>\w+))?(/?)$#',
                   $path, $matches);
        if (empty($matches['action'])) {
            unset($matches['action']);
        }

        $args = explode('&', parse_url($requestUri, PHP_URL_QUERY));

        return $matches;
    }
}
