<?php

namespace App\Http\Controllers\Reviews;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Requests\Review\DeleteReviewRequest;
use App\Http\Requests\Review\ListReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Transformers\ReviewTransformer;
use Appkr\Api\Http\Response;
use Myshop\Application\Service\ReviewService;
use Myshop\Domain\Repository\ProductRepository;
use Myshop\Domain\Repository\ReviewRepository;

class ReviewController extends Controller
{
    private $productRepository;
    private $reviewRepository;
    private $reviewService;
    private $presenter;

    public function __construct(
        ProductRepository $productRepository,
        ReviewRepository $reviewRepository,
        ReviewService $reviewService,
        Response $presenter
    ) {
        $this->productRepository = $productRepository;
        $this->reviewService = $reviewService;
        $this->reviewRepository = $reviewRepository;
        $this->presenter = $presenter;
    }

    public function index(ListReviewRequest $request, int $productId)
    {
        $product = $this->productRepository->findById($productId);

        $paginatedReviewCollection = $this->reviewRepository->findBySearchParam(
            $request->getReviewSearchParam(),
            $product
        );

        return $this->presenter->withPagination(
            $paginatedReviewCollection,
            new ReviewTransformer
        );
    }

    public function store(CreateReviewRequest $request, int $productId)
    {
        $product = $this->productRepository->findById($productId);

        $review = $this->reviewService->createReview(
            $product, $request->user(), $request->getReviewDto()
        );

        return $this->presenter->withItem($review, new ReviewTransformer);
    }

    public function update(
        UpdateReviewRequest $request,
        int $productId,
        int $reviewId
    ) {
        $product = $this->productRepository->findById($productId);

        // [선점잠금] 레코드를 조회하고 잠급니다.
        // $review = $this->reviewRepository->findByIdWithLock($reviewId, $product);

        // [선점잠금] PoC를 위해 강제로 잠금을 연장합니다.
        // 선점한 프로세스 A가 끝나고 DB 잠금이 풀리면, 다음 프로세스 B를 처리합니다.
        // sleep(10);

        // [비선점잠금]
        // 조회시점 대비 DB의 버전이 같은지를 확인하여 변경 가능 여부를 판단합니다.
        $review = $this->reviewRepository->findById($reviewId, $product);

        // Request에서는 리뷰 작성자를 식별할 수 없어서 컨트롤러에서 접근 권한 검사 합니다.
        $this->authorize('update', $review);

        $review = $this->reviewService->modifyReview(
            $review, $request->getReviewDto()
        );

        return $this->presenter->withItem($review, new ReviewTransformer);
    }

    public function destroy(
        DeleteReviewRequest $request,
        int $productId,
        int $reviewId
    ) {
        $product = $this->productRepository->findById($productId);
        $review = $this->reviewRepository->findById($reviewId);

        // Request에서는 리뷰 작성자를 식별할 수 없어서 컨트롤러에서 접근 권한 검사 합니다.
        $this->authorize('delete', $review);

        $this->reviewService->deleteReview($review, $product);

        return $this->presenter->noContent();
    }
}
