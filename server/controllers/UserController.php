<?php
class UserController
{
	use Controller;
	protected $model = 'User';

	private function createItem($data)
	{
		$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
		if ($this->data_obj->insert($data)) {
			http_response_code(200);
			echo json_encode("Create item successfully!");
		} else {
			http_response_code(404);
			echo json_encode("Can't insert into database!");
		}
	}
}