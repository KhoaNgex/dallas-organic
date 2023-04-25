<?php
class AuthController
{
	use Controller;
	protected $model = 'User';

	public function index($action, $id = -1, $data = [])
	{
		$this->data_obj = new $this->model;
		switch ($action) {
			case 'getItem':
				$this->getItem($id);
				break;
			case 'editItem':
				$this->editItem($id, $data);
				break;
			case 'login':
				$this->userLogin($data);
				break;
			case 'logout':
				$this->userLogout();
				break;
			default:
				echo json_encode("API doesn't exist!");
				break;
		}
	}

	public function userLogin($data)
	{
		$check_data = $data;
		unset($check_data['terms']);

		if ($this->data_obj->validate($data)) {
			$row = $this->data_obj->first($check_data);
			if ($row) {
				$_SESSION['USER'] = $row;
				echo json_encode('Login successfully!');
			} else {
				$this->data_obj->errors['username'] = "Wrong password or username doesn't exist!";
				echo json_encode($this->data_obj->errors);
			}
		} else {
			echo json_encode($this->data_obj->errors);
		}

	}

	public function userLogout()
	{
		if (!empty($_SESSION['USER']))
			unset($_SESSION['USER']);
		echo json_encode("Logout successfully!");
	}

}