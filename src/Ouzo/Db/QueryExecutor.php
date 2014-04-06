<?php
namespace Ouzo\Db;

use InvalidArgumentException;
use Ouzo\Config;
use Ouzo\Db;
use Ouzo\Db\Dialect\DialectFactory;
use Ouzo\Utilities\Arrays;
use Ouzo\Utilities\Objects;
use PDO;

class QueryExecutor
{
    /**
     * @var Db
     */
    private $_db;
    private $_adapter;
    private $_query;
    private $_boundValues = array();

    public $_sql;
    public $_fetchStyle = PDO::FETCH_ASSOC;

    function __construct($db, $query)
    {
        $this->_db = $db;
        $this->_query = $query;

        $this->_adapter = DialectFactory::create();
    }

    /**
     * @return QueryExecutor
     */
    public static function prepare($db, $query)
    {
        if (empty($db) || !$db instanceof Db) {
            throw new InvalidArgumentException("Database handler not provided or is of wrong type");
        }
        if (!$query) {
            throw new InvalidArgumentException("Query object not provided");
        }
        if (!$query->table) {
            throw new InvalidArgumentException("Table name cannot be empty");
        }

        if (self::isEmptyResult($query->whereClauses)) {
            return new EmptyQueryExecutor();
        }
        return new QueryExecutor($db, $query);
    }

    public function fetch()
    {
        $this->_buildQuery();
        return $this->_fetch('fetch');
    }

    public function fetchAll()
    {
        $this->_buildQuery();
        return $this->_fetch('fetchAll');
    }

    public function execute()
    {
        $this->_buildQuery();
        return $this->_db->execute($this->_sql, $this->_boundValues);
    }

    public function insert($sequence = '')
    {
        $this->execute();
        return $sequence ? $this->_db->_dbHandle->lastInsertId($sequence) : null;
    }

    private function _fetch($function)
    {
        $statement = StatementExecutor::prepare($this->_db->_dbHandle, $this->_sql, $this->_boundValues, $this->_query->options);
        return $statement->executeAndFetch($function, $this->_fetchStyle);
    }

    public function getSql()
    {
        return $this->_sql;
    }

    public function getBoundValues()
    {
        return $this->_boundValues;
    }

    public function lastErrorMessage()
    {
        return $this->_db->lastErrorMessage();
    }

    private function _buildQuery()
    {
        $this->_fetchStyle = $this->_query->selectType;
        $this->_addBindValues();
        $this->_sql = $this->_adapter->buildQuery($this->_query);
    }

    public function _addBindValue($value)
    {
        if (is_array($value)) {
            $this->_boundValues = array_merge($this->_boundValues, $value);
        } else {
            $this->_boundValues[] = is_bool($value) ? Objects::booleanToString($value) : $value;
        }
    }

    private static function isEmptyResult($whereClauses)
    {
        return Arrays::any($whereClauses, function (WhereClause $whereClause) {
            return $whereClause->isNeverSatisfied();
        });
    }

    private function _addBindValues()
    {
        $this->_addBindValue(array_values($this->_query->updateAttributes));

        foreach ($this->_query->joinClauses as $joinClause) {
            $this->_addBindValuesFromWhereClause($joinClause->onClause);
        }
        foreach ($this->_query->whereClauses as $whereClause) {
            $this->_addBindValuesFromWhereClause($whereClause);
        }
        if ($this->_query->limit) {
            $this->_addBindValue($this->_query->limit);
        }
        if ($this->_query->offset) {
            $this->_addBindValue($this->_query->offset);
        }
    }

    private function _addBindValuesFromWhereClause($whereClause)
    {
        if (!$whereClause->isEmpty()) {
            $this->_addBindValue($whereClause->values);
        }
    }
}