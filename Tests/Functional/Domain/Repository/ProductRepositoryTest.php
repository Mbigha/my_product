<?php
declare(strict_types=1);

namespace Mbigha\MyProduct\Tests\Functional\Domain\Repository;

use Mbigha\MyProduct\Domain\Repository\ProductRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * Test case
 */
class ReferencesRepositoryTest extends FunctionalTestCase
{
    /**
     * @var array Load required extensions
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/my_product'
    ];

    /**
     * @var ProductRepository
     */
    private $subject = null;

    private $connection = null;

    /**
     * @var PersistenceManager
     */
    private $persistenceManager = null;

    /**
     * @var Typo3QuerySettings
     */
    private $querySettings = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $this->connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_myproduct_domain_model_product');

        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->querySettings = $objectManager->get(Typo3QuerySettings::class);
        $this->querySettings->setRespectStoragePage(FALSE);

        $this->subject = $objectManager->get(ProductRepository::class);
        $this->subject->setDefaultQuerySettings($this->querySettings);
    }

    /**
     * @test
     */
    public function trunctateProductsTableSuccessfullyTruncatesProductsTable(): void
    {
        $this->importDataSet(__DIR__ . '/Fixtures/tx_myproduct_domain_model_product.xml');

        $numberOfItemsInDatabase = $this->connection->count('uid', 'tx_myproduct_domain_model_product', ['deleted' => 0]);
        $this->assertEquals($this->subject->countAll(), $numberOfItemsInDatabase);
        $this->subject->trunctateProductsTable();

        $numberOfItemsInDatabase = $this->connection->count('uid', 'tx_myproduct_domain_model_product', ['deleted' => 0]);
        $this->assertEquals($numberOfItemsInDatabase, 0);
    }

    /**
     * @test
     */
    public function findAllGetsAllNonDeletedProductsInRepository(): void
    {
        $this->importDataSet(__DIR__ . '/Fixtures/tx_myproduct_domain_model_product.xml');

        $numberOfItemsInDatabase = $this->connection->count('uid', 'tx_myproduct_domain_model_product', ['deleted' => 0]);
        $this->assertCount($numberOfItemsInDatabase, $this->subject->findAll());
    }

    /**
     * @test
     */
    public function updateProductUpdatesASpecificProduct(): void
    {
        $this->importDataSet(__DIR__ . '/Fixtures/tx_myproduct_domain_model_product.xml');
        $newProductName = 'New Product Name';

        $product = $this->subject->findByUid(1);
        $product->setName($newProductName);
        $this->subject->updateProduct($product);
        $updatedProduct = $this->subject->findByUid(1);

        $this->assertEquals($newProductName, $updatedProduct->getName());
    }
}
