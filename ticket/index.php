<?php

require_once '../db.php';

$sql = "select t.*, u.name as user_name from tickets t 
left join users u on t.user_id = u.id order by t.created_at desc";
$result = $db->query($sql);
// display tickets
echo "<h1>Tickets</h1>";
echo "<a href='create.php'>Create New Ticket</a>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr>";
  echo "<th>Title</th>";
  echo "<th>Description</th>";
  echo "<th>Status</th>";
  echo "<th>Created At</th>";
  echo "<th>Updated At</th>";
  echo "<th>User</th>";
echo "</tr>";
while($row = $result->fetch_assoc()) {
  echo "<tr>";
    echo "<td>".$row['title']."</td>";
    echo "<td>".$row['description']."</td>";
    echo "<td>".$row['status']."</td>";
    echo "<td>".$row['created_at']."</td>";
    echo "<td>".$row['updated_at']."</td>";
    echo "<td>".$row['user_name']."</td>";
    echo "<td><a href='entry.php?id=".$row['id']."'>Edit</a> 
    | <a href='delete.php?id=".$row['id']."' 
    onclick='return confirm(\"Are you sure?\")'>Delete</a></td>";
  echo "</tr>";
}
echo "</table>";

?>