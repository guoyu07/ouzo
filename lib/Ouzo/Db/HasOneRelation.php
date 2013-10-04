<?php

namespace Ouzo\Db;


use Ouzo\Utilities\Arrays;

class HasOneRelation extends Relation
{
    function __construct($name, array $params, $primaryKey)
    {
        parent::__construct($name, $params);

        $this->referencedColumn = Arrays::getValue($params, 'referencedColumn', $primaryKey);
        $this->collection = false;
    }
}