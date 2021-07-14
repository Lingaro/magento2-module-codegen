<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName|raw }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

declare(strict_types=1);

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\Cache;

use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;
use Magento\Framework\Serialize\Serializer\Serialize;

class {{ cacheName|pascal }} extends TagScope
{
    /**
     * Cache type code unique among all cache types
     */
    public const TYPE_IDENTIFIER = '{{ cacheName|snake }}';

    /**
     * Cache tag used to distinguish the cache type from all other cache
     */
    public const CACHE_TAG = '{{ cacheName|snake|upper }}_CACHE_TAG';

    /**
     * Cache lifetime
     */
    public const LIFETIME = {{ lifeTime }};

    private Serialize $serializer;

    private bool $useBinarySerializer = false;

    public function __construct(
        FrontendPool $cacheFrontendPool,
        Serialize $serializer,
        string $tag = self::CACHE_TAG
    ) {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), $tag);
        $this->serializer = $serializer;

        if (\function_exists('igbinary_serialize') && \function_exists('igbinary_unserialize')) {
            $this->useBinarySerializer = true;
        }
    }

    public function getCacheId(...$args): string
    {
        return self::TYPE_IDENTIFIER . '_' . hash('sha256', implode("_", $args));
    }

    /**
     * @param array|bool|float|int|mixed|string|null $data
     * @param string $identifier
     * @param array $tags
     * @param int|null $lifeTime
     * @return bool
     */
    public function save($data, $identifier, array $tags = [], $lifeTime = null): bool
    {
        if ($lifeTime === null) {
            $lifeTime = self::LIFETIME;
        }
        return parent::save($this->_serialize($data), $identifier, $tags, $lifeTime);
    }

    /**
     * @param string $identifier
     * @return array|bool|float|int|mixed|string|null
     */
    public function load($identifier)
    {
        $data = parent::load($identifier);

        if (!empty($data)) {
            return $this->_unserialize($data);
        }
        return $data;
    }

    public function cleanByTags(array $tags): bool
    {
        return $this->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, $tags);
    }

    /**
     * @param array|bool|float|int|mixed|string|null $data
     * @return string|null
     */
    private function _serialize($data): ?string
    {
        if ($this->useBinarySerializer) {
            return igbinary_serialize($data);
        }
        return $this->serializer->serialize($data);
    }

    /**
     * @param $data
     * @return array|bool|float|int|mixed|string|null
     */
    private function _unserialize(string $data)
    {
        if ($this->useBinarySerializer) {
            return igbinary_unserialize($data);
        }
        return $this->serializer->unserialize($data);
    }
}
