<?php
class BlogController
{
	use Controller;
	protected $model = 'Blog';

	public function index($action, $id = -1, $data = [])
	{
		$this->data_obj = new $this->model;
		switch ($action) {
			case 'getTitle':
				$this->getTitle();
				break;
			case 'getAllTitle':
				$this->getAllTitle($data['offset']);
				break;
			case 'getItem':
				$this->getItem($id);
				break;
		}
	}

	private function getTitle()
	{
		if ($result = $this->data_obj->findTitle()) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve item list from database!");
		}
	}

	private function getAllTitle($offset)
	{
		if ($result = $this->data_obj->findAllTitle($offset)) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve item list from database!");
		}
	}

}