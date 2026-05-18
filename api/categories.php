<?php
require_once '../config.php';
require_once '../db.php';

 $method = $_SERVER['REQUEST_METHOD'];
 $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

switch ($method) {
    case 'GET':
        if ($id) {
            $stmt = $db->prepare('SELECT * FROM categories where id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $row ? json_response($row) : json_response(['error' => 'Category not found'], 404);
        } else {
            $result = $db->query('SELECT * FROM categories ORDER BY id');
            json_response([
                'categories' => $result->fetch_all(MYSQLI_ASSOC)
            ]);
        }
        break;

    case 'POST':
        $data = get_input();
        if (empty($data['name']) || empty($data['icon'])) {
            json_response(['error' => 'Fields name and icon are required'], 422);
        }
        $stmt = $db->prepare('INSERT INTO categories (name, icon) VALUES (?, ?)');
        $stmt->bind_param('ss', $data['name'], $data['icon']);
        if ($stmt->execute()) {
            json_response(['id' => $stmt->insert_id, 'name' => $data['name'], 'icon' => $data['icon']], 201);
        } else {
            json_response(['error' => 'Insert failed — name may already exist'], 500);
        }
        break;

    case 'PUT':
        if (!$id) json_response(['error' => 'ID is required'], 422);
        $data = get_input();
        $stmt = $db->prepare('UPDATE categories SET name = ?, icon = ? WHERE id = ?');
        $stmt->bind_param('ssi', $data['name'], $data['icon'], $id);
        $stmt->execute();
        $stmt->affected_rows > 0
            ? json_response(['message' => 'Category updated'])
            : json_response(['error' => 'Category not found or no changes'], 404);
        break;

    case 'DELETE':
        if (!$id) json_response(['error' => 'ID is required'], 422);
        $stmt = $db->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->affected_rows > 0
            ? json_response(['message' => 'Category deleted'])
            : json_response(['error' => 'Category not found'], 404);
        break;

    default:
        json_response(['error' => 'Method not allowed'], 405);
}