<?php

/**
 * Класс Router
 * Компонент для работы с маршрутами
 */
class Router {
    
    private $routes; //массив, в котором будут храниться роуты
    
    public function __construct() {
        
        $routesPath = ROOT.'/config/routes.php'; //путь к роутам
        $this->routes = include($routesPath);
        
    }
    
    private function getURI() {
        
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
    
    public function run() {
        
        //Получить строку запроса
        $uri = $this->getUri();
        //Проверка наличие такого запроса в routes.php
        foreach($this->routes as $uriPattern => $path) {  
            
            //Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {
                
                //Получаем внутренний путь из внешнего согласно правилу
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                //Определить какой контроллер и метод обрабатывает запрос
                $segments = explode('/', $internalRoute);
                //Получить имя контроллера
                $controllerName = ucfirst(array_shift($segments)).'Controller';
                //Получить имя метода
                $actionName = 'action'.ucfirst(array_shift($segments));
                //Подключить файл класса контроллера
                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }
                //Создать объект, вызвать метод
                $parameters = $segments;
                $controllerObject = new $controllerName;
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);
                if($result != null) {
                    break;
                }
            }
        }
    }
}
