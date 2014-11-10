Quick Start
======

1. [Install](#install)
2. [Define A Model](#define-a-model)
3. [Basic Usage](#basic-usage)



Install
--------

Install [Composer](http://getcomposer.org/doc/01-basic-usage.md#installation) and run the following command:

```sh
composer require elephant418/model418:~1.0
```



Define A Model
--------

Start by defining a model

```php
use Model418\Model;

class UserModel extends Model
{

    // The list of the attributes of your model
    protected function initSchema()
    {
        return array('firstName', 'lastName');
    }
    
    // An instance of the associated Query.
    // It will be used to query the file system
    protected function initQuery()
    {
        // Instance a FileQuery and set the folder that will be used for storage
        return new FileQuery($this, __DIR__.'/User');
    }
}
```

[&uarr; top](#readme)



Basic Usage
--------

```php
// Retrieve all models
$userList = (new UserModel)->query()->fetchAll();

// Save a new Model
$user = (new UserModel)
    ->set('firstName', 'John')
    ->save();
    
// Retrieve by primary key
$john = (new UserModel)->query()->fetchById(1);
    
// Update an existing Model
$john->set('lastName', 'Doe')
    ->save();
    
// Delete an existing Model
$john->delete();
```

[&uarr; top](#readme)