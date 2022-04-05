<?php

namespace App\Listeners;

use Phalcon\Di\Injectable;
use Phalcon\Events\Event;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

/**
 * Notifications listeners class
 */
class NotificationsListeners extends Injectable
{
    public function productSett(Event $event, \App\Components\MyHelper $component)
    {
        $this->response->redirect("settings");
    }
    public function orderSett(Event $event, \App\Components\MyHelper $component)
    {
        $this->response->redirect("settings");
    }


    /**
     * Before handle request
     * Used to check the access granted to particular roles
     * @param Event $event
     * @param \Phalcon\Mvc\Application $application
     * @return void
     */
    public function beforeHandleRequest(Event $event, \Phalcon\Mvc\Application $application)
    {
        $aclFile = APP_PATH . '/security/acl.cache';

    
         
            if (true == is_file($aclFile)) {
            $acl = unserialize(
                file_get_contents($aclFile)
            );



            //token
            $bearer = $application->request->get("bearer");
            if ($bearer) {
                try {
                    $parser = new Parser();
                    $tokenObject = $parser->parse($bearer);
                    $now = new \DateTimeImmutable();
                    // $expires=$now->modify('+1 day')->getTimestamp();
                    $expires = $now->getTimestamp();
                    $validator = new Validator($tokenObject, 100);
                    $validator->validateExpiration($expires);
                    $role =  $tokenObject->getClaims()->getPayload()["sub"];
                
//get Acl conditions
                    $res =  $application->request->get();
                    if (!isset($res["_url"])) {
                        $controller = "index";
                        $action = "index";
                    } else {
                        $controller = $this->router->getControllerName();
                        $action = $this->router->getActionName();
                        if($action == null)
                        {
                            $action = "index";
                        }
                        }
                     
                       if (!$role || true !== $acl->isAllowed($role, $controller, $action)) {
                        die("access denied man if admin change role");
                    }

                    //acl end

                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            } else {
                $controller = $this->router->getControllerName();
                $action = $this->router->getActionName();
                if($controller == null && $action == null)
                {
                    $controller = "index";
                    $action= "index";
                    $role = "User"; 
                }
                else{
                    echo "Please add bearer";
                    die;
                }
                if (!$role || true !== $acl->isAllowed($role, $controller, $action)) {
                    die("access denied man if admin change role");
                }
            }





    }
}
}
