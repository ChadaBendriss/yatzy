<?php
require_once('_config.php');
header("Content-Type: application/json");
echo json_encode(["version" => "1.0"]);