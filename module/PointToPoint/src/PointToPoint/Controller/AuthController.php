<?php
/**
 * This is for Authentication purpose. Mohd Emadullah
 */

namespace PointToPoint\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container;

class AuthController extends AbstractActionController
{
    protected $authservice;
    
    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }
        return $this->authservice;
    }
    
    public function loginAction()
    {
        $userTable = $this->getServiceLocator()->get('UserTable');
        //if already login, redirect to success page
        if ($this->getAuthService()->hasIdentity()){
            //return $this->redirect()->toRoute('success');
        }
                        
        $request = $this->getRequest();
        
	if($request->isPost())
        {
            $data = $request->getPost();
            //$userData = $userTable->getLoginUser($data['login_user'], $data['login_pass']);
            $encyptPass = $data['login_pass'];            
            $this->getAuthService()->getAdapter()
                    ->setIdentity($data['login_user'])
                    ->setCredential($encyptPass);
            
            $result = $this->getAuthService()->authenticate();
            
            if ($result->isValid())
            {
                //authentication success
                $resultRow = $this->getAuthService()->getAdapter()->getResultRowObject();
                
                                
                $this->getAuthService()->getStorage()->write(array(
                    'id'=> $resultRow->id,
                    'user_agent'    => $request->getServer('HTTP_USER_AGENT'),
                    'username'   => $data['username'],
                    'ip_address' => $this->getRequest()->getServer('REMOTE_ADDR'),
                        ));
                $user_session = new Container('User');
		$user_session->username = $resultRow->login_name;
                $user_session->mobileNo = $resultRow->user_no;
                $user_session->id = $resultRow->id;
                $this->flashMessenger()->addSuccessMessage(array('alert alert-success'=>'Logged in successfully!'));
                $this->redirect()->toRoute('default',array('controller'=>'index','action'=>'point'));
            }else{
                $this->flashMessenger()->addErrorMessage(array('alert alert-danger'=>'User name/Password are not correct!'));
                $this->redirect()->toRoute('default',array('controller'=>'index','action'=>'point'));
            }
	}       
    }   
    
    public function logoutAction()
    {
        $session = new Container('User');
        $session->getManager()->destroy();
        $this->getAuthService()->clearIdentity();
        $this->flashMessenger()->addInfoMessage(array('alert alert-info'=>'Logout successfully!'));
        return $this->redirect()->toRoute('default',array('controller'=>'index','action'=>'point'));
    }
}
