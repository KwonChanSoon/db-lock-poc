<?php

namespace Myshop\Domain\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Myshop\Common\Dto\ReviewSearchParam;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;

interface ReviewRepository
{
    public function findById(int $id, Product $product = null) : Review;
    public function findByIdWithLock(int $id, Product $product = null) : Review;
    public function findBySearchParam(ReviewSearchParam $param, Product $product = null) : LengthAwarePaginator;
    public function save(Review $review) : void;
    public function delete(Review $review, Product $product = null) : void;
}