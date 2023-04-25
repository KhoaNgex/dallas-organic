<?php

spl_autoload_register(function ($classname) {
    require $filename = __DIR__ . "/../models/" . ucfirst($classname) . ".php";
});

require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'ErrorHandler.php';
require 'App.php';