<?php

require_once 'productapi.php';
require_once 'categoriesapi.php';
try {
    if (stripos($_SERVER['REQUEST_URI'], "/products")){     //Смотрим, какое апи запустить
        $api = new ProductsApi();
    }else{
        $api = new CategoriesApi();
    }
    $api->indexAction();
    echo $api->run();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
