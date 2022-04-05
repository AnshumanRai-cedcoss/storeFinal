<?php

use Phalcon\Mvc\Controller;

/**
 * User class
 * User Add and listing
 */
class UserController extends Controller
{

    /**
     * index action of users controller
     * this is used to add the user as per the form
     * @return void
     */
    public function indexAction()
    {  
        if(count($this->request->getPost()) > 0)
        {
          $obj = new \App\Components\Myescaper() ;
          $array = $obj->escaped($this->request->getPost());
            $user = new Users();    
            $user->assign(
              $array, 
              [
                   'name',
                   'email',
                   'password',
                   'role'
              ]
          );
          
          $success = $user->save();
          if($success)
          {
            $this->view->message = "User added successfully";
          }
        }
    }
    
   /**
    * List function
    * LIsting of all the users
    * @return void
    */
    public function listAction()
    {
        $res = json_decode(json_encode(Users::find(
            [
              'columns'    => '*'
            ]
          )));
      
    
          $this->view->data = $res;
    }



      /**
   * edit user
   *
   * @return void
   */
  public function updateAction()
  {
    $id = $this->request->get('id');
    $data = Users::findFirst($id);
    $this->view->data = $data;



   $role = Role::find();
   $this->view->role = $role;

    if (count($this->request->getPost()) > 0) {
      $res = $this->request->getPost();

      $obj = new \App\Components\Myescaper() ;
      $array = $obj->escaped($res);

      $data->name = $array["name"];
      $data->email = $array["email"];
      $data->password = $array["password"];
      $data->role = $array["role"];
      $succ = $data->save();
      if ($succ) {
        $this->response->redirect("user/list");
      }
    }
  }



  /**
   * delete the user
   *
   * @return void
   */
  public function deleteAction()
  {
    $id = $this->request->get('id');
    $data = Users::findFirst($id);

    $succ = $data->delete();
    if ($succ) {
      $this->response->redirect("user/list");
    }
  }  
}