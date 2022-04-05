<?php

use Phalcon\Mvc\Controller;

/**
 * Settings class
 */
class SettingsController extends Controller
{
    /**
     * index function
     * display the settings form
     * @return void
     */
    public function indexAction()
    {
        $sett = new Settings();
        $this->view->data = $sett->find();
    }


    /**
     * 
     * Update function of settings class
     * Used to update the settings in database
     * @return void
     */
    public function updateAction()
    {
        if(isset($this->request->getPost()["addSett"]))
        {
            $data = $this->request->getPost();
            $obj = new \App\Components\Myescaper() ;
            $array = $obj->escaped($data);
            
            $sett = new Settings();
            $res = $sett->findFirst(1);
            $res->zip = $array["zip"];
            $res->price = $array["price"];
            $res->stock = $array["stock"];
            $res->title = $array["title"];
            $succ = $res->save();
            $this->response->redirect("dashboard?bearer=");

            // //updating order 
            // $product = new Orders();
            // $res = $product->find();
            // $var=$res->getlast();
            // $id = $var->order_id;
            // $res = $product->findFirst($id);
            // if($res->zip == "")
            // {
            //     $res->zip = $data["defaultZip"];
            //     $res->save();
            // }
            // //updating order end

            // //update product
            //  $product = new Product();
            //  $res = $product->find();
            //  $var=$res->getlast();
            //  $id = $var->id;
            //  $res = $product->findFirst($id);
            //  if($data["product"] == "withTag")
            //  {
            //      $res->name = $res->name.$res->tags;
            //  }
            //  if($res->price == "")
            //  {
            //     $res->price = $data["defaultPrice"];
            //  }
            //  if($res->stock == "")
            //  {
            //     $res->stock = $data["defaultStock"];
            //  }
            //  $success = $res->save();
            //  $role= $this->request->get('bearer');
            
            // //product updating ended
        }
    }

}