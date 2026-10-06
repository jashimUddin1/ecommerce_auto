<?php
require __DIR__ . '/../config/init.php';
unset($_SESSION['user']);
redirect('/public/index.php');
