<?php
/*
 * Copyright (c) Ouzo contributors, http://ouzoframework.org
 * This file is made available under the MIT License (view the LICENSE file for more information).
 */
namespace Ouzo\Injection;


class Binder
{
    private $className;
    private $scope = Scope::PROTOTYPE;

    function __construct($className)
    {
        $this->className = $className;
    }

    public function in($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getScope()
    {
        return $this->scope;
    }
}