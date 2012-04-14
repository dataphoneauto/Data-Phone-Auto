<?php

require_once dirname(__FILE__) . '/Request.php';
require_once dirname(__FILE__) . '/HttpResponse.php';
require_once dirname(__FILE__) . '/Exceptions.php';
require_once dirname(__FILE__) . '/View.php';

class ActionController
{
    protected $_request;
    protected $_response;
    protected $_redirected;
    protected $_includeTemplate = true;

    protected $_moduleName;
    protected static $_defaultRoute = array(
        'module' => 'index',
        'action' => 'index'
    );

    public static function process(Request $request, HttpResponse $response)
    {
        $route = $request->route($_SERVER['REQUEST_URI']);
        $route = array_merge(self::$_defaultRoute, $route);
        if (!file_exists($path = FrontController::$dirname . '/controllers/'
                         . ucfirst($route['module']) . 'Controller.php')) {
                         echo $path;
            throw new ControleurIntrouvableException('Controller not found!');
        }
        require_once($path);

        $class = ucfirst($route['module']) . 'Controller';
        $controller = new $class($request, $response);
        $controller->_moduleName = $route['module'];
        return $controller->launch($route['action']);
    }

    public static function processException(Request $request,
                                            HttpResponse $response, $e)
    {
        $controller = new ActionController($request, $response);
        return $controller->launchException($e);
    }

    public function __construct(Request $request, HttpResponse $response)
    {
        $this->_request  = $request;
        $this->_response = $response;
        $this->_redirected = false;
    }

    private function _actionExists($action)
    {
        try {
            $method = new ReflectionMethod(get_class($this), $action);
            return ($method->isPublic() && !$method->isConstructor());
        } catch (Exception $e) {
            return false;
        }
    }

    public function redirect($url)
    {
        if ($this->_redirected == true) {
            throw new Exception('A redirection has already been asked!');
        }
        $this->_response->redirect($url);
        $this->_redirected = true;
    }

    private function _render($file)
    {
        $view = new View();
        
        $body = NULL;
        
        if($this->_includeTemplate)
        $body = $view->render(FrontController::$dirname
                              . '/views/header.phtml');
        
        $body .= $view->render(FrontController::$dirname
                               . '/views/' . $file . '.phtml',
                               $this->_response->getVars());
        
        if($this->_includeTemplate)
        $body .= $view->render(FrontController::$dirname
                               . '/views/footer.phtml');
        
                               
        $this->_response->setBody($body);
    }

    public function __get($param)
    {
        return $this->_response->getVar($param);
    }

    public function __set($name,$param)
    {
        $this->_response->setVar($name, $param);
    }

    public function launch($action)
    {
        $actionMethodName = $action . 'Action';

        if (!$this->_actionExists($actionMethodName)) {
            throw new ActionIntrouvableException('Action not found!');
        }

        $this->$actionMethodName();

        if (!$this->_redirected) {
            $this->_render($this->_moduleName . '/' . $action);
        }
        return $this->_response;
    }

    public function launchException(Exception $e)
    {
        if ($e instanceof MVCException) {
            $this->_render('404');
        } else {
            echo $e->getMessage();
            $this->_render('500');
        }
        return $this->_response;
    }
}
