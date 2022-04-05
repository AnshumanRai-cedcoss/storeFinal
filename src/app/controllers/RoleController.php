<?php

use Phalcon\Mvc\Controller;

/**
 * Role class
 * Used by admin to add a new role
 */
class RoleController extends Controller
{
    /**
     * index function
     * Admin adds a new role here
     * @return void
     */  
    public function indexAction()
    {

        if(count($this->request->getPost()) > 0)
       {


           $obj = new \App\Components\Myescaper() ;
             $array = $obj->escaped($res);
           $res = $this->request->getPost();
           $role = new Role();
           $role->assign(
            $array, 
              [
                   'jobProfile'    
              ]
          );
          $role->save();
          $rolelog= $this->request->get('bearer');
          $this->response->redirect("dashboard?bearer=".$rolelog);
       }
    }
  
    
}