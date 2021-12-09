<?php
declare(strict_types=1);

namespace Application\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class GenerateSqlInsertController extends AbstractActionController
{
    private $databaseAdapter;

    public function __construct(AdapterInterface $databaseAdapter)
    {
        $this->databaseAdapter = $databaseAdapter;
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class)
        );
    }

    public function indexAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/generate-sql-insert.phtml');
        $viewModel->setVariable('tableName', '');
        $viewModel->setVariable('whereClause', '');
        $viewModel->setVariable('result', '');
        $viewModel->setVariable('whereParams', ['', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '']);

        if ($this->getRequest()->isPost()) {
            $input = $this->params()->fromPost();
            $tableName = $input['tableName'];
            $whereClause = $input['whereClause'];
            $whereParams = array_filter($input['whereParams']);

            $result = $this->generateSqlInsert($tableName, $whereClause, $whereParams);
            $viewModel->setVariable('tableName', $tableName);
            $viewModel->setVariable('whereClause', $whereClause);
            $viewModel->setVariable('result', $result);
        }

        return $viewModel;
    }

    private function generateSqlInsert(string $table, string $where, array $whereValues): string
    {
        $retrievalSchema = 'ACS_5DTA';

        // Get values
        $this->values = $this->getValues($retrievalSchema, $table, $where, $whereValues);

        // Get columns
        $columns = $this->getColumns($retrievalSchema, $table);
        $columnsString = $this->convertColumnsArrayToString($columns);

        // Convert values to string
        $valuesString = $this->convertValuesArrayToString($this->values);

        // Generate placeholders string
        $placeholdersString = $this->generatePlaceholdersString(count($columns));

        return "INSERT INTO $table ($columnsString) VALUES ($valuesString)";
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