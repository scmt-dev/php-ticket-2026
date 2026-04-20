<?php 
require_once '../db.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

## API User
switch ($method) {
    case 'POST':
        # Register
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data['name'] = $json['name'] ?? null;
        $data['email'] = $json['name'] ?? null;
        $data['password'] = $json['password'] ?? '';
        $data['confirm_password'] = $json['confirm_password'] ?? '';
        $confirm = $data['password'] === $data['confirm_password'];
        if(!$confirm) {
            http_response_code(400);
            echo json_encode(['message' => 'password fail']);
            exit;
        }

        $has = password_hash($data['password'], PASSWORD_DEFAULT);
        break;
    case 'PUT':
        break;
    case 'GET':
        $sql = 'select id,name,email from users limit 10';
        $rows = $db->query($sql);
        $data = $rows->fetch_all(MYSQLI_ASSOC);
        echo json_encode([
            'data' => $data
        ]);
        break;
    default:
        break;
}