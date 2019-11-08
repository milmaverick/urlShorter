<?php

ini_set ("session.use_trans_sid", true);
session_start();

define('HOST', $_SERVER['HTTP_HOST']);
define("PATH", "App/core/");
define("CONTROLLER_PATH", "App/controllers/");
define("MODEL_PATH", "App/models/");

require_once("Db.php");
require_once("Route.php");
require_once PATH. 'Model.php';
require_once PATH. 'View.php';
require_once PATH. "Controller.php";

Routing::buildRoute();
