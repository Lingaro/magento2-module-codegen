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

class {{ notificationName|pascal }}
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
     * {{ notificationName|pascal }} constructor.
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
     * @param null $storeId
     * @throws LocalizedException
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function send(
        $recipientEmail,
        array $templateParams = [],
        int $storeId = null
    ): void {
        $template = '{{ notificationSectionId|snake }}/{{ notificationGroupId|snake }}/{{ notificationName|snake }}';
        $templateParams ['store'] = $this->storeManager->getStore($storeId);

        $templateId = $this->scopeConfig->getValue($template, 'store', $storeId);

        $from = $this->senderResolver->resolve(
            $this->scopeConfig->getValue('sales_email/order/identity', 'store', $storeId),
            $storeId
        );

        $this->transportBuilder->setTemplateIdentifier($templateId);
        $this->transportBuilder->setTemplateOptions(['area' => 'frontend', 'store' => $storeId]);
        $this->transportBuilder->setTemplateVars($templateParams);
        $this->transportBuilder->setFromByScope($from);
        $this->transportBuilder->addTo($recipientEmail);

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
    }
}
