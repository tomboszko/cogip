<?php

$host = getenv('DB_HOST');
$db   = getenv('DB_DATABASE');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Mapping of contact IDs to their image files
$contactImages = [
    5 => 'IMG/05.png',
    // 1 => '/path/to/image1.jpg',
    // 2 => '/path/to/image2.jpg',
    // ... more contacts
];

foreach ($contactImages as $contactId => $imagePath) {
    // Read the image file
    $imageData = file_get_contents($imagePath);
    // Convert the image data to a format that can be stored in the database
    $imageData = base64_encode($imageData);

    // Prepare an UPDATE statement
    $stmt = $pdo->prepare("UPDATE contacts SET Avatar = :imageData WHERE id = :contactId");

    // Bind the image data and contact ID to the statement
    $stmt->bindParam(':imageData', $imageData, PDO::PARAM_LOB);
    $stmt->bindParam(':contactId', $contactId, PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();
}
?>
