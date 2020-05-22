<?php
/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName}}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_{{ sectionId|snake|upper }}_{{ groupId|snake|upper }}_{{ fieldId|snake|upper }} = "{{ sectionId|snake }}/{{ groupId|snake }}/{{ fieldId|snake }}";

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function get{{ fieldId|camel|ucfirst }}(${{ configScope }}Id = null)
    {
        $this->scopeConfig->getValue(
            static::XML_PATH_{{ sectionId|snake|upper }}_{{ groupId|snake|upper }}_{{ fieldId|snake|upper }},
            ScopeInterface::SCOPE_{{ configScope|upper }},
            ${{ configScope }}Id
        );
    }
}