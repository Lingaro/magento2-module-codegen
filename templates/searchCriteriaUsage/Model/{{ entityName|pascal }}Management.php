<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entityName|pascal }}ManagementInterface;
{% if filters is not empty %}
use Magento\Framework\Api\FilterBuilder;
{% endif %}
use Magento\Framework\Api\SearchCriteriaBuilder;
{% if sorts is not empty %}
use Magento\Framework\Api\SortOrderBuilder;
{% endif %}
{% if customEntityRepository is empty %}
use {{ vendorName|pascal }}\{{ moduleName|pascal }}\Api\{{ entityName|pascal }}RepositoryInterface;
{% endif %}

class {{ entityName|pascal }}Management implements {{ entityName|pascal }}ManagementInterface
{
    private {% if customEntityRepository is empty %}{{ entityName|pascal }}RepositoryInterface{% else %}{{ customEntityRepository }}{% endif %} $entityRepository;
    private SearchCriteriaBuilder $searchBuilder;
{% if filters is not empty %}
    private FilterBuilder $filterBuilder;
{% endif %}
{% if sorts is not empty %}
    private SortOrderBuilder $sortOrderBuilder;
{% endif %}

    public function __construct(
        SearchCriteriaBuilder $searchBuilder,
{% if filters is not empty %}
        FilterBuilder $filterBuilder,
{% endif %}
{% if sorts is not empty %}
        SortOrderBuilder $sortOrderBuilder,
{% endif %}
{% if customEntityRepository is empty %}
        {{ entityName|pascal }}RepositoryInterface $entityRepository
{% else %}
        {{ customEntityRepository }} $entityRepository
{% endif %}
    ) {
        $this->searchBuilder = $searchBuilder;
{% if filters is not empty %}
        $this->filterBuilder = $filterBuilder;
{% endif %}
{% if sorts is not empty %}
        $this->sortOrderBuilder = $sortOrderBuilder;
{% endif %}
        $this->entityRepository = $entityRepository;
    }

    public function {{ functionName|camel }}(
{% for item in filters %}
{% if item.value is empty %}
        {{ item.type }} ${{ item.field|camel }},
{% endif %}
{% endfor %}
        int $pageSize = 0,
        int $currentPage = 1
    ): array {
{% for item in filters %}
        ${{ item.field|camel }}Filter = $this->filterBuilder
            ->setField('{{ item.field }}')
{% if item.value %}
            ->setValue('{{ item.value }}')
{% else %}
            ->setValue(${{ item.field|camel }})
{% endif %}
            ->setConditionType('{{ item.conditionType }}')
            ->create();
{% endfor %}

{% if conditionType == 'OR' %}
        $this->searchBuilder
            ->addFilters([
                {% for item in filters %}
                ${{ item.field|camel }}Filter,
                {% endfor %}
            ]);
{% elseif conditionType == 'AND' %}
{% for item in filters %}
        $this->searchBuilder->addFilters([${{ item.field|camel }}Filter]);
{% endfor %}
{% elseif conditionType == 'mixed' %}
        // mix it as you need e.g.
        // $this->searchBuilder->addFilters([$filter1, $filter2]);
        // $this->searchBuilder->addFilters([filter2]);
        // will return: WHERE (field1 like value1 OR field2 eq value2) AND (field3 eq value3)
{% for item in filters %}
        $this->searchBuilder->addFilters([${{ item.field|camel }}Filter]);
{% endfor %}
{% endif %}
{% if sorts is not empty %}

        $sorts = [];
{% for item in sorts %}
        $sorts[] = $this->sortOrderBuilder
            ->setField('{{ item.field }}')
            ->setDirection('{{ item.direction }}')
            ->create();
{% endfor %}
        $this->searchBuilder->setSortOrders($sorts);
{% endif %}

        if (!empty($pageSize)) {
            $this->searchBuilder->setPageSize($pageSize);
            $this->searchBuilder->setCurrentPage($currentPage);
        }

        return $this->entityRepository
            ->getList($this->searchBuilder->create())
            ->getItems();
    }
}
