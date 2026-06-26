<?php

namespace MageOS\MetaRobotsTag\Model\Resolver;

use Magento\Cms\Api\Data\PageInterface;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use MageOS\MetaRobotsTag\Model\MetaRobotsString;

class CmsPageMetaRobots implements ResolverInterface
{
    public function __construct(
        protected readonly PageRepositoryInterface $pageRepository,
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
        if (!isset($value[PageInterface::PAGE_ID])) {
            return null;
        }

        try {
            $page = $this->pageRepository->getById((int)$value[PageInterface::PAGE_ID]);
        } catch (LocalizedException $e) {
            return null;
        }

        if (!$page instanceof DataObject) {
            return null;
        }

        return $this->metaRobotsString->execute($page);
    }
}
