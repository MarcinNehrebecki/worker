<?php

namespace App\Service;

abstract class ListService
{
    protected $limit = 10;
    protected $offset = 0;
    protected $sort = 'name';
    protected $order = 'asc';
    protected $query = '';

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }
}