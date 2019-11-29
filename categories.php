<?php
require_once ('db.php');

class Category{
    public function getCategories(){
        $db = new Database();
        $db->connect();
        $data = array();
        $result = $db->mysqli->query("SELECT * FROM `categories`");
        while($row = mysqli_fetch_assoc($result)){ // оформим каждую строку результата
            // как ассоциативный массив
            $data[] = $row; // допишем строку из выборки как новый элемент результирующего массива
        }
        return ($data);
    }

    public function getCategory($id){
        $db = new Database();
        $db->connect();
        $data = array();
        $data_products = array();
        $result_category = $db->mysqli->query("SELECT * FROM `categories` WHERE `id` = $id");
        $result_products = $db->mysqli->query("SELECT `products`.* FROM `products` JOIN `relationships` ON `products`.id = `relationships`.product_id WHERE `relationships`.category_id = $id");
        while($row = mysqli_fetch_assoc($result_category)){
            $data['category'] = $row;
            while($row_prod = mysqli_fetch_assoc($result_products)){
                $data_products['products'][] = $row_prod;
            }
        }
        $result_data = array_merge($data, $data_products);
        return ($result_data);
    }

    public function createCategory($name){
        $db = new Database();
        $db->connect();
        $result = $db->mysqli->query("INSERT INTO `categories`(`category_name`) VALUES ('$name')");
        return $result;
    }

    public function updateCategory($id, $name){
        $db = new Database();
        $db->connect();
        $result = $db->mysqli->query("UPDATE `categories` SET `category_name` = '$name'  WHERE `id` = $id");
        return $result;
    }

    public function deleteCategory($id){
        $db = new Database();
        $db->connect();
        $result = $db->mysqli->query("DELETE FROM `categories`WHERE `id` = $id");
        if ($result){
            $postresult = $db->mysqli->query("DELETE FROM `relationships`WHERE `category_id` = $id");
            if ($postresult){
                return $result;
            }
        }
    }
}