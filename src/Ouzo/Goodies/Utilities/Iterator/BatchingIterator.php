<?php
/*
 * Copyright (c) Ouzo contributors, http://ouzoframework.org
 * This file is made available under the MIT License (view the LICENSE file for more information).
 */
namespace Ouzo\Utilities\Iterator;

use Iterator;

class BatchingIterator implements Iterator
{
    /** @var Iterator $iterator */
    private $iterator;
    /** @var int */
    private $chunkSize;
    /** @var array $currentChunk */
    private $currentChunk;
    /** @var int */
    private $position = 0;

    /**
     * @param Iterator $iterator
     * @param int $chunkSize
     */
    public function __construct(Iterator $iterator, $chunkSize)
    {
        $this->iterator = $iterator;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
        $this->iterator->rewind();
    }

    /**
     * @return bool
     */
    public function valid()
    {
        if (!isset($this->currentChunk)) {
            $this->fetchChunk();
        }
        return !empty($this->currentChunk);
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return array
     */
    public function current()
    {
        return $this->currentChunk;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->position++;
        $this->fetchChunk();
    }

    /**
     * @return void
     */
    private function fetchChunk()
    {
        $this->currentChunk = [];
        for ($i = 0; $i < $this->chunkSize && $this->iterator->valid(); $i++, $this->iterator->next()) {
            $this->currentChunk[] = $this->iterator->current();
        }
    }
}
