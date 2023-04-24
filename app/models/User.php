<?php 


/**
 * User class
 */
class User
{
	
	use Model;

	protected $table = 'users';

	protected $allowedColumns = [
		'confirm-password',
		'email',
		'password',
		'address',
		'phone-number',
		'day', 'month', 'year'
	];

	public function validate($data)
	{
		$this->errors = [];
		if (empty($data['fullname'])) {
			$this->errors['fullname'] = "Vui lòng nhập họ và tên!";
		} else if (strlen($_POST["fullname"]) < 5) {
			$this->errors['fullname'] = "Họ và tên phải chứa ít nhất 5 kí tự!";
		}
		if (empty($data['username'])) {
			$this->errors['username'] = "Vui lòng nhập tên đăng nhập!";
		} else if (strlen($_POST["username"]) < 2 || strlen($_POST["username"]) > 30) {
			$this->errors['username'] = "Tên đăng nhập phải chứa từ 2-30 kí tự!";
		}
		if (empty($data['password'])) {
			$this->errors['password'] = "Vui lòng nhập mật khẩu!";
		} else if (strlen($_POST["password"]) < 8) {
			$this->errors['password'] = "Mật khẩu phải chứa ít nhất 8 kí tự!";
		}
		if (empty($data['confirm-password'])) {
			$this->errors['confirm-password'] = "Vui lòng xác nhận mật khẩu!";
		} else if (strcmp($_POST['password'], $_POST['confirm-password']) != 0) {
			$this->errors['confirm-password'] = "Xác nhận mật khẩu không chính xác!";
		}
		if(empty($data['gender']))
		{
			$this->errors['gander'] = "Vui lòng chọn giới tính!";
		}
		switch ($_POST["month"]) {
			case 4:
			case 6:
			case 9:
			case 11:
				if ($_POST["day"] == 31) {
					$this->errors['day'] = "Ngày sinh không hợp lệ!";
				}
				break;
			case 2:
				if ($_POST["day"] >= 30) {
					$this->errors['day'] = "Ngày sinh không hợp lệ!";
				}
				if ($_POST["day"] == 29) {
					$y = $_POST["year"];
					if ($y % 4 != 0 || ($y % 100 == 0 && $y % 400 != 0))
					$this->errors['day'] = "Ngày sinh không hợp lệ!";
				}
				break;
		}
		if(empty($data['email']))
		{
			$this->errors['email'] = "Vui lòng nhập email!";
		}else
		if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
		{
			$this->errors['email'] = "Địa chỉ email không hợp lệ!";
		}
		if (empty($data['address'])) {
			$this->errors['address'] = "Vui lòng nhập địa chỉ liên lạc!";
		} else if (strlen($_POST["address"]) < 8) {
			$this->errors['address'] = "Địa chỉ liên lạc phải chứa ít nhất 8 kí tự!";
		}
		function check_phone_number (string $s) {
			if (strlen($s) != 10) return false;
			if ($s[0] != '0') return false;
			if ($s[1] != '3' && $s[1] != '7' && $s[1] != '8' && $s[1] != '9') return false;
			for ($i = 2; $i < 10; $i++) {
				if ($s[$i] < '0' || $s[$i] > '9') return false;
			} 
			return true;
		}
		if(empty($data['phone-number']))
		{
			$this->errors['phone-number'] = "Vui lòng nhập số điện thoại!";
		} else if (!check_phone_number($_POST['phone-number'])) {
			$this->errors['phone-number'] = "Số điện thoại không hợp lệ!";
		}
		
		if(empty($data['terms']))
		{
			$this->errors['terms'] = "Vui lòng chấp nhận mọi điều khoản!";
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}
}