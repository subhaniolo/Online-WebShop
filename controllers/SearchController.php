<?php
class SearchController{
    public function actionIndex(){
        $search = htmlspecialchars($_POST['search']);
        $searchProducts = Product::getSearchProduct($_POST['search']);
        
      // Список категорий для левого меню
        $categories = Category::getCategoriesList();

        // Список товаров в категории
//        $categoryProducts = Product::getProductsListByCategory($categoryId, $page);

        require_once(ROOT . '/views/site/search.php');
        return true;
    }
}
