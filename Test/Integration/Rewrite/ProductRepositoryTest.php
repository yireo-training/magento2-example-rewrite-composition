<?php
declare(strict_types=1);

namespace Yireo\ExampleRewriteComposition\Test\Integration\Rewrite;

use Yireo\ExampleRewriteComposition\Api\ProductRepositoryInterface;
use Yireo\ExampleRewriteComposition\Rewrite\ProductRepository as ProductRepositoryRewrite;
use Magento\Catalog\Api\Data\ProductInterface;

use Magento\TestFramework\ObjectManager;

use PHPUnit\Framework\TestCase;

/**
 * Class ProductRepositoryTest
 *
 * @package Yireo\ExampleRewriteComposition\Test\Integration\Rewrite
 */
class ProductRepositoryTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->get(ProductRepositoryInterface::class);
    }

    public function testBasics()
    {
        $this->assertInstanceOf(ProductRepositoryRewrite::class, $this->productRepository);
    }

    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @magentoAppArea adminhtml
     */
    public function testAddProduct()
    {
        $product = $this->productRepository->createNew();
        $product = $this->fillInProduct($product);

        $this->productRepository->save($product);

        $searchCriteriaBuilder = $this->productRepository->getSearchCriteriaBuilder();
        $searchCriteriaBuilder->addFilter('sku', 'example_foobar');
        $searchCriteria = $searchCriteriaBuilder->create();
        $searchResults = $this->productRepository->getList($searchCriteria);
        $products = $searchResults->getItems();
        $this->assertCount(1, $products);

        $this->productRepository->deleteById('example_foobar');
    }

    /**
     * @param ProductInterface $product
     * @return ProductInterface
     */
    private function fillInProduct(ProductInterface $product): ProductInterface
    {
        $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
            ->setAttributeSetId(4)
            ->setWebsiteIds([1])
            ->setName('Example Foo Bar')
            ->setSku('example_foobar')
            ->setPrice(10)
            ->setDescription('Foo Bar Description')
            ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
            ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            ->setCategoryIds([2])
            ->setStockData(['use_config_manage_stock' => 0]);

        return $product;
    }
}
