<?php

namespace Application\Table;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\AbstractTableGateway;
use Psr\Container\ContainerInterface;

final class PromotionsTable extends AbstractTableGateway
{
    const TABLE_NAME = 'PROMOTIONS';

    /**
     * PurchaseOrderHeaderTable constructor.
     * @param AdapterInterface $adapter
     * @param $table
     */
    public function __construct(AdapterInterface $adapter, $table)
    {
        $this->adapter = $adapter;
        $this->table = $table;
        $this->initialize();
    }

    /**
     * @param ContainerInterface $container
     * @return PromotionsTable
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class),
            self::TABLE_NAME
        );
    }
}