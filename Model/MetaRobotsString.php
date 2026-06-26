<?php

namespace MageOS\MetaRobotsTag\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Store\Model\ScopeInterface;
use MageOS\MetaRobotsTag\Api\SetMetaRobotsInterface;

class MetaRobotsString
{
    public const XML_PATH_DEFAULT_ROBOTS = 'design/search_engine_robots/default_robots';

    public function __construct(
        protected readonly ScopeConfigInterface $scopeConfig,
        protected readonly SetMetaRobotsInterface $setMetaRobots
    ) {
    }

    public function execute(DataObject $entity): ?string
    {
        $default = (string)$this->scopeConfig->getValue(
            self::XML_PATH_DEFAULT_ROBOTS,
            ScopeInterface::SCOPE_STORE
        );

        $robots = array_values(array_filter(array_map('trim', explode(',', $default))));
        $robots = $this->setMetaRobots->execute($robots, $entity);
        $robots = array_values(array_filter(array_map(static fn ($value): string => (string)$value, $robots)));

        return $robots ? implode(',', $robots) : null;
    }
}
