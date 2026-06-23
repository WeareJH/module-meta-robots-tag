<?php

namespace MageOS\MetaRobotsTag\Model\Resolver;

use Magento\Framework\DataObject;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use MageOS\MetaRobotsTag\Model\MetaRobotsString;

class ProductMetaRobots implements ResolverInterface
{
    public function __construct(
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

        return $this->metaRobotsString->execute($model);
    }
}
