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
                $this->editItem($data);
                break;
            case 'removeItem':
                $this->removeItem($data);
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

    private function createItem($data)
    {
        if ($this->data_obj->insert($data)) {
            http_response_code(200);
            echo json_encode("Change quantity in cart successfully!");
        } else {
            http_response_code(404);
            echo json_encode("Can't change quantity in cart!");
        }
    }

    private function editItem($data)
    {
        if ($this->data_obj->updateQuantity($data)) {
            http_response_code(200);
            echo json_encode("Change quantity in cart successfully!");
        } else {
            http_response_code(404);
            echo json_encode("Can't change quantity in cart!");
        }
    }

    private function removeItem($data)
    {
        if ($this->data_obj->deleteItem($data)) {
            http_response_code(200);
            echo json_encode("Delete item in cart successfully!");
        } else {
            http_response_code(404);
            echo json_encode("Can't delete item in cart!");
        }
    }

}