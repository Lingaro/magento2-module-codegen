<?php

/**
 * @copyright Copyright © {{ commentsYear }} {{ commentsCompanyName }}. All rights reserved.
 * @author    {{ commentsUserEmail }}
 */

namespace {{ vendorName|pascal }}\{{ moduleName|pascal }}\Model\EmailNotification;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;

class {{ name|pascal }}
{
    /** @var TransportBuilder */
    private $transportBuilder;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var SenderResolverInterface */
    private $senderResolver;

    /**
     * {{ name|pascal }} constructor.
     * @param TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param SenderResolverInterface $senderResolver
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        SenderResolverInterface $senderResolver
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->senderResolver = $senderResolver;
    }

    /**
     * @param string|array $recipientEmail
     * @param array $templateParams
{% if scope == 'frontend' %}
     * @param int|null $storeId
{% endif %}
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function send(
        $recipientEmail,
        array $templateParams = []{% if scope == 'frontend' %},
        int $storeId = null{% endif %}

    ): void
    {
        $templateXmlPath = '{{ configSection|snake }}/{{ configGroup|snake }}/{{ name|snake }}_template';
        $identityXmlPath = '{{ configSection|snake }}/{{ configGroup|snake }}/{{ name|snake }}_identity';

{% if scope == 'frontend' %}
        $store = $this->storeManager->getStore($storeId);
        $storeId = $store->getId();
        $templateParams['store'] = $store;
{% else %}
        $storeId = Store::DEFAULT_STORE_ID;
{% endif %}

        $templateId = $this->scopeConfig->getValue($templateXmlPath, 'store', $storeId);

        $from = $this->senderResolver->resolve(
            $this->scopeConfig->getValue($identityXmlPath, 'store', $storeId),
            $storeId
        );

        $this->transportBuilder->setTemplateIdentifier($templateId);
        $this->transportBuilder->setTemplateOptions(['area' => '{{ scope }}', 'store' => $storeId]);
        $this->transportBuilder->setTemplateVars($templateParams);
        $this->transportBuilder->setFromByScope($from);
        $this->transportBuilder->addTo($recipientEmail);

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
    }
}
