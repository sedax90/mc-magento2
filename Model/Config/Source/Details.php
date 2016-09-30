<?php
/**
 * Ebizmarts_MailChimp Magento JS component
 *
 * @category    Ebizmarts
 * @package     Ebizmarts_MailChimp
 * @author      Ebizmarts Team <info@ebizmarts.com>
 * @copyright   Ebizmarts (http://ebizmarts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


namespace Ebizmarts\MailChimp\Model\Config\Source;

class Details implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var null
     */
    private $_options = null;
    /**
     * @var \Ebizmarts\MailChimp\Helper\Data|null
     */
    private $_helper  = null;

    /**
     * @param \Ebizmarts\MailChimp\Helper\Data $helper
     * @param \Ebizmarts\MailChimp\Model\Api $api
     */
    public function __construct(
        \Ebizmarts\MailChimp\Helper\Data $helper
    ) {
    
        $this->_helper  = $helper;
        if ($this->_helper->getApiKey()) {
            $api = $this->_helper->getApi();
            try {
                $this->_options = $api->root->info();
            } catch (\Exception $e) {
                $this->_helper->log($e->getMessage());
            }
        } else {
            $this->_options = '--- Enter your API Key ---';
        }
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $ret = '';
        if (is_array($this->_options)) {
            if (isset($this->_options['account_name'])) {
                $ret = [
                    ['value' => 'Username', 'label' => $this->_options['account_name']],
                    ['value' => 'Data uploaded to MailChimp', 'label' => ''],
                    ['value' => '    Total Subscribers', 'label' => $this->_options['total_subscribers']]
                ];
                if (isset($this->_options['store_exists']) && $this->_options['store_exists']) {
                    $ret = array_merge([
                        ['value' => '  Total Customers', 'label' => $this->_options['total_customers']],
                        ['value' => '  Total Products', 'label' => $this->_options['total_products']],
                        ['value' => '  Total Orders', 'label' => $this->_options['total_orders']],
                        ['value' => '  Total Carts', 'label' => $this->_options['total_carts']]
                    ], $ret);
                }
            }
        } elseif (!$this->_options) {
            $ret = [
                ['value' => 'Error', 'label' => __('--- Invalid API Key ---')]
            ];
        } else {
            $ret = [['value' => 'Important', 'label' => __($this->_options)]];
        }
        return $ret;
    }
}