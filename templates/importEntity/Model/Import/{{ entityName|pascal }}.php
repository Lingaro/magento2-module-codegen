{{ include(template_from_string(headerPHP)) }}
namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\Import;

use Exception;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\ImportExport\Helper\Data as ImportHelper;
use Magento\ImportExport\Model\Import;
use Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\ResourceModel\Helper;
use Magento\ImportExport\Model\ResourceModel\Import\Data;
{% set entity_db_table = entityDbTable|trim %}
{% if entity_db_table == '' %}
{% set entity_db_table = vendorName|snake ~ "_" ~ moduleName|snake ~ "_" ~ entityName|snake %}
{% endif %}

class {{ entityName|pascal }} extends AbstractEntity
{
    public const ENTITY_CODE = '{{ entityName|snake }}';
    public const TABLE = '{{ entity_db_table }}';
    public const ENTITY_ID_COLUMN = '{{ entityIdColumn }}';

    protected $needColumnCheck = true;
    protected $logInHistory = true;
    protected $_permanentAttributes = [
        '{{ entityIdColumn }}'
    ];
    protected $validColumnNames = [
        '{{ entityIdColumn }}',
{% for item in fields %}
        '{{ item.name|snake }}',
{% endfor %}
    ];

    /**
     * Validation failure entity specific message template definitions
     *
     * Add entries for each of your custom row data validations, eg.
     * protected $_messageTemplates = [
     *     'NameIsRequired' => 'The name cannot be empty.',
     * ];
     *
     * @var array
     */
    protected $_messageTemplates = [];

    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @param JsonHelper $jsonHelper
     * @param ImportHelper $importExportData
     * @param Data $importData
     * @param ResourceConnection $resource
     * @param Helper $resourceHelper
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     */
    public function __construct(
        JsonHelper $jsonHelper,
        ImportHelper $importExportData,
        Data $importData,
        ResourceConnection $resource,
        Helper $resourceHelper,
        ProcessingErrorAggregatorInterface $errorAggregator
    ) {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->errorAggregator = $errorAggregator;

        $this->initMessageTemplates();
    }

    public function getEntityTypeCode(): string
    {
        return static::ENTITY_CODE;
    }

    public function getValidColumnNames(): array
    {
        return $this->validColumnNames;
    }

    /**
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validateRow(array $rowData, $rowNum): bool
    {
        /**
         * Add your validations for specific columns, eg.
         * $name = $rowData['name'] ?? '';
         *
         * if (!$name) {
         *     $this->addRowError('NameIsRequired', $rowNum);
         * }
         *
         * For each validation add entry to $this->_messageTemplates array
         */

        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }

        $this->_validatedRows[$rowNum] = true;

        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    protected function _importData(): bool
    {
        switch ($this->getBehavior()) {
            case Import::BEHAVIOR_DELETE:
                $this->deleteEntity();
                break;
            case Import::BEHAVIOR_REPLACE:
                $this->saveAndReplaceEntity();
                break;
            case Import::BEHAVIOR_APPEND:
                $this->saveAndReplaceEntity();
                break;
        }

        return true;
    }

    private function deleteEntity(): bool
    {
        $rows = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            foreach ($bunch as $rowNum => $rowData) {
                $this->validateRow($rowData, $rowNum);

                if (!$this->getErrorAggregator()->isRowInvalid($rowNum)) {
                    $rowId = $rowData[static::ENTITY_ID_COLUMN];
                    $rows[] = $rowId;
                }

                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);
                }
            }
        }

        if ($rows) {
            return $this->deleteEntityFinish(array_unique($rows));
        }

        return false;
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function saveAndReplaceEntity(): void
    {
        $behavior = $this->getBehavior();
        $rows = [];
        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityList = [];

            foreach ($bunch as $rowNum => $row) {
                if (!$this->validateRow($row, $rowNum)) {
                    continue;
                }

                if ($this->getErrorAggregator()->hasToBeTerminated()) {
                    $this->getErrorAggregator()->addRowToSkip($rowNum);

                    continue;
                }

                $rowId = $row[static::ENTITY_ID_COLUMN];
                $rows[] = $rowId;
                $columnValues = [];

                foreach ($this->getAvailableColumns() as $columnKey) {
                    $columnValues[$columnKey] = $row[$columnKey];
                }

                $entityList[$rowId][] = $columnValues;
                $this->countItemsCreated += (int) !isset($row[static::ENTITY_ID_COLUMN]);
                $this->countItemsUpdated += (int) isset($row[static::ENTITY_ID_COLUMN]);
            }

            if (Import::BEHAVIOR_REPLACE === $behavior) {
                if ($rows && $this->deleteEntityFinish(array_unique($rows))) {
                    $this->saveEntityFinish($entityList);
                }
            } elseif (Import::BEHAVIOR_APPEND === $behavior) {
                $this->saveEntityFinish($entityList);
            }
        }
    }

    private function saveEntityFinish(array $entityData): bool
    {
        if ($entityData) {
            $tableName = $this->connection->getTableName(static::TABLE);
            $rows = [];

            foreach ($entityData as $entityRows) {
                foreach ($entityRows as $row) {
                    $rows[] = $row;
                }
            }

            if ($rows) {
                $this->connection->insertOnDuplicate($tableName, $rows, $this->getAvailableColumns());

                return true;
            }
        }

        return false;
    }

    private function deleteEntityFinish(array $entityIds): bool
    {
        if ($entityIds) {
            try {
                $this->countItemsDeleted += $this->connection->delete(
                    $this->connection->getTableName(static::TABLE),
                    $this->connection->quoteInto(static::ENTITY_ID_COLUMN . ' IN (?)', $entityIds)
                );

                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    private function getAvailableColumns(): array
    {
        return $this->validColumnNames;
    }

    private function initMessageTemplates()
    {
        foreach ($this->_messageTemplates as $errorCode => $message) {
            $this->addMessageTemplate($errorCode, __($message));
        }

        return $this;
    }
}
