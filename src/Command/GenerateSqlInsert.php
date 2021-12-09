<?php

declare(strict_types=1);

namespace Tools\Command;

use Interop\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\ServiceManager;

final class GenerateSqlInsert extends Command
{
    /**
     * @var Adapter|AdapterInterface
     */
    private $databaseAdapter;

    /**
     * @var array
     */
    private $values;

    /**
     * GenerateSqlInsert constructor.
     * @param Adapter|AdapterInterface $databaseAdapter
     */
    public function __construct(AdapterInterface $databaseAdapter)
    {
        $this->databaseAdapter = $databaseAdapter;

        parent::__construct();
    }

    /**
     * @param ServiceManager|ContainerInterface $container
     * @return GenerateSqlInsert
     */
    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class)
        );
    }

    public function configure()
    {
        $this->setName('db:generate-insert')
            ->setDescription('Generate an SQL insert statement from a select statement with one result')
            ->setHelp('This command allows you to automate the task of building an insert statement from the retrieval of an individual row')
            ->addArgument(
                'format',
                InputArgument::OPTIONAL,
                'Should the output be formatted as SQL or PHP?',
                'PHP'
            );

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $table = 'K_TABLCOD';
        //$where = 'where TXCODE=?';
        $where = '';
        $retrievalSchema = 'WEB_5DTA';
        //$whereValues = [1000000];
        $whereValues = [];

        // Get values
        $this->values = $this->getValues($retrievalSchema, $table, $where, $whereValues);

        // Get columns
        $columns = $this->getColumns($retrievalSchema, $table);
        $columnsString = $this->convertColumnsArrayToString($columns);

        // Convert values to string
        $valuesString = $this->convertValuesArrayToString($this->values);

        // Generate placeholders string
        $placeholdersString = $this->generatePlaceholdersString(count($columns));

        $insert = "INSERT INTO $table ($columnsString) VALUES ($valuesString)";
        $output->writeln($insert);

        return 1;
    }

    /**
     * @param string $retrievalSchema
     * @param string $table
     * @param string $where
     * @param array $whereValues
     * @return array
     */
    private function getValues(string $retrievalSchema, string $table, string $where, array $whereValues): array
    {
        $sql = "SELECT * from $retrievalSchema.$table $where";
        $params = $whereValues;
        $results = $this->databaseAdapter->query($sql, $params)->toArray();
        $values = $results[0];

        return array_map('trim', $values);
    }

    /**
     * @param string $retrievalSchema
     * @param string $table
     * @return array
     */
    private function getColumns(string $retrievalSchema, string $table): array
    {
        $sql = "SELECT * from QSYS2.COLUMNS WHERE table_schema=? and table_name=?";
        $params = [$retrievalSchema, $table];
        $results = $this->databaseAdapter->query($sql, $params)->toArray();
        $columns = [];
        $counter = 0;
        foreach ($results as $result) {
            $columns[] = trim($result['COLUMN_NAME']);
            if ($result['DATA_TYPE'] === 'CHARACTER') {
                $this->values[$result['COLUMN_NAME']] = "'" . $this->values[$result['COLUMN_NAME']] . "'";
            }
            $counter++;
        }

        return $columns;
    }

    /**
     * @param array $columns
     * @return string
     */
    private function convertColumnsArrayToString(array $columns): string
    {
        $columnsString = '';
        $counter = 1;
        foreach ($columns as $column) {
            $columnsString .= $column;
            if ($counter < count($columns)) {
                $columnsString .= ', ';
            }
            $counter++;
        }

        return $columnsString;
    }

    /**
     * @param array $values
     * @return string
     */
    private function convertValuesArrayToString(array $values): string
    {
        $valuesString = '';
        $counter = 1;
        foreach ($this->values as $key => $value) {
            if ($value === '_') {
                $value = '';
            }
            $valuesString .= $value;
            if ($counter < count($this->values)) {
                $valuesString .= ', ';
            }
            $counter++;
        }
        return $valuesString;
    }

    /**
     * @param int $numberOfColumns
     * @return string
     */
    private function generatePlaceholdersString(int $numberOfColumns): string
    {
        $placeholdersString = '';
        $counter = 1;
        while ($counter <= $numberOfColumns) {
            $placeholdersString .= '?';
            if ($counter < $numberOfColumns) {
                $placeholdersString .= ', ';
            }
            $counter++;
        }
        return $placeholdersString;
    }
}
