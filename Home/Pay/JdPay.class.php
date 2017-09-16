<?php

namespace Home\Pay;

class JdPay {
    //v_amount v_moneytype v_oid v_mid v_url key
    protected $v_amount;
    protected $v_oid;
    
    protected $v_moneytype = 'CNY';
    
    protected $v_mid;
    protected $v_url;
    protected $v_key;

    public function __construct($v_oid , $v_amount) {
        $this->v_oid = $v_oid;
        $this->v_amount = $v_amount;

        $this->v_mid = C('V_MID');
        $this->v_url = C('V_URL');
        $this->v_key = C('V_KEY');
    }

    public function form() {
        $form = '<form method=post action="https://pay3.chinabank.com.cn/PayGate">
                <input type=hidden name=v_mid value="%s">
                <input type=hidden name=v_oid value="%s">
                <input type=hidden name=v_amount value="%s">
                <input type=hidden name=v_moneytype value="CNY">
                <input type=hidden name=v_url value="%s">
                <input type=hidden name=v_md5info value="%s">
                <input type="submit" value="支付">
                </form>
                ';

        return sprintf($form , $this->v_mid , $this->v_oid , $this->v_amount , $this->v_url , $this->sign());
    }

    public function sign() {
        $sign = $this->v_amount . $this->v_moneytype . $this->v_oid . $this->v_mid . $this->v_url . $this->v_key;
        return strtoupper(md5($sign));
    }
}

?>