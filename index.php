<!DOCTYPE html>
<html>
<head>
<style>
#a{
  font-family: "Times New Roman", Times, serif;
  border-collapse: collapse;
  width: 100%;
}

#a td, #a th {
  border: 1px solid #ddd;
  padding: 8px;
}

#a th {
  padding-top: 15px;
  padding-bottom: 15px;
  text-align: center;
  background-color: #92a8d1;
  color: 92a8d1;
}
</style>
</head>
<body>
<table id="a">
<tr>
		<th>Car number</th>
		<th>Year made</th>
		<th>Model  </th>
		<th>Manager</th>
		<th>Status</th>
		<th>Previous manager</th>
</tr>

<?php
include_once 'config/connection.php';
$database = new Database();
$db = $database->connect();

    $query = '
	SELECT 
		c.number as car_number,
		c.year_made as year_made,
		c.model as model,
	CONCAT(u.name," ", s.name) as manager,
		st.name as status,
	CONCAT( 
	  (SELECT u.name
       FROM car_management e1
       INNER JOIN users u ON u.id = e1.user_id
       INNER JOIN segments s ON s.id = e1.segments_id 
       WHERE e1.id < car_management.id 
       ORDER BY e1.id 
       DESC LIMIT 1)
       ," ",
       (SELECT s.name
       FROM car_management e1 
       INNER JOIN users u ON u.id = e1.user_id
       INNER JOIN segments s ON s.id = e1.segments_id 
       WHERE e1.id < car_management.id
       ORDER BY e1.id 
       DESC LIMIT 1)
       ) as previous_manager
	FROM car_management 
 	INNER JOIN cars c ON c.id  = car_management.cars_id
    INNER JOIN segments s ON s.id = car_management.segments_id
    INNER JOIN users u ON u.id  = car_management.user_id
    INNER JOIN car_status ct ON c.id = ct.cars_id
    INNER JOIN statuses st ON st.id = ct.status_id
    ';

        $a1 = $db->prepare($query);
 		$a1->execute();

 		$num = $a1->rowCount();

	
if($num > 0){
	while($row = $a1->fetch(PDO::FETCH_ASSOC)){
		extract($row);

		echo "<tr><td>";
		echo $car_number;
		echo "</td><td>";
		echo $year_made;
		echo "</td><td>";
		echo $model;
		echo "</td><td>";
		echo $manager;
		echo "</td><td>";
		echo $status;
		echo "</td><td>";
		echo $previous_manager;
		echo "</td>";
	}
	echo "</table>";
}

?>
</body>
</html>
