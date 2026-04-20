<?php 

header('Content-Type: application/json');

$data = [
    'version' => '1.0',
    'message' => 'Hello, World!',
    'status' => 'success'
];
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'POST':
        // read the raw input data
        $input = file_get_contents('php://input');
        // decode the JSON data
        $json = json_decode($input, true);
        $data['name'] = $json['name'] ?? 'Unknown';
        echo json_encode([
            'method' => 'POST',
            'message' => 'Data received successfully',
            'data' => $data
        ]);
        break;
    case 'GET':
        echo json_encode([
            'method' => 'GET',
            'message'=> 'Data retrieved successfully',
            'data' => $data
        ]);
        break;
    default:
        echo json_encode($data);
        break;
}