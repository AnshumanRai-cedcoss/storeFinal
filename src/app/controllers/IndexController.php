<?php

use Phalcon\Mvc\Controller;

/**
 * Index class
 * Front Page of the website
 */
class IndexController extends Controller
{


    /**
     * index action of index controller
     * Used for the login request and main page of the app
     * @return void
     */
    public function indexAction()
    {  
       if(count($this->request->getPost()) > 0)
       {     
            $res  = $this->request->getPost();

            $obj = new \App\Components\Myescaper() ;
            $arra = $obj->escaped($res);

            $result = Users::find(
                [
                    'columns' => '*',
                    'conditions' => 'email = ?1 AND password =?2',
                    'bind' => 
                    [
                        1 => $arra["email"],
                        2 => $arra["password"]
                    ]
                ]
            );
            if(count($result) > 0)
            {
                
                $this->session = $this->container->getSession();
                $this->session->set('user',json_decode(json_encode($result)));
                $res = json_decode(json_encode($result)); 
                // echo "<pre>";
                // print_r($res);
                // die;
                $this->response->redirect('dashboard?bearer='.$res[0]->role);
            }
            else 
            { 
                $this->mylogs
                ->error('Wrong email or password!Try again');
                $this->view->message = "Wrong email or password!Try again";
            }
            
       }
    
  
    }


    /**
     * Signout function
     *
     * @return void
     */
    public function signoutAction()
    {
        $this->session->destroy();
        $this->session->remove('user');
        $this->response->redirect("");

    }
 

    
}