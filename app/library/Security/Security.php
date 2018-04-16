<?php
/**
 * Created by PhpStorm.
 * User: vladislav
 * Date: 12/06/14
 * Time: 14:05
 */
namespace Library\Security;

use \Phalcon\Events\Event,
   \Phalcon\Mvc\User\Plugin,
   \Phalcon\Mvc\Dispatcher,
   \Phalcon\Acl;


class Security extends Plugin
{

   public function __construct($dependencyInjector)
   {
       $this->_dependencyInjector = $dependencyInjector;
   }

	public function getAcl()
    {
		unset($this->persistent->acl);
		unset($_SESSION['Security']['acl']);


		if (!isset($this->persistent->acl)) {
			$acl = new Acl\Adapter\Memory();

			$acl->setDefaultAction(Acl::DENY);


			$roles = array(
				'users' => new Acl\Role('Users'),
				'guests' => new Acl\Role('Guests')
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}


            $publicControllers = array(
                'index' => '*',
                'payment' => '*',
                'cryptopay' => '*',
                'error' => '*',
            );
            foreach ($publicControllers as $resource => $actions) {
                $acl->addResource(new Acl\Resource($resource), $actions);
            }

            foreach ($roles as $role) {
                foreach ($publicControllers as $resource => $actions) {
                    if($actions == '*'){
                        $acl->allow($role->getName(), $resource, '*');
                    }else {
                        foreach ($actions as $action) {
                            $acl->allow($role->getName(), $resource, $action);
                        }
                    }
                }
            }

			$this->persistent->acl = $acl;
		}
		return $this->persistent->acl;
	}

       public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {


           $user = $this->session->get('userId');

           if (!$user) {
               $role = 'Guests';
           } else {
               $role = 'Users';
           }



           $controller = $dispatcher->getControllerName();
           $action = $dispatcher->getActionName();
           $acl = $this->getAcl();

            $allowed = $acl->isAllowed($role, $controller, $action);

            if ($allowed != Acl::ALLOW) {

                $dispatcher->forward(
                    array(
                        'controller' => 'user',
                        'action' => 'login'
                    ));
                return false;
            }

       }

       public function beforeException(Event $event, Dispatcher $dispatcher,  $exception)
       {

           switch ($exception->getCode()) {
               case 0:

                   $dispatcher->forward(array(
                       'controller' => 'error',
                       'action' => 'pageIsNotResponding',
                       'params' => array('exception' => $exception, 'controllerName'=>$dispatcher->getControllerName(), 'actionName' => $dispatcher->getActionName())
                   ));

                   return $event->isStopped();
               break;
               case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
               case \Phalcon\Dispatcher::EXCEPTION_INVALID_HANDLER:
               case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
               case \Phalcon\Dispatcher::EXCEPTION_INVALID_PARAMS:

	                $dispatcher->forward(array(
	                    'controller' => 'error',
	                    'action' => 'pageNotFound',
	                    'params' => array('exception' => $exception)
	                ));

                   return $event->isStopped();
               break;
               default:

               break;
           }
       }

}