
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function register(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
      if(empty($data->name)|| empty($data->email) || empty ($data->password))
       {http_response_code(400);
        echo json_encode(['message' => 'All fields are required']);
        return;}
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn-> prepare($query);
        $stmt->bind_param("s", $data -> email);
        $stmt->execute();
       $result= $stmt->get_result();
         if($result->num_rows > 0){
            http_response_code(409);
            echo json_encode(['message'=>'Email already exists']);
            return;
         }
      $hashedPassword = password_hash($data ->password, PASSWORD_BCRYPT);
     $insertquery = " INSERT INTO users  (name, email, password) VALUES (?, ?, ?)";
     $stmt = $conn-> prepare($insertquery);
     $stmt->bind_param("sss", $data->name, $data->email, $hashedPassword);
      $stmt->execute();
      if($stmt->affected_rows > 0){
        http_response_code(201);
        echo json_encode(['message'=> 'User registered']);
      }
      else{
      http_response_code(500);
      echo json_encode(['message'=> 'something went wrong!!']);
      return;
      }

    }


function login(){
    global $conn;
    $data = json_decode(file_get_contents("php://input"));
    if( empty($data->email) || empty($data->password))
        {
        http_response_code(400);
        echo json_encode(['message' => 'All fields are required']);
        return;
    }
     $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn-> prepare($query);
        $stmt->bind_param("s", $data->email);
        $stmt->execute();
        $loginresult = $stmt-> get_result();
        if($loginresult->num_rows> 0){

         $user = $loginresult->fetch_assoc();
        if(password_verify($data->password, $user['Password'])){
            http_response_code(200);
            $secretkey = "focusdesk_super_secret_key_2026_xyz";
            $payload = [ 
                'user_id'=> $user['ID'], 
               'email'=> $user['Email'],
               'name' => $user['Name'],
               'exp'=> time() + 3600   

             ];
             $token = JWT:: encode($payload, $secretkey, 'HS256');

         echo json_encode(['message'=>'User logged in', 'token' => $token]);

        }
        else{
             http_response_code(401);
         echo json_encode(['message'=>'invalid credentials, try again.']);

        }
         
        }

}
?>