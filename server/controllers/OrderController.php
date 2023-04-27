<?php
class OrderController
{
	use Controller;
	protected $model = 'Order';

	private function filterItem($data)
	{
		if ($result = $this->data_obj->get_user_orders($data)) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve item from database!");
		}
	}

	private function getItem($id)
	{
		if ($result = $this->data_obj->get_order_details($id)) {
			http_response_code(200);
			echo json_encode($result);
		} else {
			http_response_code(404);
			echo json_encode("Can't retrieve item from database!");
		}
	}
}