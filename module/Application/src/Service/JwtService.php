<?php
namespace Application\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
class JwtService
{
    private $secretKey = 'EUgACEfBP1LYkpOnJSKUtCSQxZgl9kof'; 

    public function generateToken(array $data)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // jwt valid for 1 hour from the issued time
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data,
        );

      return  JWT::encode($payload, $this->secretKey, 'HS256');
    }

    public function verifyToken($token)
    {

        $headers = new \stdClass();
        try {
            $decoded_token = JWT::decode($token, new Key( $this->secretKey, 'HS256'), $headers);
            return $decoded_token;
        } catch (\Exception $e) {
             return $e->getMessage();
        }
    }
    public function checkExp($request) {
         $token = $request->getHeader('Authorization');
             if(!$token){
               return ['message' => 'Unauthorized...!'];
             }
             $token = explode(' ',$token->getFieldValue())[1];
             
             $userData = $this->verifyToken($token);
            if ($userData === null) {
                return ['message' => 'Unauthorized...!'];
            }
            if($userData == "Expired token"){
                return['message' => 'Token Expired...!'];
            }
            return null;
    }
}
