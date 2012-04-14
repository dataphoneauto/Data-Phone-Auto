<?php

require_once dirname(__FILE__) . '/ActionController.php';

abstract class FrontController
{
    public static $isDebug = true;
    public static $dirname = '/';

    public static function dispatch()
    {
        try {
            session_start();
            $request  = new Request();
            $response = new HttpResponse();
            ActionController::process($request, $response)->printOut();
        } catch (Exception $e) {
            if (!self::$isDebug) {
                ActionController::processException($request, $response, $e)
                                        ->printOut();
            } else {
                echo $e->getMessage() . '<br/>' . $e->getTraceAsString();
            }
        }
    }
}
