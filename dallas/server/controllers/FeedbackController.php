<?php
class FeedbackController
{
    use Controller;
    protected $model = 'Feedback';

    public function index($action, $id = -1, $data = [])
    {
        $this->data_obj = new $this->model;
        switch ($action) {
            case 'getAll':
                $this->getAll();
                break;
            case 'createItem':
                $this->createItem($data);
                break;
            case 'editItem':
                $this->editItem($id, $data);
                break;
            case 'removeItem':
                $this->removeItem($id);
                break;
            case 'getForDisplay':
                $this->getProdFeedback($id);
                break;
            case 'getAvgRating':
                $this->getAvgRating($id);
                break;
            default:
                echo json_encode("API doesn't exist!");
                break;
        }
    }

    private function getProdFeedback($id)
    {
        if ($id == -1) {
            http_response_code(404);
            echo json_encode("API doesn't exist!");
            return;
        }

        if ($result = $this->data_obj->findProdFeedback($id)) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode("Can't retrieve item from database!");
        }
    }

    private function getAvgRating($id)
    {
        if ($id == -1) {
            http_response_code(404);
            echo json_encode("API doesn't exist!");
            return;
        }

        if ($result = $this->data_obj->findAvgRating($id)) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode("Can't calculate average rating for product!");
        }
    }

}