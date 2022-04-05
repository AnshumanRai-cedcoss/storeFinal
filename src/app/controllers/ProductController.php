<?php

use Phalcon\Mvc\Controller;

/**
 * Product class
 * Product adding and listing
 */
class ProductController extends Controller
{

  /**
   * index action of products controller
   * this is used to ad the product as per the form
   * @return void
   */
  public function indexAction()
  {
    if (isset($this->request->getPost()["addProd"])) {
    
      $res = $this->request->getPost();

      $obj = new \App\Components\Myescaper() ;
      $array = $obj->escaped($res);
       
      $product = new Product();
      $product->assign(
        $array,
        [
          'name',
          'description',
          'tags',
          'price',
          'stock',
        ]
      );

      $success = $product->save();
      if ($success) {
        $this->view->message = "Product added successfully";
      }
    }
  }

  /**
   * List function
   * LIsting of all the available products
   * @return void
   */
  public function listAction()
  {
    $locale = $this->request->get("lang");

    $v = new App\Locale\Locale();

    $data = $v->getTranslator();

    if (!$this->Cache->has($locale)) {
        $this->Cache->set($locale, $data);
    }
     $this->view->t = $this->Cache->get($locale); 
 
    $res = json_decode(json_encode(Product::find(
      [
        'columns'    => '*'
      ]
    )));


    $this->view->data = $res;
  }

  /**
   * edit the product by admin
   *
   * @return void
   */
  public function updateAction()
  {
    $id = $this->request->get('id');
    $data = Product::findFirst($id);
    $this->view->data = $data;
    if (isset($this->request->getPost()["editProd"])) {
      $res = $this->request->getPost();

      $obj = new \App\Components\Myescaper() ;
      $array = $obj->escaped($res);

      $data->name = $array["name"];
      $data->tags = $array["tags"];
      $data->price = $array["price"];
      $data->stock = $array["stock"];
      $succ = $data->save();
      if ($succ) {
        $this->response->redirect("product/list");
      }
    }
  }



  /**
   * delete the product
   *
   * @return void
   */
  public function deleteAction()
  {
    $id = $this->request->get('id');
    $data = Product::findFirst($id);

    $succ = $data->delete();
    if ($succ) {
      $this->response->redirect("product/list");
    }
  }
}
