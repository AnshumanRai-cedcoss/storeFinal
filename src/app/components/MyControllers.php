<?php 
namespace App\Components;

/**
 * Controller class
 */
class MyControllers
{ 
    
    /**
     * getcontrol function
     *this returns all the controllers and actions in the code
     * @return array of object
     */
    public function getcontrol()
    {
       
        $controllers = [];

        foreach (glob(APP_PATH . '/controllers/*Controller.php') as $controller) {
            // echo $controller;
            $className = basename($controller, '.php');
            $controllers[$className] = [];
            $methods = (new \ReflectionClass($className))->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if (\Phalcon\Text::endsWith($method->name, 'Action')) {
                    $controllers[$className][] = $method->name;
                }
            }
        }
        return $controllers;
    }


}

