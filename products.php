<?php 
	require_once ('db.php');

	class Product{
        public function getProducts(){
            $db = new Database();
            $db->connect();
            $data = array();
            $result = $db->mysqli->query("SELECT * FROM `products`");
            while($row = mysqli_fetch_assoc($result)){ // оформим каждую строку результата
                // как ассоциативный массив
                $data[] = $row; // допишем строку из выборки как новый элемент результирующего массива
            }
            return ($data);
        }

        public function getProduct($id){
            $db = new Database();
            $db->connect();
            $data = array();
            $result = $db->mysqli->query("SELECT * FROM `products` WHERE `id` = $id");
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
            return ($data);
        }

        public function createProduct($name, $price, $category){
            $db = new Database();
            $db->connect();
            $result = $db->mysqli->query("INSERT INTO `products`(`name`, `price`) VALUES ('$name', '$price')");
            $new_product_id = $db->mysqli->insert_id;
            if ($result){
                $category = explode(",", $category);
                foreach ($category as $cat){
                    $postresult = $db->mysqli->query("INSERT INTO `relationships`(`category_id`, `product_id`) VALUES ('$cat', '$new_product_id')");
                }
                if ($postresult){
                    return $result;
                }
            }
        }

        public function updateProduct($id, $name, $price, $category){
            $db = new Database();
            $db->connect();
            $result = $db->mysqli->query("UPDATE `products` SET `name` = '$name', `price` ='$price' WHERE `id` = $id");
            $new_product_id = $db->mysqli->insert_id;
            if ($result){
                $preresult = $db->mysqli->query("DELETE FROM `relationships`WHERE `product_id` = $id");
                if ($preresult){
                    $category = explode(",", $category);
                    foreach ($category as $cat){
                        $postresult = $db->mysqli->query("INSERT INTO `relationships`(`category_id`, `product_id`) VALUES ('$cat', '$id')");
                    }
                }
                if ($postresult){
                    return $result;
                }
            }
        }

        public function deleteProduct($id){
            $db = new Database();
            $db->connect();
            $preresult = $db->mysqli->query("DELETE FROM `relationships`WHERE `product_id` = $id");
            if ($preresult){
                $result = $db->mysqli->query("DELETE FROM `products`WHERE `id` = $id");
                return $result;
            }
        }
    }