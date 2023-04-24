<?php
class ProductController
{
	use Controller;
	protected $model = 'Product';

	public function index($action, $id = -1, $data = [])
	{
		$this->data_obj = new $this->model;
		switch ($action) {
			case 'getAll':
				$this->getAll();
				break;
			case 'getAllTitle':
				$this->getAllTitle($data);
				break;
			case 'getCount':
				$this->getCount();
				break;
			case 'getItem':
				$this->getItem($id);
				break;
			case 'createItem':
				$this->createItem($data);
				break;
			case 'editItem':
				$this->editItem($id, $data);
				break;
			case 'removeItem':
				$this->removeItem($id);
				break;
			case 'filterItem':
				$this->filterItem($data);
				break;
			case 'getBest':
				$this->getBest();
				break;
			default:
				echo json_encode("API doesn't exist!");
				break;
		}
	}

	private function getBest()
	{
		if ($result = $this->data_obj->getBestSellers()) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve products from database!");
		}
	}

	private function getCount()
	{
		if ($result = $this->data_obj->findCount()) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve info from database!");
		}
	}
	private function getAllTitle($data)
	{
		if ($result = $this->data_obj->findAllTitle($data)) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve products from database!");
		}
	}

}