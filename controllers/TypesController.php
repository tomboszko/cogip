<?php

require_once __DIR__ . '/../models/TypesModel.php';
require_once __DIR__ . '/../models/ErrorModel.php';

class TypesController{
    private $model;

    public function __construct($pdo){
        $this->model = new TypeModel($pdo);
    }

    public function getAllTypes(){
        $errorModel = new ErrorModel();
        try{
            $type = $this->model->getAllTypes();
            header('Content-Type: application/json');
            if($type){
                echo json_encode([
                    'status' => 200,
                    'data' => $type], JSON_PRETTY_PRINT);
            }else{
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Type not found']);
            }
        }catch(Exception $e){
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }
}