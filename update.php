<?php
// SET HEADER
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: PUT");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, ageization, X-Requested-With");

// INCLUDING DATABASE AND MAKING OBJECT
require 'database.php';
$db_connection = new Database();
$conn = $db_connection->dbConnection();

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

//CHECKING, IF ID AVAILABLE ON $data
if(isset($data->id)){
    
    $msg['message'] = '';
    $post_id = $data->id;
    
    //GET POST BY ID FROM DATABASE
    $get_post = "SELECT * FROM `posts` WHERE id=:post_id";
    $get_stmt = $conn->prepare($get_post);
    $get_stmt->bindValue(':post_id', $post_id,PDO::PARAM_INT);
    $get_stmt->execute();
    
    
    //CHECK WHETHER THERE IS ANY POST IN OUR DATABASE
    if($get_stmt->rowCount() > 0){
        
        // FETCH POST FROM DATBASE 
        $row = $get_stmt->fetch(PDO::FETCH_ASSOC);
        
        // CHECK, IF NEW UPDATE REQUEST DATA IS AVAILABLE THEN SET IT OTHERWISE SET OLD DATA

        $post_contactno= isset($data->contactno) ? $data->contactno : $row['contactno'];
        $post_qrid= isset($data->qrid) ? $data->qrid : $row['qrid'];

        $post_fullname = isset($data->fullname) ? $data->fullname : $row['fullname'];
        $post_sex = isset($data->sex) ? $data->sex  : $row['sex'];

        $post_brgy = isset($data->brgy) ? $data->brgy : $row['brgy'];
        $post_age = isset($data->age) ? $data->age : $row['age'];

        $post_decision = isset($data->decision) ? $data->decision : $row['decision'];
        $post_usertype = isset($data->usertype) ? $data->usertype : $row['usertype'];

        $post_img = isset($data->img) ? $data->img : $row['img'];

        $post_qrcode = isset($data->qrcode) ? $data->qrcode : $row['qrcode'];
        $post_jcinfo = isset($data->jcinfo) ? $data->jcinfo : $row['jcinfo'];
        $post_jqna = isset($data->jqna) ? $data->jqna : $row['jqna'];

        $post_footprint = isset($data->footprint) ? $data->footprint: $row['footprint'];
        
        $update_query = "UPDATE `posts` SET contactno = :contactno, qrid = :qrid, fullname = :fullname, sex = :sex, brgy = :brgy, age = :age , decision = :decision, usertype = :usertype, img = :img,  qrcode = :qrcode, jcinfo = :jcinfo, jqna = :jqna, footprint = :footprint
        WHERE id = :id";
        
        $update_stmt = $conn->prepare($update_query);
        
        // DATA BINDING AND REMOVE SPECIAL CHARS AND REMOVE TAGS
        $update_stmt->bindValue(':qrid', htmlspecialchars(strip_tags($post_qrid)),PDO::PARAM_STR);

        $update_stmt->bindValue(':contactno', htmlspecialchars(strip_tags($post_contactno)),PDO::PARAM_STR);

        $update_stmt->bindValue(':fullname', htmlspecialchars(strip_tags($post_fullname)),PDO::PARAM_STR);

        $update_stmt->bindValue(':sex', htmlspecialchars(strip_tags($post_sex)),PDO::PARAM_STR);

        $update_stmt->bindValue(':brgy', htmlspecialchars(strip_tags($post_brgy)),PDO::PARAM_STR);
        $update_stmt->bindValue(':age', htmlspecialchars(strip_tags($post_age)),PDO::PARAM_STR);

        $update_stmt->bindValue(':decision', htmlspecialchars(strip_tags($post_decision)),PDO::PARAM_STR);
        $update_stmt->bindValue(':usertype', htmlspecialchars(strip_tags($post_usertype)),PDO::PARAM_STR);

        $update_stmt->bindValue(':img', htmlspecialchars(strip_tags($post_img)),PDO::PARAM_STR);
     
        $update_stmt->bindValue(':qrcode', htmlspecialchars(strip_tags($post_qrcode)),PDO::PARAM_STR);
        $update_stmt->bindValue(':jcinfo', htmlspecialchars(strip_tags($post_jcinfo)),PDO::PARAM_STR);
        $update_stmt->bindValue(':jqna', htmlspecialchars(strip_tags($post_jqna)),PDO::PARAM_STR);
        $update_stmt->bindValue(':footprint', htmlspecialchars(strip_tags($post_footprint)),PDO::PARAM_STR);
        
        $update_stmt->bindValue(':id', $post_id,PDO::PARAM_INT);
        
        
        if($update_stmt->execute()){
            $msg['message'] = 'Data updated successfully';
        }else{
            $msg['message'] = 'data not updated';
        }   
        
    }
    else{
        $msg['message'] = 'Invalid ID';
    }  
    
    echo  json_encode($msg);
    
}
?>