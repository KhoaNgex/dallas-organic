<?php
trait Controller
{
	private $data_obj;

	public function index($action, $id = -1, $data = [])
	{
		$this->data_obj = new $this->model;
		switch ($action) {
			case 'getAll':
				$this->getAll();
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
			default:
				echo json_encode("API doesn't exist!");
				break;
		}
	}

	private function getAll()
	{
		if ($result = $this->data_obj->findAll()) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve item list from database!");
		}
	}

	private function getItem($id)
	{
		if ($id == -1) {
			http_response_code(404);
			echo json_encode("API doesn't exist!");
			return;
		}

		$data['id'] = $id;
		if ($result = $this->data_obj->where($data)) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve item from database!");
		}
	}

	private function createItem($data)
	{
		if ($this->data_obj->insert($data)) {
			http_response_code(200);
			echo json_encode("Create item successfully!");
		} else {
			http_response_code(404);
			echo json_encode("Can't insert into database!");
		}
	}

	private function editItem($id, $data)
	{
		if ($id == -1) {
			http_response_code(404);
			echo json_encode("API doesn't exist!");
			return;
		}
		if ($this->data_obj->update($id, $data)) {
			http_response_code(200);
			echo json_encode("Update item successfully!");
		} else {
			http_response_code(404);
			echo json_encode("Can't update item in database!");
		}
	}

	private function removeItem($id)
	{
		if ($id == -1) {
			http_response_code(404);
			echo json_encode("API doesn't exist!");
			return;
		}
		if ($this->data_obj->delete($id)) {
			http_response_code(200);
			echo json_encode("Remove item successfully!");
		} else {
			http_response_code(404);
			echo json_encode("Can't remove item in database!");
		}
	}

	private function filterItem($data)
	{
		if ($result = $this->data_obj->where($data)) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve item from database!");
		}
	}

}