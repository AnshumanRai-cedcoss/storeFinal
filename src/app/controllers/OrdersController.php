<?php

use Phalcon\Mvc\Controller;

/**
 * orders class
 * order adding and listing
 */
class OrdersController extends Controller
{

  /**
   * index action of orders controller
   * this is used to ad the order as per the form
   * @return void
   */
  public function indexAction()
  {
    $name = Product::find(
      [
        'columns'    => 'name',
      ]
    );
    $res = json_decode(json_encode($name));

    $this->view->data = $res;

    if (isset($this->request->getPost()["placeOrder"])) {
      $res = $this->request->getPost();

      $obj = new \App\Components\Myescaper() ;
      $arra = $obj->escaped($res);

      $order = new Orders();
      $order->assign(
        $arra,
        [
          'customerName',
          'address',
          'zip',
          'product',
          'quantity'
        ]
      );
      $success = $order->save();
      if ($success) {
        $this->view->message = "Ordered placed successfully";
      }
    }
  }

  /**
   * List function
   * LIsting of all the placed orders
   * @return void
   */
  public function listAction()
  {
    $res = json_decode(json_encode(Orders::find(
      [
        'columns'    => '*'
      ]
    )));

    $this->view->data = $res;
  }

  /**
   * edit placed orders
   *
   * @return void
   */
  public function updateAction()
  {
    $id = $this->request->get('id');
    $data = Orders::findFirst($id);
    $this->view->data = $data;

    $name = Product::find(
      [
        'columns'    => 'name',
      ]
    );
    $res = json_decode(json_encode($name));
  

    $this->view->name = $res;


    if (count($this->request->getPost()) > 0) {
      $res = $this->request->getPost();
       
      $obj = new \App\Components\Myescaper() ;
      $arra = $obj->escaped($res);

      $data->customerName = $arra["customerName"];
      $data->address = $arra["address"];
      $data->zip = $arra["zip"];
      $data->product = $arra["product"];
      $data->quantity = $arra["quantity"];

      $succ = $data->save();
      if ($succ) {
        $this->response->redirect("orders/list");
      }
    }
  }



  /**
   * delete the order
   *
   * @return void
   */
  public function deleteAction()
  {
    $id = $this->request->get('id');
    $data = Orders::findFirst($id);
    $succ = $data->delete();
    if ($succ) {
      $this->response->redirect("product/list");
    }
  }
}
