<?php

namespace Model418\Example\BasicUsage;

require_once( __DIR__ . '/../../vendor/autoload.php');

// Retrieve all models
$userList = (new UserModel)->query()->fetchAll();

// Save a new Model
$user = (new UserModel)
    ->set('firstName', 'John')
    ->save();

// Retrieve by primary key
$john = (new UserModel)
    ->query()
    ->fetchById(1);

// Update an existing Model
$john->set('lastName', 'Doe')
    ->save();

// Delete an existing Model
$john->delete();