<?php
use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;

/**
 * Secure class
 */
class SecureController extends Controller
{

 /**
  * This is used to create and display token 
  *
  * @return void
  */
 public function createTokenAction()
 {

    $id = $this->request->get('role');
    $res = Users::findFirst($id);
    $res= json_decode(json_encode($res));

    // Defaults to 'sha512'
    $signer  = new Hmac();
    
    // Builder object
    $builder = new Builder($signer);
    
    $now        = new DateTimeImmutable();
    $issued     = $now->getTimestamp();
    $notBefore  = $now->modify('-1 minute')->getTimestamp();
    $expires    = $now->modify('+10 day')->getTimestamp();
    $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
    
    // Setup
    $builder
        ->setAudience('https://target.phalcon.io')  // aud
        ->setContentType('application/json')        // cty - header
        ->setExpirationTime($expires)               // exp 
        ->setId('abcd123456789')                    // JTI id 
        ->setIssuedAt($issued)                      // iat 
        ->setIssuer('https://phalcon.io')           // iss 
        ->setNotBefore($notBefore)                  // nbf
        ->setSubject($res->profile)                        // sub
        ->setPassphrase($passphrase)                // password 
    ;
    
    // Phalcon\Security\JWT\Token\Token object
    $tokenObject = $builder->getToken();
    
    // The token
   
    $token =  $tokenObject->getToken();
    echo $token;
  die;
 }
     
 /**
  * BuildACL function
  * Used to build the security file and add permissions
  * @return void
  */
    public function BuildAclAction()
    {
        $aclFile = APP_PATH . '/security/acl.cache';
      
        if (true !== is_file($aclFile)) {

           // The ACL does not exist - build it
            $acl = new Memory();
             
         
        
            echo "<pre>";
            $var = new \App\Components\MyControllers();
            $result = $var->getcontrol();
            echo "<pre>";
        
            foreach ($result as $key => $value) {
                 foreach ($value as $k) {
                 $acl->addComponent(
                   strtolower(str_replace("Controller", "", $key)),
                    [
                       strtolower(str_replace("Action", "", $k))
                    ]
                );
            }
            }

            $var = new Role();
            $res = json_decode(json_encode($var->find())); 
            foreach ($res as $key => $value) {
                $acl->addRole($value->jobProfile);
                if($value->jobProfile == "Admin")
                {
                    $acl->allow($value->jobProfile, "*", "*");
                }
                else if($value->jobProfile == "Manager")
                {
                    $acl->allow($value->jobProfile, "dashboard", "index");
                    $acl->allow($value->jobProfile, "product", "*");
                    $acl->allow($value->jobProfile, "index", "*"); 
                     
                }
                else if($value->jobProfile == "Accountant")
                {
                    $acl->allow($value->jobProfile, "dashboard", "index");
                    $acl->allow($value->jobProfile, "orders", "*");
                    $acl->allow($value->jobProfile, "index", "*");   
                }
                
            }
            $acl->addComponent(
                "index",
                 [
                    "index"
                 ]
             );
            $acl->addRole("User");
            $acl->allow("User", "index", "index"); 
       

           
    
        

            // Store serialized list into plain file
            file_put_contents(
                $aclFile,
                serialize($acl)
            );
        }
    }
}