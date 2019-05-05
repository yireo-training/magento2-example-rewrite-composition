<?php
declare(strict_types=1);

namespace Yireo\ExampleRewriteComposition\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface as OriginalProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Interface ProductRepositoryInterface
 *
 * @package Yireo\ExampleRewriteComposition\Api
 */
interface ProductRepositoryInterface extends OriginalProductRepositoryInterface
{
    /**
     * @return ProductInterface
     */
    public function createNew(): ProductInterface;

    /**
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder;
}
