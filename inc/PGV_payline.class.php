<?php
/**
 * Payline
 *
 * Payline getway class
 *
 * @copyright	(c) 2012 Payline.ir
 * @author		Payline developement team info@payline.ir
 * @license		http://www.opensource.org/licenses/gpl-3.0.html
 * @version		1.0
 */
Class PGV_Payline {

	/**
	 * Payline API Key
	 *
	 * @var integer
	 */
	public $api;

	/**
	 * Payline price payment
	 *
	 * @var integer
	 */
	public $Price;

	/**
	 * Return URL in from Payline
	 *
	 * @var string
	 */
	public $ReturnPath;

    /**
	 * Return trans_id
	 *
	 * @var string
	 */
	public $RefNumber;

	/**
	 * Constructors
	 */
	public function __construct() {

	}

    public function send($url,$api,$amount,$redirect){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&amount=$amount&redirect=$redirect");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
    public function get($url,$api,$trans_id,$id_get){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POSTFIELDS,"api=$api&id_get=$id_get&trans_id=$trans_id");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

	/**
	 * Request for payment transactions
	 *
	 * @param  Not param
	 * @return Status request
	 */
	public function Request() {
        $url = 'http://payline.ir/payment/gateway-send';
        $result = $this->send($url,$this->api,$this->Price,$this->ReturnPath);

		if($result > 0 && is_numeric($result)) {
			echo "<meta http-equiv='Refresh' content='0;URL=http://payline.ir/payment/gateway-$result'>";
		} else {
			return $result;
		}

	}

	/**
	 * Verify Payment
	 *
	 * @param  Not param
	 * @return Status verify
	 */
	public function Verify() {
        $url = 'http://payline.ir/payment/gateway-result-second';
        $trans_id = $_POST['trans_id'];
        $id_get = $_POST['id_get'];
        $result = $this->get($url,$this->api,$trans_id,$id_get);
        $this->RefNumber = $trans_id;
        return $result;
	}
}