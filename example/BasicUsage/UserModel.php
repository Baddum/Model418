<?php

namespace Model418\Example\BasicUsage;

use Model418\ModelQuery;

class UserModel extends ModelQuery
{

    // The list of the attributes of your model
    protected function initSchema()
    {
        return array('firstName', 'lastName');
    }
}