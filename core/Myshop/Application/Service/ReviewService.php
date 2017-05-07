<?php

namespace Myshop\Application\Service;

use Illuminate\Contracts\Auth\Authenticatable;
use Myshop\Common\Dto\ReviewDto;
use Myshop\Domain\Model\Product;
use Myshop\Domain\Model\Review;
use Myshop\Domain\Repository\ReviewRepository;

class ReviewService
{
    private $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function makeReview(Product $product, Authenticatable $user, ReviewDto $dto) : Review
    {
        $review = new Review;

        $review->title = $dto->getTitle();
        $review->content = $dto->getContent();

        $review->author()->associate($user);
        $review->product()->associate($product);

        $this->reviewRepository->save($review);

        return $review->fresh();
    }

    public function modifyReview(Review $review, ReviewDto $dto) : Review
    {
        $review->title = $dto->getTitle() ?: $review->title;
        $review->content = $dto->getContent() ?: $review->content;

        $this->reviewRepository->save($review);

        return $review->fresh();
    }

    public function deleteReview(Review $review, Product $product = null)
    {
        $this->reviewRepository->delete($review, $product);
    }
}