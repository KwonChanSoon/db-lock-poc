<?php

namespace Myshop\Common\Dto;

class ReviewSearchParam
{
    private $keyword;
    private $userId;
    private $sortBy;
    private $sortDirection;
    private $page;
    private $size;

    public function __construct(
        string $keyword = null,
        int $userId = null,
        string $sortBy = 'created_at',
        string $sortDirection = 'desc',
        int $page = 1,
        int $size = 10
    ) {
        $this->keyword = $keyword;
        $this->userId = $userId;
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->page = $page;
        $this->size = $size;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getSortBy()
    {
        return $this->sortBy;
    }

    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getSize()
    {
        return $this->size;
    }
}