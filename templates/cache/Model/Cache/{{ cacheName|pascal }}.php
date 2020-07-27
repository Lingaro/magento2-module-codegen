<?php
/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\Cache;

use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;
use Magento\Framework\Serialize\Serializer\Serialize;

class {{ cacheName|pascal }} extends TagScope
{
    /**
     * Cache type code unique among all cache types
     */
    const TYPE_IDENTIFIER = '{{ cacheName|snake }}';

    /**
     * Cache tag used to distinguish the cache type from all other cache
     */
    const CACHE_TAG = '{{ cacheName|snake|upper }}_CACHE_TAG';

    /**
     * Cache lifetime
     */
    const LIFETIME = {{ lifeTime }};

    /**
     * @var Serialize
     */
    protected $serializer;

    /**
     * @var bool
     */
    protected $useBinarySerializer = false;

    /**
     * Cache constructor.
     * @param FrontendPool $cacheFrontendPool
     * @param Serialize $serializer
     * @param string $tag
     */
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

    /**
     * @param mixed ...$args
     * @return string
     */
    public function getCacheId(...$args): string
    {
        return self::TYPE_IDENTIFIER . '_' . md5(implode("_", $args));
    }

    /**
     * @param $data
     * @param $identifier
     * @param array $tags
     * @param null $lifeTime
     * @return bool
     */
    public function save($data, $identifier, array $tags = [], $lifeTime = null)
    {
        if (is_null($lifeTime)) {
            $lifeTime = self::LIFETIME;
        }
        return parent::save($this->_serialize($data), $identifier, $tags, $lifeTime);
    }

    /**
     * @param $identifier
     * @return bool|mixed|string
     */
    public function load($identifier)
    {
        $data = parent::load($identifier);

        if (!empty($data)) {
            return $this->_unserialize($data);
        }
        return $data;
    }

    /**
     * @param array $tags
     * @return bool
     */
    public function cleanByTags(array $tags)
    {
        return $this->clean(\Zend_Cache::CLEANING_MODE_MATCHING_TAG, $tags);
    }

    /**
     * @param $data
     * @return bool|string
     */
    private function _serialize($data)
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
    private function _unserialize($data)
    {
        if ($this->useBinarySerializer) {
            return igbinary_unserialize($data);
        }
        return $this->serializer->unserialize($data);
    }
}
