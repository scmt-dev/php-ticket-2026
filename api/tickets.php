<?php 

require_once '../db.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

## API Ticket
switch ($method) {
    case 'GET':
        # Get Tickets
        $sql = 'select * from tickets limit 10';
        $result = $db->query($sql);
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
        $sql = 'select count(*) as total from tickets';
        $result = $db->query($sql);
        $total = $result->fetch_assoc()['total'];
        echo json_encode(['message' => 'Tickets retrieved', 
            'total'=>$total,
            'data'=>$tickets
        ]);
        break;
    case 'POST':
        # Create Ticket
        $input = file_get_contents('php://input');
        $json = json_decode($input, true);
        $data = [];
        $data['title'] = $json['title'] ?? null;
        $data['description'] = $json['description'] ?? null;
        $data['status'] = $json['status'] ?? 'open';
        $userId = 1;
        $sql = 'insert into tickets (title, description, status, user_id) values (?, ?, ?, ?)';
        $stmt = $db->prepare($sql);
        $stmt->bind_param('sssi', $data['title'], $data['description'], $data['status'],$userId);
        $stmt->execute();
        echo json_encode(['message' => 'Ticket created', 'data'=>$data]);
        break;
    default:
        http_response_code(400);
        echo json_encode(['message' => 'Invalid request method']);
        break;
}