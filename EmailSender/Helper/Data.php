<?php
/**
 * @category  Custom
 * @package   EmailSender
 * @author    Nihal Malik
 * @copyright Copyright (c) EmailSender (neehalmalik8@gmail.com)
 */

declare(strict_types=1);

namespace Custom\EmailSender\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
	const XML_PAYMENT_METHOD      =   'customemailrestrict/general/payment_method';

    const XML_PATH_EMAIL_RESTRICT      =   'customemailrestrict/general/enable_email_restrict_flag';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        protected \Magento\Framework\App\Helper\Context $context,
		protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface,
            ) {
        parent::__construct($context);
		$this->scopeConfig = $scopeInterface;
    }



	/**
     * @return bool
     */
    public function enableEmailRestrict()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_RESTRICT,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
    public function enablePaymentMethods()
    {
        return explode(',', $this->scopeConfig->getValue(
            self::XML_PAYMENT_METHOD,
            ScopeInterface::SCOPE_WEBSITE
        ));
    }
    

    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }

}
