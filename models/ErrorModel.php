
<?php
class ErrorModel {
    public function logError($error) {
        // Log the error (for example, write it to a file or send it to an error tracking service)
        error_log($error->getMessage());
    }

    public function sendErrorResponse($error) {
        // Set the HTTP status code to 500 (Internal Server Error)
        http_response_code(500);
        
        // Send an error message as a JSON response
        echo json_encode(['error' => $error->getMessage()]);
    }

    public function sendNotFoundResponse() {
        // Set the HTTP status code to 404 (Not Found)
        http_response_code(404);
        
        // Send an error message as a JSON response
        echo json_encode(['error' => '404 Not found']);
    }

}
