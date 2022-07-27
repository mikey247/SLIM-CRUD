<?php 

namespace App\Controllers;
use PDO;

class ProductController extends BaseController{

        public $errors = [];
        public $title='';
        public $price='';
        public $description='';

    public function index($request , $response){
            $products = $this->c->db->query('SELECT * FROM products')->fetchAll(PDO::FETCH_ASSOC);
            // return $response->write(print_r($products, true)); 
            return $this->c->view->render($response, 'products/index.twig',compact('products'));
    }

    public function create($request, $response){

        return $this->c->view->render($response, 'products/create.twig');
    }

    public function createOne($request, $response){
        $entries = $request->getParams(); 
        $this->errors = [];
        $this->title = $entries['title'];
        $this->price = $entries['price'];
        $this->description = $entries['description'];
        $this->image = $_FILES['image'];
        $this->imagePath = '';
        $this->date = date('Y-m-d H:i:s');

        if (!is_dir('images')) {
            mkdir('images');
        }

        if ($this->image && $this->image['tmp_name']) {
            $this->imagePath = 'images/' . $this->randomString(8) . '/' . $this->image['name'];
            mkdir(dirname($this->imagePath));
            move_uploaded_file($this->image['tmp_name'], $this->imagePath);
        }

        if(!$this->title){
            $this->errors[] = "Product title is required";
         };
     
         if(!$this->price){
             $this->errors[] = "Product price is required";
         };

         if(!$this->description){
            $this->errors[] = "Product description is required";
        };
         
        if(empty($this->errors)){
     
         $this->statement = $this->c->db->prepare("INSERT INTO products (title, image, description, price, create_date)
                                 VALUES (:title, :image, :description, :price, :date)"
         );
             $this->statement->bindValue(':title', $this->title);
             $this->statement->bindValue(':image', $this->imagePath);
             $this->statement->bindValue(':description', $this->description);
             $this->statement->bindValue(':price', $this->price);
             $this->statement->bindValue(':date', $this->date);
             $this->statement->execute();
            //  header('Location: index.php');
            return $response->withRedirect($this->c->router->pathFor('home'));
        }else{
              $formErrors = $this->errors;
              if (empty($formErrors)) {
                $formErrors=null;
              }

              $formValues=array(
                'title'=> $this->title,
                'price'=>$this->price,
                'description'=>$this->description
              );

              $values = array(
               'formErrors'=> $formErrors,
               'formValues'=> $formValues
              );
            //   return $response->write(print_r($values));
              return $this->c->view->render($response, 'products/create.twig',compact('values')); 
        }
    }


    public function delete($request, $response, $args){

        $id = $args['id'];
        $statement = $this->c->db->prepare('DELETE FROM products WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
        // return $response->write(print_r($args['id'],true));
        return $response->withRedirect($this->c->router->pathFor('home'));
    }

    public function randomString($n){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $str .= $characters[$index];
        }

        return $str;
    }

           

}