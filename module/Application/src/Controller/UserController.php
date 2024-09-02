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

class UserController extends BaseController
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
    public function loginAction(){
      
        try{
            $this->setCorsHeaders();
            $data = $this->getRequest()->getPost();
             // $request = $this->getRequest()->getContent();
           // $data = json_decode($request, true);
            
            $result =  $this->table->loginCheck($data['email'],$data['password']);
            if(!empty($result)){
                if($result->role == "Admin") {
                    $data = ['id' => $result->id,'name'=> $result->name,'email'=>$result->email];
                    $token = $this->jwtService->generateToken($data);
                    $this->getResponse()->setStatusCode(200);
                    return new JsonModel([
                        "success" => true,
                        "message" => "Logged In sucessfully",
                        "status_code" => 200,
                        "data" => null,
                        "token" => $token,
                    ]);
                }else{
                    $this->getResponse()->setStatusCode(401);
                    return new JsonModel([
                        "success" => false,
                        'message' => "Unauthendicated...!",
                        "error_code" => 401,
                        "data" => null,
                    ]);
                }
            }else{
                $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                    "success" => false,
                    'message' => "Email Id or Password Incorrect...!",
                    "error_code" => 401,
                    "data" => null,
                ]);
            }
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                "success" => false,
                'message' => "Some thing went wrong...!",
                "error_code" => 500,
                "data" => $e,
            ]);
        }
    }
    public function listAction()
    {
        try{
             $this->setCorsHeaders();
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                //$this->getResponse()->setStatusCode(401);
                return new JsonModel([
                    "success" => false,
                    'message' => $result['message'],
                    "error_code" => 401,
                    "data" => null,
                ]);
            }
            $users = $this->table->fetchAll();
            if(!$users){
                $this->getResponse()->setStatusCode(200);
                return new JsonModel([
                    "success" => true,
                    'message' => "No user Found...!",
                    "status_code" => 200,
                    "data" => null,
                ]);
            }
            return new JsonModel([
                "success" => true,
                'message' => "Users Found...!",
                "status_code" => 200,
                'data' => $users->toArray()
            ]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModels([
               "success" => false,
                'message' => "Some thing went wrong...!",
                "error_code" => 500,
                "data" => $e,
            ]);
        }
    }

    public function getAction()
    {
        try{
            $this->setCorsHeaders();
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                    "success" => false,
                    'message' => $result['message'],
                    "error_code" => 401,
                    "data" => null,
                ]);
            }
            $id = $this->params()->fromRoute('id');
            $user = $this->table->getUser($id);
            if(empty($user)){
                $this->getResponse()->setStatusCode(404);
                return new JsonModel([
                    "success" => false,
                    'message' => "User Not found..!",
                    "error_code" => 404,
                    "data" => null,
                ]);
            }
             $this->getResponse()->setStatusCode(200);
            return new JsonModel([
                    "success" => true,
                    'message' => "User found..!",
                    "status_code" => 200,
                    'data' => $user
            ]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                "success" => false,
                'message' => "Some thing went wrong...!",
                "error_code" => 500,
                "data" => $e,
            ]);
        }
      
    }

    public function createAction()
    {
        try{
            $this->setCorsHeaders();
            $data = $this->getRequest()->getPost();
            //$data = json_decode($request, true);
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
                    "success" => false,
                    'message' => "Validation Errors...!",
                    "error_code" => 400,
                    "data" => $validationmsg,
                ]);
            }
            
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                    "success" => false,
                    'message' => $result['message'],
                    "error_code" => 401,
                    "data" => null,
                ]);
            }
            
            $user = $this->table->getUserbyEmail($data['email']);
            if($user){
               $this->getResponse()->setStatusCode(409);
                return new JsonModel([
                    "success" => false,
                    'message' => "Email ID Already Exsist...!",
                    "error_code" => 409,
                    "data" => null,
                ]);
            }
            $result =  $this->table->saveUser($data);
            $this->getResponse()->setStatusCode(201);
            return new JsonModel([
                "success" => true,
                'message' => "User Created Successfully..!",
                "status_code" => 201,
                "data" => $result,
            ]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                "success" => false,
                'message' => "Some thing went wrong...!",
                "error_code" => 500,
                "data" => $e,
            ]);
        }
    }

    public function updateAction()
    {
        try{
            // $request = $this->getRequest()->getContent();
            // $data = json_decode($request, true);
            $this->setCorsHeaders();
            $data = $this->getRequest()->getPost();
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
                    "success" => false,
                    'message' => "Validation Errors...!",
                    "error_code" => 400,
                    "data" => $validationmsg,
                ]);
            }
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                    "success" => false,
                    'message' => $result['message'],
                    "error_code" => 401,
                    "data" => null,
                ]);
            }
            $id = $data['id'];
            $user = $this->table->getUser($id);
            if(!$user){
                $this->getResponse()->setStatusCode(404);
                return new JsonModel([
                    "success" => false,
                    'message' => "User Not Found..!",
                    "error_code" => 400,
                    "data" => null,
                ]);
            }
            //$user->exchangeArray($data);
            $this->table->saveUser($data);
            $this->getResponse()->setStatusCode(200);
            return new JsonModel([
                "success" => true,
                'message' => "User updated..!",
                "status_code" => 200,
                'data' => $user
            ]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                "success" => false,
                'message' => "Some thing went wrong...!",
                "error_code" => 500,
                "data" => $e,
            ]);
        }
    }

    public function deleteAction() {
        try {
            $result = $this->jwtService->checkExp($this->getRequest());
            if(!empty($result)){
                 $this->getResponse()->setStatusCode(401);
                return new JsonModel([
                    "success" => false,
                    'message' => $result['message'],
                    "error_code" => 401,
                    "data" => null,
                ]);
            }
            $id = $this->params()->fromRoute('id');
            $user = $this->table->getUser($id);
            if(empty($user)){
                $this->getResponse()->setStatusCode(404);
                return new JsonModel([
                    "success" => false,
                    'message' => "User Not Found...!",
                    "error_code" => 404,
                    "data" => null,
                ]);
            }
            $this->table->deleteUser($id);
            $this->getResponse()->setStatusCode(200);
            return new JsonModel([
                "success" => true,
                'message' => "User deleted...!",
                "status_code" => 200,
                "data" => null,
            ]);
        }catch(\Exception $e) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel([
                "success" => false,
                'message' => "Some thing went wrong...!",
                "error_code" => 500,
                "data" => $e,
            ]);
        }
       
    }
    
}
