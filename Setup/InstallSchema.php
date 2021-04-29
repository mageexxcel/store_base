<?php

namespace Excellence\Storebase\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
		$installer = $setup;
		$installer->startSetup();

		/**
		 * Creating table excellence_storebase
		 */
		$installer->getConnection()->addColumn(
                $installer->getTable('quote'),
                    'storebase',
                    [
                        'nullable' => false,
                        'length' => 255,
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'comment' => 'Storebase',
                    ]
                );

                $installer->getConnection()->addColumn(
                    $installer->getTable('sales_order'),
                    'storebase',
                    [
                        'nullable' => false,
                        'length' => 255,
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'comment' => 'Storebase',
                    ]
                );

                $installer->getConnection()->addColumn(
                    $installer->getTable('sales_order_grid'),
                    'storebase',
                    [
                        'nullable' => false,
                        'length' => 255,
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'comment' => 'Storebase',
                    ]
                );
                $installer->endSetup();

            // ==============new table setup===============
          $installer = $setup;
            $installer->startSetup();

            $table = $installer->getConnection()->newTable(
            $installer->getTable('excellence_storebase')
        )->addColumn(
            'store_view',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            25,
            [ 'nullable' => false, ],
            'Store View'
        )->addColumn(
            'storebase_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'Entity ID'
        )->addColumn(
            'store_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Store Name'
        )->addColumn(
            'street_one',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Street One'
        )->addColumn(
            'street_two',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Street Two'
        )->addColumn(
            'number',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Phone Number'
        )->addColumn(
            'time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Time'
        )->addColumn(
            'city',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'City'
        )->addColumn(
            'region_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'State/Province'
        )->addColumn(
            'zipcode',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            50,
            [ 'nullable' => false, ],
            'Zipcode'
        )->addColumn(
            'country',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Country'
        )->addColumn(
            'latitude',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            ['nullable' => false,],
            'Latitude'
        )->addColumn(
            'longitude',
             \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            ['nullable' => false,],
            'Longitude'
        )->addColumn(
            'position',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            50,
            [ 'nullable' => false, ],
            'Position'
        )->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            50,
            [ 'nullable' => false, ],
            'Status'
        )->addColumn(
            'sunday',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'nullable' => false, ],
            'Sunday'
        )->addColumn(
            'monday',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'nullable' => false, ],
            'Monday'
        )->addColumn(
            'tuesday',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'nullable' => false, ],
            'Tuesday'
        )->addColumn(
            'wednesday',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'nullable' => false, ],
            'Wednesday'
        )->addColumn(
            'thursday',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'nullable' => false, ],
            'Thursday'
        )->addColumn(
            'friday',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'nullable' => false, ],
            'Friday'
        )->addColumn(
            'saturday',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'nullable' => false, ],
            'Saturday'
        )->addColumn(
            'time_range',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            2000,
            ['nullable' => false,],
            'Time Range'
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
	}
}