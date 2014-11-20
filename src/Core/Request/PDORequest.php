<?php

namespace Elephant418\Model418\Core\Request;

class PDORequest
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $SQL;
    protected $PDO;
    protected $lastInsertId = false;


    /* GETTER
     *************************************************************************/
    public function getPDO()
    {
        return $this->PDO;
    }

    public function getSQL()
    {
        return $this->SQL;
    }

    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }


    /* SETTER
     *************************************************************************/
    public function setPDO($PDO)
    {
        $this->PDO = $PDO;
        return $this;
    }

    public function setSQL($SQL)
    {
        $this->SQL = $SQL;
        return $this;
    }


    /* CONSTRUCTOR
     *************************************************************************/
    public function __construct($PDO = null, $SQL = '')
    {
        $this->setPDO($PDO);
        $this->setSQL($SQL);
    }


    /* PUBLIC METHODS
     *************************************************************************/
    public function executeOne($arguments = array())
    {
        $result = $this->execute($arguments);
        if (is_array($result) && count($result) > 0) {
            $result = $result[0];
        }
        return $result;
    }

    public function execute($arguments = array())
    {
        if (empty($this->PDO)) {
            throw new \PDOException('The PDO object is empty.');
        }
        if (empty($this->SQL)) {
            throw new \PDOException('The SQL request is empty.');
        }

        // Prepare the request
        $statement = $this->PDO->prepare($this->SQL);
        foreach ($arguments as $parameter => $value) {
            $statement->bindValue($parameter, $value);
        }
        $result = $statement->execute();

        // Execute the request
        if (!$result) {
            throw new \PDOException('Error with the SQL request : ' . $this->SQL, $statement->errorInfo());
        }

        if (\UString::isStartWith($this->SQL, ['SELECT', 'SHOW', 'DESCRIBE', 'EXPLAIN'])) {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        } else if (\UString::isStartWith($this->SQL, "INSERT")) {
            $result = true;
            $id = $this->PDO->lastInsertId();
            if ($id == '0') {
                // Error Case or a table without autoincrement
                $id = false;
                $result = false;
            }
            $this->lastInsertId = $id;
        }

        return $result;
    }
}




