<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, ageization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CREATE MESSAGE ARRAY AND SET EMPTY
$msg['message'] = '';

// CHECK IF RECEIVED DATA FROM THE REQUEST
if(isset($data->contactno) && isset($data->fullname) && isset($data->sex) && isset($data->brgy) && isset($data->age)  &&
         ($data->decision) && isset($data->usertype) && isset($data->img) &&
         ($data->qrcode) && isset($data->jcinfo) && isset($data->jqna) && isset($data->footprint)
){
    // CHECK DATA VALUE IS EMPTY OR NOT
    if(!empty($data->contactno) && !empty($data->qrid)  && !empty($data->fullname) && !empty($data->sex) && !empty($data->brgy) && !empty($data->age)  &&
    !empty($data->decision) && !empty($data->usertype) && !empty($data->img)
    ){
        
        $insert_query = "INSERT INTO `posts`(contactno,qrid,fullname,sex,brgy,age,decision,usertype,img,qrcode,jcinfo,jqna,footprint) 
                         VALUES(:contactno,:qrid,:fullname,:sex,:brgy,:age,:decision,:usertype,:img,:qrcode,:jcinfo,:jqna,:footprint)";
        
        $insert_stmt = $conn->prepare($insert_query);
        // DATA BINDING
        $insert_stmt->bindValue(':contactno', htmlspecialchars(strip_tags($data->contactno)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':qrid', htmlspecialchars(strip_tags($data->qrid)),PDO::PARAM_STR);

        $insert_stmt->bindValue(':fullname', htmlspecialchars(strip_tags($data->fullname)),PDO::PARAM_STR);
        
        $insert_stmt->bindValue(':sex', htmlspecialchars(strip_tags($data->sex)),PDO::PARAM_STR);

        $insert_stmt->bindValue(':brgy', htmlspecialchars(strip_tags($data->brgy)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':age', htmlspecialchars(strip_tags($data->age)),PDO::PARAM_STR);

        $insert_stmt->bindValue(':decision', htmlspecialchars(strip_tags($data->decision)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':usertype', htmlspecialchars(strip_tags($data->usertype)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':img', htmlspecialchars(strip_tags($data->img)),PDO::PARAM_STR);

        $insert_stmt->bindValue(':qrcode', htmlspecialchars(strip_tags($data->qrcode)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':jcinfo', htmlspecialchars(strip_tags($data->jcinfo)),PDO::PARAM_STR);
        $insert_stmt->bindValue(':jqna', htmlspecialchars(strip_tags($data->jqna)),PDO::PARAM_STR);

        $insert_stmt->bindValue(':footprint', htmlspecialchars(strip_tags($data->footprint)),PDO::PARAM_STR);
        
        if($insert_stmt->execute()){
            $msg['message'] = 'Data save successfully!';
        }else{
            $msg['message'] = 'Data not save check your network';
        } 
        
    }else{
        $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
    }
}
else{
    $msg['message'] = 'Please fill all the fields all required * ';
}
//ECHO DATA IN JSON FORMAT
echo  json_encode($msg);
?>