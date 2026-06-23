<?php

namespace MageOS\MetaRobotsTag\Model\Resolver;

use Magento\Catalog\Model\ResourceModel\Category as CategoryResource;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use MageOS\MetaRobotsTag\Api\AttributesProviderInterface;
use MageOS\MetaRobotsTag\Model\MetaRobotsString;

class CategoryMetaRobots implements ResolverInterface
{
    public function __construct(
        protected readonly CategoryResource $categoryResource,
        protected readonly AttributesProviderInterface $attributesProvider,
        protected readonly DataObjectFactory $dataObjectFactory,
        protected readonly MetaRobotsString $metaRobotsString
    ) {
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ): ?string {
        $model = $value['model'] ?? null;
        if (!$model instanceof DataObject) {
            return null;
        }

        $categoryId = (int)$model->getId();
        if (!$categoryId || !$context instanceof ContextInterface) {
            return null;
        }

        $storeId = (int)($context->getExtensionAttributes()->getStore()?->getId() ?? 0);
        $flags = [];
        foreach (array_keys($this->attributesProvider->getAttributes()) as $attributeCode) {
            $flags[$attributeCode] = $this->categoryResource->getAttributeRawValue($categoryId, $attributeCode, $storeId);
        }

        return $this->metaRobotsString->execute($this->dataObjectFactory->create(['data' => $flags]));
    }
}
