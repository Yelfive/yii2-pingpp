<?php

namespace fk\pingpp;

use Pingpp\Charge;
use Pingpp\Pingpp;
use Pingpp\Transfer;
use yii\base\Object;

/**
 * @author Felix Huang <yelfivehuang@gmail.com>
 */
class Component extends Object
{

    public $apiKey;
    public $appId;
    public $privateKeyPath;

    public $type = 'b2c';
    /**
     * @var string Options are as follows:
     * - alipay    支付宝 APP 支付
     * - alipay_wap    支付宝手机网页支付
     * - alipay_pc_direct    支付宝 PC 网页支付
     * - alipay_qr    支付宝当面付
     * - bfb    百度钱包移动快捷支付
     * - bfb_wap    百度钱包手机网页支付
     * - cnp_u    应用内快捷支付（银联）
     * - cnp_f    应用内快捷支付（外卡）
     * - cp_b2b    银联企业网银支付
     * - upacp    银联全渠道支付，即银联 APP 支付（2015 年 1 月 1 日后的银联新商户使用。若有疑问，请与 Ping++ 或者相关的收单行联系）
     * - upacp_wap    银联全渠道手机网页支付（2015 年 1 月 1 日后的银联新商户使用。若有疑问，请与 Ping++ 或者相关的收单行联系）
     * - upacp_pc    银联 PC 网页支付
     * - wx    微信支付，即微信 APP 支付
     * - wx_pub    微信公众号支付
     * - wx_pub_qr    微信公众号扫码支付
     * - wx_wap    微信 WAP 支付
     * - yeepay_wap    易宝手机网页支付
     * - jdpay_wap    京东手机网页支付
     * - fqlpay_wap    分期乐支付
     * - qgbc_wap    量化派支付
     * - cmb_wallet    招行一网通
     * - applepay_upacp    Apple Pay
     * - mmdpay_wap    么么贷
     */
    public $channel = 'wx';

    /**
     * @var string Currency type
     * - cny    Chinese Yuan
     */
    public $currency = 'cny';

    protected $extra;

    protected $metadata;

    public function init()
    {
        parent::init();
        $this->setApiKey($this->apiKey);
    }

    /**
     * Actually, this is a payment method,
     * connected to third-part payment platform such as WeChat and AliPay
     * via Ping Plus Plus
     *
     * @param string $orderNo
     * @param float $amount
     * @param string $subject Title for the charge/goods
     * @param string $body Description for the charge/goods
     * @param null $extra
     * @param null|int $expire Unix timestamp before the order expired
     * @param null $metadata
     * @param null|string $description Extra information for the charge/goods
     * @return Charge
     */
    public function charge($orderNo, $amount, $subject, $body, $extra = null, $expire = null, $metadata = null, $description = null)
    {
        $params = [
            'order_no' => $orderNo,
            'app' => ['id' => $this->appId],
            'channel' => $this->channel,
            'amount' => $amount,
            'client_ip' => $this->getClientIp(),
            'currency' => $this->currency,
            'subject' => $subject,
            'body' => $body,
        ];
        if ($extra) $params['extra'] = $extra;
        if ($expire) $params['time_expire'] = $expire;
        if ($metadata) $params['metadata'] = $metadata;
        if ($description) $params['description'] = $description;

        $charge = Charge::create($params);
        return $charge;
    }

    /**
     * Transfer from our's account on third-part platform
     * to
     * clients on our platform
     * Currently, it supports only WeChat
     * @param string $orderNo
     * @param float $amount
     * @param string $recipient Receiver id  on the third-part platform, openid for example
     * @param string $description Message about this transfer
     * @param null $metadata
     * @param null $extra
     * @return Transfer
     */
    public function transfer($orderNo, $amount, $recipient, $description, $metadata = null, $extra = null)
    {
        $params = [
            'app' => ['id' => $this->appId],
            'channel' => $this->channel,
            'order_no' => $orderNo,
            'amount' => $amount,
            'type' => $this->type,
            'currency' => $this->currency,
            'recipient' => $recipient,
            'description' => $description,
        ];
        if ($metadata) $params['metadata'] = $metadata;
        if ($extra) $params['extra'] = $extra;
        return Transfer::create($params);
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    private $_clientIp;

    /**
     * Usage:
     * Yii::$app->pingpp->setClientIp('127.0.0.1')->charge();
     * @param $clientIp
     * @return $this
     */
    public function setClientIp($clientIp)
    {
        $this->_clientIp = $clientIp;
        return $this;
    }

    public function getClientIp()
    {
        if (!$this->_clientIp) $this->_clientIp = $_SERVER['REMOTE_ADDR'];
        return $this->_clientIp;
    }

    public function setApiKey($key)
    {
        Pingpp::setApiKey($key);
    }
}