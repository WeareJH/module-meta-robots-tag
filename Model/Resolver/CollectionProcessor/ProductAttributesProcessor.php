<?php

namespace MageOS\MetaRobotsTag\Model\Resolver\CollectionProcessor;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Product\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\GraphQl\Model\Query\ContextInterface;
use MageOS\MetaRobotsTag\Api\AttributesProviderInterface;

class ProductAttributesProcessor implements CollectionProcessorInterface
{
    public function __construct(
        protected readonly AttributesProviderInterface $attributesProvider
    ) {
    }

    public function process(
        Collection $collection,
        SearchCriteriaInterface $searchCriteria,
        array $attributeNames,
        ?ContextInterface $context = null
    ): Collection {
        foreach (array_keys($this->attributesProvider->getAttributes()) as $attributeCode) {
            $collection->addAttributeToSelect($attributeCode);
        }

        return $collection;
    }
}
