<?php
include 'db_connection.php'; // Connect to SQLite

$filter_date = $_GET['date'] ?? '';
$filter_name = $_GET['name'] ?? '';
$filter_class = $_GET['class'] ?? '';
$limit = 10; 
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM faculty_checkins WHERE 1=1";
$params = [];

// Prepare the query with parameters
if ($filter_date) {
    $query .= " AND checkin_date = :checkin_date";
    $params[':checkin_date'] = $filter_date;
}
if ($filter_name) {
    $query .= " AND name LIKE :name";
    $params[':name'] = '%' . $filter_name . '%';
}
if ($filter_class) {
    $query .= " AND class = :class";
    $params[':class'] = $filter_class;
}

$query .= " ORDER BY checkin_date DESC, checkin_time DESC LIMIT :limit OFFSET :offset";

// Prepare and execute the statement
$stmt = $db->prepare($query);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total records for pagination
$total_result = $db->query("SELECT COUNT(*) as total FROM faculty_checkins")->fetch();
$total_pages = ceil($total_result['total'] / $limit);

// Return JSON response
header('Content-Type: application/json');
echo json_encode(["data" => $data, "total_pages" => $total_pages]);
?>