<?php

class Routing {

	public static function buildRoute() {

		/*Контроллер и action по умолчанию*/
		$controllerName = "IndexController";
		$action = "index";

		$route = explode("/", $_SERVER['REQUEST_URI']);

		/*Определяем контроллер*/
		if($route[1] != '' && count($route)>2) {
			$controllerName = ucfirst($route[1]. "Controller");
		}
		else if ($route[1][0]=='?') {
			$action = 'redirect';
		}

		if(isset($route[2]) && $route[2] !='') {
			$action = $route[2];
		}

		if(file_exists(CONTROLLER_PATH . $controllerName . ".php"))
		{
			require_once CONTROLLER_PATH . $controllerName . ".php";

			$controller = new $controllerName;

			if(method_exists($controller, $action))
			{
				$controller->$action();
			}
			else
			{
				Routing::ErrorPage404('action');
			}

		}
		else
		{
			Routing::ErrorPage404('controller');
		}
	}

	function ErrorPage404($error)
	{
    echo "Not Found ". $error;
  }
}

?>
