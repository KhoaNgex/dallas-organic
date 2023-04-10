<?php 

class User
{ 
	
	use Model;

	protected $table = 'user_account';

	protected $allowedColumns = [
		'username',
		'password',
	]; 

	public function validate($data)
	{
		$this->errors = [];

		if(empty($data['username']))
		{
			$this->errors['username'] = "Vui lòng nhập tên người dùng!";
		}
		
		if(empty($data['password']))
		{
			$this->errors['password'] = "Vui lòng nhập mật khẩu!";
		}
		
		if(empty($data['terms']))
		{
			$this->errors['terms'] = "Vui lòng chấp nhận điều khoản và dịch vụ!";
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}
}