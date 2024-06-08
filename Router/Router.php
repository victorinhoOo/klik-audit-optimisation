<?php

foreach(glob("Controllers/*.php") as $file){
    require_once($file);
}

foreach(glob("Router/Routes/*.php") as $file){
    require_once($file);
}

class Router{
    private array $routesList;
    private array $controllersList; 

    public function __construct()
    {
        $this->createControllersList();
        $this->createRoutesList();
    }

    private function createControllersList():void{
        $this->controllersList["main"] = new MainController();
        $this->controllersList["user"] = new UserController();
    }

    private function createRoutesList():void{
        $this->routesList["index"] = new RouteIndex($this->controllersList["main"]);
        $this->routesList["connexion"] = new RouteConnexion($this->controllersList["user"]);
    }

    public function routing($get, $post){
        session_start();
        if(empty($post)){
            if(isset($get["action"])){
                $this->routesList[$get["action"]]->action();
            }
            else{
                if(isset($_SESSION["connectedUser"])){
                    $this->routesList["index"]->action();
                }else{
                    $this->routesList["connexion"]->action();
                }
            }
        }
        else{
            $this->routesList[$get["action"]]->action($post, 'POST');
        }
    }
}