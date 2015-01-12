<?php
/*
 * Copyright (c) Ouzo contributors, http://ouzoframework.org
 * This file is made available under the MIT License (view the LICENSE file for more information).
 */
namespace Application\Model\Test;

use Ouzo\Model;

/**
 * @property int id
 * @property string name
 * @property Product[] products
 */
class Manufacturer extends Model
{
    private $_fields = array('name');

    public function __construct($attributes = array())
    {
        parent::__construct(array(
            'hasMany' => array('products' => array('class' => 'Test\Product', 'foreignKey' => 'id_manufacturer')),
            'attributes' => $attributes,
            'fields' => $this->_fields));
    }
}
