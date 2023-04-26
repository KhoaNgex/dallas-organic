<?php
class App
{
	private $controller = 'Controller';
	private function splitURL()
	{
		$URL = $_GET['url'] ?? 'home';
		$URL = explode("/", trim($URL, "/"));
		return $URL;
	}

	public function processAPI()
	{
		$URL = $this->splitURL();
		$component = $URL[0] . 'Controller';
		$component_action = $URL[1];
		$component_id = count($URL) > 2 ? $URL[2] : -1;

		/** select controller **/
		$filename = __DIR__ . "/../controllers/" . ucfirst($component) . ".php";

		if (file_exists($filename)) {
			require $filename;
			$this->controller = ucfirst($component);
		} else {
			$filename = __DIR__ . "/../controllers/_404.php";
			require $filename;
			$this->controller = "_404";
		}

		$controller = new $this->controller;
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = json_decode(file_get_contents("php://input"), true);
			$controller->index($component_action, $component_id, $data);
		} else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
			$data = json_decode(file_get_contents("php://input"), true);
			$controller->index($component_action, isset($_GET['id']) ? $_GET['id'] : -1, $data);
		} else {
			$controller->index($component_action, $component_id, $_GET);
		}

	}

}