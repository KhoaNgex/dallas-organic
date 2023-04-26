<?php
class AuthController
{
	use Controller;
	protected $model = 'User';

	private function validateRegister($data)
	{
		$usernamePattern = '/^[a-zA-Z0-9_-]{3,16}$/';
		$emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
		$phonePattern = '/^[0-9]{10,}$/';
		$passwordPattern = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/';

		// Validate username
		if (!preg_match($usernamePattern, $data['username'])) {
			$this->data_obj->errors = "Vui lòng nhập tên tài khoản từ 3-16 ký tự, chỉ bao gồm chữ cái thường, chữ in hoa, số và dấu gạch dưới.";
			return false;
		}

		// Validate email
		if (!preg_match($emailPattern, $data['email'])) {
			$this->data_obj->errors = "Vui lòng nhập email hợp lệ.";
			return false;
		}

		if (!preg_match($phonePattern, $data['phonenumber'])) {
			$this->data_obj->errors = "Số điện thoại chỉ gồm ký tự số và có ít nhất 10 chữ số.";
			return false;
		}

		if (!preg_match($passwordPattern, $data['password'])) {
			$this->data_obj->errors = "Vui lòng nhập mật khẩu có ít nhất 8 ký tự và bao gồm ít nhất một chữ cái và một chữ số.";
			return false;
		}

		return true;
	}

	private function validateLogin($data)
	{
		$usernamePattern = '/^[a-zA-Z0-9_-]{3,16}$/';
		$passwordPattern = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/';

		// Validate username
		if (!preg_match($usernamePattern, $data['username'])) {
			$this->data_obj->errors = "Vui lòng nhập tên tài khoản từ 3-16 ký tự, chỉ bao gồm chữ cái thường, chữ in hoa, số và dấu gạch dưới.";
			return false;
		}


		if (!preg_match($passwordPattern, $data['password'])) {
			$this->data_obj->errors = "Vui lòng nhập mật khẩu có ít nhất 8 ký tự và bao gồm ít nhất một chữ cái và một chữ số.";
			return false;
		}

		return true;
	}

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
			case 'register':
				$this->userRegister($data);
				break;
			case 'check':
				$this->userCheck();
				break;
			default:
				echo json_encode("API doesn't exist!");
				break;
		}
	}

	public function userLogin($data)
	{

		if ($this->validateLogin($data)) {
			$row = $this->data_obj->first(array("username" => $data["username"]));

			if ($row) {
				if (password_verify($data['password'], $row->password)) {
					http_response_code(200);
					$_SESSION['USER'] = $row->id;
					echo json_encode('Login successfully!');
				} else {
					http_response_code(404);
					$this->data_obj->errors['password'] = "Wrong password!";
					echo json_encode($this->data_obj->errors);
				}
			} else {
				http_response_code(404);
				$this->data_obj->errors['username'] = "Username doesn't exist!";
				echo json_encode($this->data_obj->errors);
			}
		} else {
			echo json_encode($this->data_obj->errors);
		}

	}

	public function userRegister($data)
	{
		if ($this->validateRegister($data)) {
			//hash password
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
			$this->createItem($data);
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

	public function userCheck()
	{
		if (isset($_SESSION['USER'])) {
			// Session variable is set
			echo json_encode($_SESSION['USER']);
		} else {
			// Session variable is not set
			echo json_encode('not set');
		}
	}

}