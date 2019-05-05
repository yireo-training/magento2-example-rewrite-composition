<?php
declare(strict_types=1);

namespace Yireo\ExampleRewriteComposition\Rewrite;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Catalog\Model\ProductRepository as OriginalProductRepository;

use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

use Yireo\ExampleRewriteComposition\Api\ProductRepositoryInterface;

/**
 * Class ProductRepository
 * @package Yireo\ExampleRewriteComposition\Rewrite
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var OriginalProductRepository
     */
    private $original;

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    /**
     * ProductRepository constructor.
     *
     * @param OriginalProductRepository $original
     * @param ProductInterfaceFactory $productFactory
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        OriginalProductRepository $original,
        ProductInterfaceFactory $productFactory,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->original = $original;
        $this->productFactory = $productFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * @return ProductInterface
     */
    public function createNew(): ProductInterface
    {
        return $this->productFactory->create();
    }

    /**
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilderFactory->create();
    }

    /**
     * Create product
     *
     * @param ProductInterface $product
     * @param bool $saveOptions
     * @return ProductInterface
     * @throws InputException
     * @throws StateException
     * @throws CouldNotSaveException
     */
    public function save(ProductInterface $product, $saveOptions = false)
    {
        return $this->original->save($product, $saveOptions);
    }

    /**
     * Get info about product by product SKU
     *
     * @param string $sku
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function get($sku, $editMode = false, $storeId = null, $forceReload = false)
    {
        return $this->original->get($sku, $editMode, $storeId, $forceReload);
    }

    /**
     * Get info about product by product id
     *
     * @param int $productId
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function getById($productId, $editMode = false, $storeId = null, $forceReload = false)
    {
        return $this->original->getById($productId, $editMode, $storeId, $forceReload);
    }

    /**
     * Delete product
     *
     * @param ProductInterface $product
     * @return bool Will returned True if deleted
     * @throws StateException
     */
    public function delete(ProductInterface $product)
    {
        return $this->original->delete($product);
    }

    /**
     * @param string $sku
     * @return bool Will returned True if deleted
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById($sku)
    {
        return $this->original->deleteById($sku);
    }

    /**
     * Get product list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ProductSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        return $this->original->getList($searchCriteria);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->original, $name)) {
            return $this->original->$name($arguments);
        }

        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
    }
}
