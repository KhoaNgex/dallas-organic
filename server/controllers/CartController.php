<?php
class CartController
{
    use Controller;
    protected $model = 'Cart';

    public function index($action, $id = -1, $data = [])
    {
        $this->data_obj = new $this->model;
        switch ($action) {
            case 'getAll':
                $this->getAll($id);
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
            default:
                echo json_encode("API doesn't exist!");
                break;
        }
    }

    private function getAll($user_id)
    {
        if ($result = $this->data_obj->findAll($user_id)) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode("Can't retrieve cart from database!");
        }
    }

}