<?php

require_once __DIR__ . '/../vendor/autoload.php';

use dfdiag\Belajar\PHP\MVC\App\Router;
use dfdiag\Belajar\PHP\MVC\Config\Database;
use dfdiag\Belajar\PHP\MVC\Controller\HomeController;
use dfdiag\Belajar\PHP\MVC\Controller\UserController;

//Database env prod or test
Database::getConnection('prod');
//Home Controller
Router::add('GET', '/', HomeController::class, 'index',[]);

//User Controller
Router::add('GET', '/users/register', UserController::class, 'register',[]);
Router::add('POST', '/users/register', UserController::class, 'postRegister',[]);
Router::add('GET', '/users/login', UserController::class, 'login',[]);
Router::add('POST', '/users/login', UserController::class, 'postLogin',[]);

Router::run();