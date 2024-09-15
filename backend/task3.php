<?php
//original code
$data = [];
foreach ($ids as $id) {
    $result = $connection->query("SELECT `x`, `y` FROM `values` WHERE `id` = " . $id);
    $data[] = $result->fetch_row();
}

// For the start, I would rewrite it with PDO for future interaction
// It will be enough to replace ->fetch_row() with ->fetch() in this example of code

// The problem is in the multiple connections to the database, as we create new connection each time
// For solution we can use 'IN' instead of 'foreach'
$ids = implode(',', $ids);
$data[] = $connection->query("SELECT `x`, `y` FROM `values` WHERE `id` IN (" . $ids . ")")->fetch_all();

// Also the problem is possible SQL injections, so it would be better to use prepared queries
// Of course would be easier to do it with only one query
// prepare the query
$stmt = $connection->prepare("SELECT `x`, `y` FROM `values` WHERE `id` = :id");
$stmt->execute([':id' => $id]);
$data[] = $stmt->fetch();
// But in this case we have already chosen to use IN instead of foreach
$idsList = implode(',', array_fill(0, count($ids), '?'));

$stmt = $connection->prepare("SELECT `x`, `y` FROM `values` WHERE `id` IN ($idsList)");
$stmt->execute($ids);

$data = $stmt->fetchAll();

// Anyway it would be better to add a check
if (!empty($ids)) {
}
// I would write some features like logging PDO errors, so I will know where is the problem
// Also we can add PDO::FETCH_NUM to fix the format of response

// If we expect to work with big data I would request data with chunks
// for example
$chunkSize = 1000;
$chunks = array_chunk($ids, $chunkSize);
foreach ($chunks as $chunk) {
    // previous code with request preparation
}