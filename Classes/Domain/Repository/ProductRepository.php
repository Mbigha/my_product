<?php
namespace Mbigha\MyProduct\Domain\Repository;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/***
 *
 * This file is part of the "Products" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/
/**
 * The repository for Products
 */
class ProductRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager = null;

    public function trunctateProductsTable() {
        $tableName = 'tx_myproduct_domain_model_product';
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable($tableName);
        $connection->truncate($tableName);
    }

    /**
     * @param \Mbigha\MyProduct\Domain\Model\Product $product
     */
    public function updateProduct(\Mbigha\MyProduct\Domain\Model\Product $product) {
        $this->update($product);
        $this->persistenceManager->persistAll();
    }
}
