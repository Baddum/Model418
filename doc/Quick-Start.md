Quick Start
======

1. [Install](#install)
2. [Define a Model](#define-a-model)
3. [Basic Usage](#basic-usage)
4. [Define Storage Folder](#define-storage-folder)



Install
--------

Install [Composer](http://getcomposer.org/doc/01-basic-usage.md#installation) and run the following command:

```sh
composer require elephant418/model418:~1.0
```



Define a Model
--------

Start by defining a model

```php
use Model418\ModelQuery;

class UserModel extends ModelQuery
{

    // The list of the attributes of your model
    protected function initSchema()
    {
        return array('firstName', 'lastName');
    }
}
```



Basic Usage
--------

```php
// Require composer autoload
require_once( __DIR__ . '/../../vendor/autoload.php');
use Model418\Example\BasicUsage\UserModel;

// Retrieve all models
$userList = (new UserModel)->query()->fetchAll();

// Save a new Model
// And create a `data/UserModel/1.yml` file
$user = (new UserModel)
    ->set('firstName', 'John')
    ->save();
    
// Retrieve by primary key
$johnId = $user->id;
$john = (new UserModel)
    ->query()
    ->fetchById($johnId);
    
// Update an existing Model
$john->set('lastName', 'Doe')
    ->save();

// Access to Model attribute
echo $john->firstName.' '.$john->lastName.PHP_EOL;
    
// Delete an existing Model
$john->delete();
```



Define Storage Folder
--------

You can define in which folder you want to store your data, by overriding the `initFolder()` method:

```php
use Model418\ModelQuery;

class UserModel extends ModelQuery
{

    protected function initFolder()
    {
        // Return the path where you want to store your data.
        return __DIR__.'/../data/User';
    }
}
```
