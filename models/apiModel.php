<?php

// function checkApiKey() {
//     // Retrieve the 'api_key' query parameter
//     $apiKey = isset($_GET['api_key']) ? $_GET['api_key'] : null;

//     // Get the stored API key
//     $storedApiKey = getenv('API_KEY');

//     // Check if the provided API key matches the stored API key
//     if ($apiKey !== $storedApiKey) {
//         // If not, return an error response
//         http_response_code(403);
//         echo json_encode(['error' => 'Invalid API key']);
//         exit;  // Stop the script execution
//     }
// }

// Then, in each route handler, call the checkApiKey function at the start:

// $router->get('/invoices', function() use ($invoicesController) {
//     checkApiKey();

//     // ... rest of your code ...
// });

// $router->post('/invoices', function() use ($invoicesController) {
//     checkApiKey();

//     // ... rest of your code ...
// });

// // Do the same for all other routes