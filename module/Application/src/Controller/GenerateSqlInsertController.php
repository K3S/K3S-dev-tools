<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Form\GenerateSqlInsertForm;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Driver\IbmDb2\Statement;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Sql;
use Laminas\Form\FormElementManager;
use Laminas\Form\FormInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

final class GenerateSqlInsertController extends AbstractActionController
{
    private AdapterInterface $databaseAdapter;
    private FormInterface $form;

    /**
     * @param AdapterInterface $databaseAdapter
     * @param FormInterface $form
     */
    public function __construct(AdapterInterface $databaseAdapter, FormInterface $form)
    {
        $this->databaseAdapter = $databaseAdapter;
        $this->form = $form;
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self(
            $container->get(Adapter::class),
            $container->get(FormElementManager::class)->get(GenerateSqlInsertForm::class)
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
            $query = trim($input['query'], ';');


            $result = $this->generateSqlInsert($query);
            $viewModel->setVariable('tableName', $tableName);
            $viewModel->setVariable('whereClause', $whereClause);
            $viewModel->setVariable('result', $result);
        }

        $viewModel->setVariable('form', $this->form);

        return $viewModel;
    }

    private function parseSqlQuery(string $query): array
    {
        $sqlKeywords = ['SELECT', 'FROM', 'WHERE', 'ORDER BY', 'LIMIT', 'OFFSET', 'GROUP BY', 'HAVING', 'UNION', 'LEFT JOIN', 'RIGHT JOIN',];
        $queryKeywords = [];
        $queryKeywordPositions = [];
        foreach ($sqlKeywords as $keyword) {
            if (str_contains($query, $keyword)) {
                $position = strpos($query, $keyword);
                $queryKeywords[] = [
                    'keyword' => $keyword,
                    'position' => $position,
                ];
                $queryKeywordPositions[] = $position;
            }
        }

        var_dump($queryKeywords);
        var_dump($queryKeywordPositions);
        die();


        $query = strtoupper($query);
        $fromPosition = strpos($query, 'FROM');
        $select = substr($query, 0, $fromPosition);
        var_dump($select);
        die();
    }

    private function generateSqlInsert(string $query): string
    {
        $retrievalSchema = 'ACS_5DTA';

        // Get values
        $this->values = $this->getValues($query);

        $this->parseSqlQuery($query);
        die();

        // Get columns
        $columns = $this->getColumns($retrievalSchema, $table);
        $columnsString = $this->convertColumnsArrayToString($columns);

        // Convert values to string
        $valuesString = $this->convertValuesArrayToString($this->values);

        // Generate placeholders string
        $placeholdersString = $this->generatePlaceholdersString(count($columns));

        return "INSERT INTO $table ($columnsString) VALUES ($valuesString);";
    }

    /**
     * @param string $retrievalSchema
     * @param string $table
     * @param string $where
     * @param array $whereValues
     * @return array
     */
    private function getValues(string $query): array
    {
        $sql = $query;
        $results = $this->databaseAdapter->query($sql, [])->toArray();
        foreach ($results as &$result) {
            $result = array_map('trim', $result);
        }
        return $results;
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