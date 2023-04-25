<?php
class Feedback
{
	use Model;
	protected $table = 'feedback';

	public function __construct()
	{
		$this->order_column = "feedback_datetime";
	}
	public function findProdFeedback($product_id)
	{
		$query = "call getProductFeedback($product_id);";
		return $this->query($query);
	}

	public function findAll()
	{
		$query = "call getAllFeedback();";
		return $this->query($query);
	}

	public function findAvgRating($product_id)
	{
		$query = "select calculate_average_rating($product_id) as avg_rating;";
		return $this->query($query);
	}

}