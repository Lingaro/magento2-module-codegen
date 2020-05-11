<?php

namespace Codegen\Model\Setup\Patch\Data;

use Codegen\Model\Api\Data\LlamaInterface;
use Codegen\Model\Api\Data\LlamaInterfaceFactory;
use Codegen\Model\Api\LlamaRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class PlayWithLlamas implements DataPatchInterface
{
    /**
     * @var LlamaRepositoryInterface
     */
    private $llamaRepository;

    /**
     * @var LlamaInterfaceFactory
     */
    private $llamaFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    public function __construct(
        LlamaRepositoryInterface $llamaRepository,
        LlamaInterfaceFactory $llamaFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    )
    {
        $this->llamaRepository = $llamaRepository;
        $this->llamaFactory = $llamaFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    public function apply()
    {
        // save
        $this->addLlama('Foo', 123.456);
        $this->addLlama('Bar', 654.321);

        // getList
        $filters = [
            $this->filterBuilder
                ->setField('name')
                ->setValue('Foo')
                ->create()
        ];
        $llamas = $this->llamaRepository->getList(
            $this->searchCriteriaBuilder
                ->addFilters($filters)
                ->create()
        )->getItems();

        // getById
        /** @var LlamaInterface $llama */
        $llama = array_pop($llamas);
        $llama = $this->llamaRepository->getById($llama->getId());

        // delete
        $this->llamaRepository->delete($llama);
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    private function addLlama(string $name, float $points): void
    {
        $llama = $this->llamaFactory->create();
        $llama->setName($name);
        $llama->setPoints($points);
        $this->llamaRepository->save($llama);
    }
}