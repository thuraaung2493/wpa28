<!-- localhost: ooframe.loca -->

<?php

define("DD", realpath("../"));

require DD . "/vendor/autoload.php";

// Select
$result = DB::table("students")->where('id', '<', 10)->get();
var_dump($result);
$results = DB::table("students")->select('name','address')->where('name', '=', 'Too Ba')->get();
var_dump($results);

// Delete
// DB::table("students")->where("id", 19)->delete();

// Insert
$data = [
    "name" => "Zaw Zaw",
    "address" => "Tarmwe"
];
// DB::table("students")->insert($data);


// Update
$update_data = [
    "name" => "Naw Naw",
    "address" => "San Chaung"
];
// DB::table("students")->where("id", 5)->update($update_data);


// Get All
// $students = DB::table("students")->get();
// var_dump($students);
// foreach ($students as $stu) {
//     echo "Id = " . $stu['id'] . "<br>";
//     echo "Name = " . $stu['name'] . "<br>";
//     echo "Address = " . $stu['address'] . "<br>";
// }


// Get By Id
// $student = DB::table("students")->where("id", 18)->get();
// var_dump($student);

 ?>
