<?php 

/**
 * home class
 */
class Home
{
	use Controller;

	public function index()
	{

		$data['username'] = empty($_SESSION['USER']) ? 'User':$_SESSION['USER']->email;

		$this->view('home',$data);
	}

}
