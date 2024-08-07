<?php 
// module/Application/src/Controller/UserController.php

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Application\Model\User;
use Application\Model\UserTable;
use Laminas\Http\Response;
use Application\Service\JwtService;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\StringLength;
use Laminas\Validator\Digits;
use Laminas\Http\Header\SetCookie;

class UserController extends AbstractActionController
{
    private $table;
    private $jwtService;
    public function __construct(UserTable $table, JwtService $jwtService)
    {
        $this->table = $table;
        $this->jwtService = $jwtService;
        $this->nameValidator = new NotEmpty();
        $this->idValidator = new NotEmpty();
        $this->emailValidator = new EmailAddress();
        $this->passwordValidator = new StringLength(['min' => 6]);
        $this->roleValidator = new NotEmpty();
    }

    public function listAction()
    {
        try{
            $response = $this->getResponse();
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                 $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                'message' => $result['message'],
                ]);
            }
            $users = $this->table->fetchAll();
            if(!$users){
                $this->getResponse()->setStatusCode(200);
                return new JsonModel([
                'message' => "No user Found...!",
                ]);
            }
            return new JsonModel(['data' => $users->toArray()]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModels([
                'message' => "Some thing went wrong...!",
                'error' => $e
            ]);
        }
    }

    public function getAction()
    {
        try{
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                 $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                'message' => $result['message'],
                ]);
            }
            $id = $this->params()->fromRoute('id');
            $user = $this->table->getUser($id);
            if(empty($user)){
                $this->getResponse()->setStatusCode(404);
                return new JsonModel(['message' => "User Not found..!"]);
            }
             $this->getResponse()->setStatusCode(200);
            return new JsonModel(['message' => "User found..!",'data' => $user]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                'message' => "Some thing went wrong...!",
                'error' => $e
            ]);
        }
      
    }

    public function createAction()
    {
        try{
            $request = $this->getRequest()->getContent();
            $data = json_decode($request, true);
            $validationerr = false;
            $validationmsg = array();
           
            $nameIsValid = $this->nameValidator->isValid($data['name']);
            $emailIsValid = $this->emailValidator->isValid($data['email']);
            $passwordIsValid = $this->passwordValidator->isValid($data['password']);
            $roleIsValid = $this->roleValidator->isValid($data['role']);
           
            if(!$nameIsValid){
                $validationerr = true;
                array_push($validationmsg, 'Please Enter the name..!');
            }
            if(!$emailIsValid){
                $validationerr = true;
                array_push($validationmsg,'Please Enter Valid Email ..!');
            }
            if(!$passwordIsValid){
                $validationerr = true;
               array_push($validationmsg,'Password must not be empty, be at least 6 characters long..!');
            }
            if(!$roleIsValid){
                $validationerr = true;
                array_push($validationmsg,'Please Enter Role ..!');
            }
            
            if($validationerr){
                
                $this->getResponse()->setStatusCode(400);
                return new JsonModel([
                    'message' => 'Validation Errors',
                    'Errors' => $validationmsg,
                ]);
            }
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                 $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                'message' => $result['message'],
                ]);
            }
            
            $user = $this->table->getUserbyEmail($data['email']);
            if($user){
                $this->getResponse()->setStatusCode(409);
                return new JsonModel([
                    'message' => "Email ID Already Exsist...!",
                ]);
            }
            $user = new User();
            $user->exchangeArray($data); 
           
            $result =  $this->table->saveUser($user);
            return new JsonModel(['result' => $result, "message"=>"User Created Successfully..!"]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                'message' => "Some thing went wrong...!",
                'error' => $e
            ]);
        }
    }

    public function updateAction()
    {
        try{
            $request = $this->getRequest()->getContent();
            $data = json_decode($request, true);
            $validationerr = false;
            $validationmsg = array();
           
            $nameIsValid = $this->nameValidator->isValid($data['name']);
            $emailIsValid = $this->emailValidator->isValid($data['email']);
            $roleIsValid = $this->roleValidator->isValid($data['role']);
            $idIsValid = $this->idValidator->isValid($data['id']);
            if (!$idIsValid) {
                $validationerr = true;
                array_push($validationmsg, 'Please Valid User Id..!');
            }
            if(!$nameIsValid){
                $validationerr = true;
                array_push($validationmsg, 'Please Enter the name..!');
            }
            if(!$emailIsValid){
                $validationerr = true;
                array_push($validationmsg,'Please Enter Valid Email ..!');
            }
            if(!$roleIsValid){
                $validationerr = true;
                array_push($validationmsg,'Please Enter Role ..!');
            }
            if($validationerr){
                $this->getResponse()->setStatusCode(400);
                return new JsonModel([
                    'message' => 'Validation Errors',
                    'Errors' => $validationmsg,
                ]);
            }
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                 $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                'message' => $result['message'],
                ]);
            }
            $id = $data['id'];
            $user = $this->table->getUser($id);
            if(!$user){
              $this->getResponse()->setStatusCode(404);
                return new JsonModel([
                    'message' => "User Not Found..!",
                ]);
            }
            $user->exchangeArray($data);
            $this->table->saveUser($user);
            return new JsonModel(['data' => $user]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                'message' => "Some thing went wrong...!",
                'error' => $e
            ]);
        }
    }

    public function deleteAction() {
        try {
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                 $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                'message' => $result['message'],
                ]);
            }
            $id = $this->params()->fromRoute('id');
            $user = $this->table->getUser($id);
            if(empty($user)){
                $this->getResponse()->setStatusCode(404);
                return new JsonModel([
                'message' => 'User Not Found...!',
                ]);
            }
            $this->table->deleteUser($id);
            $this->getResponse()->setStatusCode(200);
            return new JsonModel(['message' => 'User deleted']);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                'message' => "Some thing went wrong...!",
                'error' => $e
            ]);
        }
       
    }
    public function loginAction(){
      
        try{
            // $data = $this->params()->fromPost();
              $request = $this->getRequest()->getContent();
            $data = json_decode($request, true);
            $result =  $this->table->loginCheck($data['email'],$data['password']);
            if(!empty($result)){
                if($result->role == "admin") {
                    $data = ['id' => $result->id,'name'=> $result->name,'email'=>$result->email];
                    $token = $this->jwtService->generateToken($data);
                    $this->getResponse()->setStatusCode(200);
                    return new JsonModel([
                        'message' => "Logged In Successfully..!",
                        'JWT' => $token
                    ]);
                }else{
                    $this->getResponse()->setStatusCode(401);
                    return new JsonModel([
                        'message' => "Unauthendicated...!",
                    ]);
                }
            }else{
                $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                    'message' => "Email Id or Password Incorrect...!",
                ]);
            }
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                'message' => "Some thing went wrong...!",
                'error' => $e
            ]);
        }
    }
}
