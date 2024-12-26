<?php
require_once 'config.php';
header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('ID event tidak ditemukan');
    }

    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Event tidak ditemukan');
    }

    $event = $result->fetch_assoc();

    // Ensure proper encoding of special characters
    $event = array_map('utf8_encode', $event);

    echo json_encode([
        'id' => $event['id'],
        'title' => $event['title'],
        'description' => $event['description'],
        'event_date' => $event['event_date'],
        'event_time' => $event['event_time'],
        'image_url' => $event['image_url']
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}

