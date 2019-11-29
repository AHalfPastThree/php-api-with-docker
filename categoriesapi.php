<?php
require_once 'Api.php';
require_once 'db.php';
require_once 'categories.php';

class CategoriesApi extends Api
{
    public $apiName = 'categories';

    /**
     * Метод GET
     * Вывод списка всех записей
     * http://ДОМЕН/categories
     * @return string
     */
    public function indexAction()
    {
        $category = new Category();
        $categories = $category->getCategories();
        if($categories){
            return $this->response($categories, 200);
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http://ДОМЕН/categories/1
     * @return string
     */
    public function viewAction()
    {
        //id должен быть первым параметром после /categories/x
        $id = array_shift($this->requestUri);

        if($id){
            $category = new Category();
            $categories = $category->getCategory($id);
            if($categories){
                return $this->response($categories, 200);
            }
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Метод POST
     * Создание новой записи
     * http://ДОМЕН/categories + параметр запроса category_name
     * @return string
     */
    public function createAction()
    {
        $name = $this->requestParams['category_name'] ?? '';
        if($name){
            $category = new Category();
            $new_category = $category->createCategory($name);
            if($new_category){
                return $this->response('Data saved.', 200);
            }
        }
        return $this->response("Saving error", 500);
    }

    /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http://ДОМЕН/categories/1 + параметр запроса category_name
     * @return string
     */
    public function updateAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $categoryId = $parse_url['path'] ?? null;

        $category = new Category();

        if(!$categoryId || !$category->getCategory($categoryId)){
            return $this->response("User with id=$categoryId not found", 404);
        }

        $name = $this->requestParams['category_name'] ?? '';

        if($name){
            if($update_category = $category->updateCategory($categoryId, $name)){
                return $this->response('Data updated.', 200);
            }
        }
        return $this->response("Update error", 400);
    }

    /**
     * Метод DELETE
     * Удаление отдельной записи (по ее id)
     * http://ДОМЕН/categories/1
     * @return string
     */
    public function deleteAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $category_Id = $parse_url['path'] ?? null;

        $category= new Category();

        if(!$category_Id || !$category->getCategory($category_Id)){
            return $this->response("User with id=$category_Id not found", 404);
        }
        if($category->deleteCategory($category_Id)){
            return $this->response('Data deleted.', 200);
        }
        return $this->response("Delete error", 500);
    }

}