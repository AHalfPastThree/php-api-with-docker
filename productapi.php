<?php
require_once 'Api.php';
require_once 'db.php';
require_once 'products.php';

class ProductsApi extends Api
{
    public $apiName = 'products';

    /**
     * Метод GET
     * Вывод списка всех записей
     * http://ДОМЕН/products
     * @return string
     */
    public function indexAction()
    {
        $product = new Product();
        $products = $product->getProducts();
        if($products){
            return $this->response($products, 200);
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http://ДОМЕН/products/1
     * @return string
     */
    public function viewAction()
    {
        //id должен быть первым параметром после /products/x
        $id = array_shift($this->requestUri);

        if($id){
            $product = new Product();
            $products = $product->getProduct($id);
            if($products){
                return $this->response($products, 200);
            }
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Метод POST
     * Создание новой записи
     * http://ДОМЕН/products + параметры запроса name, price, category
     * @return string
     */
    public function createAction()
    {
        $name = $this->requestParams['name'] ?? '';
        $price = $this->requestParams['price'] ?? '';
        $category = $this->requestParams['category_id'] ?? '';
        if($name && $price && $category){
            $product = new Product();
            $new_product = $product->createProduct($name, $price, $category);
            if($new_product){
                return $this->response('Data saved.', 200);
            }else{
                return $this->response("Saving error", 500);
            }
        }
    }

    /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http://ДОМЕН/products/1 + параметры запроса name, price, category
     * @return string
     */
    public function updateAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $productId = $parse_url['path'] ?? null;

        $product = new Product();

        if(!$productId || !$product->getProduct($productId)){
            return $this->response("User with id=$productId not found", 404);
        }

        $name = $this->requestParams['name'] ?? '';
        $price = $this->requestParams['price'] ?? '';
        $category = $this->requestParams['category_id'] ?? '';

        if($name && $price && $category){
            if($update_product = $product->updateProduct($productId, $name, $price, $category)){
                return $this->response('Data updated.', 200);
            }
        }
        return $this->response("Update error", 400);
    }

    /**
     * Метод DELETE
     * Удаление отдельной записи (по ее id)
     * http://ДОМЕН/products/1
     * @return string
     */
    public function deleteAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $productId = $parse_url['path'] ?? null;

        $product = new Product();

        if(!$productId || !$product->getProduct($productId)){
            return $this->response("User with id=$productId not found", 404);
        }
        if($product->deleteProduct($productId)){
            return $this->response('Data deleted.', 200);
        }
        return $this->response("Delete error", 500);
    }

}