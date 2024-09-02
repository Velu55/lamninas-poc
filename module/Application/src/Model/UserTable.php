<?php 
namespace Application\Model;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Db\ResultSet\ResultSet;

class UserTable
{
    protected $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getUser($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        return $rowset->current();
    }
    public function getUserbyEmail($email)
    {
        $email = (string) $email;
        $rowset = $this->tableGateway->select(['email' => $email]);
        return $rowset->current();
    }
    public function saveUser($data)
    {
        
        if(!isset($data['id'])){
          
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $data = [
                'name'  => $data['name'],
                'email' => $data['email'],
                'password' => $hashedPassword,
                'role' => $data['role']
            ];    
           return $this->tableGateway->insert($data);
        } else {
            if ($data['id']) {
                $id = $data['id'];
                  $data = [
                    'name'  => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['role']
                ];    
               return $this->tableGateway->update($data, ['id' => $id]);
            } else {
                throw new \Exception('User ID does not exist');
            }
        }
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }

    public function loginCheck($email, $pass){
        $email = (string) $email;
        $pass =  $pass;
        $rowset = $this->tableGateway->select(['email' => $email]);
        $user = $rowset->current();
        if(!$user){
            return null;
        }
        
        if (password_verify($pass, $user->password)) {
            return $user;
        } else {
            return null;
        }
    }
}
