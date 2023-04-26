<?php

class Order
{
	use Model;

	protected $table = 'orders';

	public function insert($data)
	{
		$recieve_address = $data['recieve_address'];
		$recieve_phonenum = $data['recieve_phonenum'];
		$note = $data['note'];
		$order_date = "current_date()";
		$order_status = "Đang chuẩn bị";
		$ship_fee = $data['ship_fee'];
		$userID_ordcus = $data['userID_ordcus'];

		$query = "call placeOrder('" . $recieve_address . "', $recieve_phonenum,'" . $note . "'," . $order_date . ",'" . $order_status . "', $ship_fee, $userID_ordcus);";
		return $this->query($query);
	}
}