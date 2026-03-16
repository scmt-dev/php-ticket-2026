<?php
    session_start();

    if(!$_SESSION['user_id']) {
        echo 'Please login first.';
        exit;
    }

    require_once '../db.php';
    // insert 
    $sql = "insert into tickets (title, description, status, user_id) values (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssi", $title, $description, $status, $_SESSION['user_id']);

?>
<form action="" method="post">
    <div>
        Title: 
        <input type="text" name="title" />
    </div>
    <div>
        Description: 
        <textarea name="description"></textarea>
    </div>
    <div>
        Status: 
        <select name="status">
            <option value="open">Open</option>
            <option value="closed">Closed</option>
        </select>
    </div>
    <button type="submit" name="submit">Submit</button>
</form>