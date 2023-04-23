<?php 


/**
 * User class
 */
class User
{
	
	use Model;

	protected $table = 'users';

	protected $allowedColumns = [

		'email',
		'password',
	];

	public function validate($data)
	{
		$this->errors = [];
		if(empty($data['fullname']))
		{
			$this->errors['fullname'] = "Fullname is required";
		}
		if(empty($data['username']))
		{
			$this->errors['username'] = "Username is required";
		}
		if(empty($data['password']))
		{
			$this->errors['password'] = "Password is required";
		}
		if(empty($data['gender']))
		{
			$this->errors['gander'] = "Gender is required";
		}
		if(empty($data['email']))
		{
			$this->errors['email'] = "Email is required";
		}else
		if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
		{
			$this->errors['email'] = "Email is not valid";
		}
		if(empty($data['address']))
		{
			$this->errors['address'] = "Address is required";
		}
		if(empty($data['phone-number']))
		{
			$this->errors['phone-number'] = "Phone number is required";
		}
		
		if(empty($data['terms']))
		{
			$this->errors['terms'] = "Please accept the terms and conditions";
		}

		if(empty($this->errors))
		{
			return true;
		}

		return false;
	}
}