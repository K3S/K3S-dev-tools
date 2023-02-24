<?php

declare(strict_types=1);

namespace Console\Command;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;

final class CopyData extends Command
{
    /**
     * @var Adapter|AdapterInterface
     */
    private $adapter;

    /**
     * CopyData constructor.
     * @param AdapterInterface $adapter
     * @param null $name
     */
    public function __construct(AdapterInterface $adapter, $name = null)
    {
        $this->adapter = $adapter;

        parent::__construct($name);
    }

    /**
     * @param ContainerInterface $container
     * @return static
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class)
        );
    }

    public function configure()
    {
        $this->setName('db:copy-data')
            ->setDescription('Copies data from one library to another')
            ->setHelp('This command allows you to copy data from one library to another')
            ->addArgument(
                'from',
                InputArgument::REQUIRED,
                'Which library are you copying data from?'
            )->addArgument(
                'to',
                InputArgument::REQUIRED,
                'Which library are you copying data to?'
            );

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $tables = $this->getLibraryTables($input->getArgument('from'));
        foreach ($tables as $table) {
            $output->writeln('Copying data from ' . $input->getArgument('from') . '.' . $table);
            $this->copyData($input->getArgument('from'), $table, $input->getArgument('to'), $table);
        }
    }

    public function copyData(string $fromSchema, string $fromTable, string $toSchema, string $toTable)
    {
        $sql = 'DELETE FROM ' . $toSchema . '.' . $toTable;
        $this->adapter->query($sql, []);
        $sql = 'INSERT INTO ' . $toSchema . '.' . $toTable . ' (SELECT * FROM ' . $fromSchema . '.' . $fromTable . ')';
        $this->adapter->query($sql, []);
    }

    /**
     * @param string $library
     * @return string[]
     */
    public function getLibraryTables(string $library): array
    {

        // @todo only select New Steel tables (currently getting Scrap too)

//        $sql = 'SELECT TABLE_NAME from QSYS2.TABLES where TABLE_SCHEMA=? and TABLE_TYPE=?';
//        $params = [$library, 'BASE TABLE'];
//        return $this->adapter->query($sql, $params)->toArray();


        return [
            'INF001',
            'INF002',
            'INF003',
            'INF004',
            'INF005',
            'INF006',
            'INF007',
            'INF008',
            'INF009',
            'INF010',
            'INF011',
            'INF012',
            'INF013',
            'INF014',
            'INF015',
            'INF020',
            'INF021',
            'INF022',
            'INF025',
            'INF030',
            'INF031',
            'INF040',
            'INF041',
            'INF042',
            'INF050',
            'INF100',
            'INF901',
            'INF902',
            'INF903',
            'INHISTAJ',
            'INHISTDR',
            'INHISTIV',
            'INHISTPO',
            'INHISTSC',
            'INHISTSR',
            'INHISTTF',
            'INVMSTG',
            'INVMSTH',
            'INVMSTI',
            'OEF001',
            'OEF002',
            'OEF004',
            'OEF007',
            'OEF008',
            'OEF018',
            'OEF021',
            'OEF022',
            'OEF901',
            'TAXMST',
            'XAF001',
            'XAF002',
            'XAF901',
            'XAF902',
            'APF001',
            'ARF008',
            'ARPMAS',
            'NTF001',
            'ARF004',
            'PUF004',
            'PUF005',
            'PUF016',
            'PUF017',
            'PUF019',
            'PUF022',
            'PUF023',
            'PUF027',
            'PURR001',
            'PURR901',
            'PUF901',
            'SAF001',
            'SAF006',
            'SAF013',
            'SLSMNX',
            'PUF003',
            'PUF006',
            'PUF060',
            'PUF061',
            'PUF064',
            'PUFMTREML',
            'PUFMTRNXT',
            'PUFMTRS',
        ];
    }
}