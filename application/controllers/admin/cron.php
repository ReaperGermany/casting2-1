<?php

class ExchangeRatesCBRF
{
	var $rates;
	function __construct($date = null)
	//В PHP версии ниже 5 это метод объекта следует переименовать в ExchangeRatesCBRF
	{
		$client = new SoapClient("http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL"); 
		if (!isset($date)) $date = date("Y-m-d"); 
		$curs = $client->GetCursOnDate(array("On_date" => $date));
		$this->rates = new SimpleXMLElement($curs->GetCursOnDateResult->any);
	}
	
	function GetRate ($code)
	{
	//Этот метод получает в качестве параметра цифровой или буквенный код валюты и возвращает ее курс
		$code1 = (int)$code;
		if ($code1!=0) 
		{
			$result = $this->rates->xpath('ValuteData/ValuteCursOnDate/Vcode[.='.$code.']/parent::*');
		}
		else
		{
			$result = $this->rates->xpath('ValuteData/ValuteCursOnDate/VchCode[.="'.$code.'"]/parent::*');
		}
		if (!$result)
		{
			return false; 
		}
		else 
		{
			$vc = (float)$result[0]->Vcurs;
			$vn = (int)$result[0]->Vnom;
			return ($vc/$vn);
		}

	}
}
/**
 * FXWSService class
 *
 *
 *
 * @author    {author}
 * @copyright {copyright}
 * @package   {package}
 */
class FXWSService extends SoapClient {

  public function FXWSService($wsdl = "http://www.newyorkfed.org/markets/fxrates/WebService/v1_0/FXWS.wsdl", $options = array()) {
    parent::__construct($wsdl, $options);
  }

  /**
   *
   *
   * @param
   * @return string
   */
  public function getAllLatestNoonRates() {
    return $this->__call('getAllLatestNoonRates', array(),
      array(
            'uri' => 'http://v1_0.WebService.fxrates.markets',
            'soapaction' => ''
           )
      );
  }

  /**
   *
   *
   * @param string $currency_code
   * @param dateTime $date1
   * @param dateTime $date2
   * @return string
   */
  public function getNoonRates($currency_code, $date1, $date2) {
    return $this->__call('getNoonRates', array(
            new SoapParam($currency_code, 'currency_code'),
            new SoapParam($date1, 'date1'),
            new SoapParam($date2, 'date2')
      ),
      array(
            'uri' => 'http://v1_0.WebService.fxrates.markets',
            'soapaction' => ''
           )
      );
  }

  /**
   *
   *
   * @param dateTime $date1
   * @return string
   */
  public function getAllNoonRates($date1) {
    return $this->__call('getAllNoonRates', array(
            new SoapParam($date1, 'date1')
      ),
      array(
            'uri' => 'http://v1_0.WebService.fxrates.markets',
            'soapaction' => ''
           )
      );
  }

  /**
   *
   *
   * @param string $currency_code
   * @return string
   */
  public function getLatestNoonRate($currency_code) {
    return $this->__call('getLatestNoonRate', array(
            new SoapParam($currency_code, 'currency_code')
      ),
      array(
            'uri' => 'http://v1_0.WebService.fxrates.markets',
            'soapaction' => ''
           )
      );
  }

}


class Cron extends Controller
{
    public function dump()
    {
        $prefs = array(
                'ignore'      => array('requests', 'matches'),
                'format'      => 'gzip',
                'filename'    => 'brocker.sql',
                'add_drop'    => TRUE,
                'add_insert'  => TRUE,
                'newline'     => "\n"
              );
        $filename = "brocker_".date('Y_M_d');

        $this->load->dbutil();
        $backup =& $this->dbutil->backup($prefs);

        $this->load->helper('file');
        write_file('backups/'.$filename.'.gz', $backup);
    }

    public function updateCurrency()
    {
        //$serviceLocation = "http://www.newyorkfed.org/markets/fxrates/WebService/v1_0/FXWS.cfc";
        //$serviceLocation = "http://www.newyorkfed.org/markets/fxrates/WebService/v1_0/FXWS.wsdl";
        //$client = new SoapClient($serviceLocation);

		$rates = new ExchangeRatesCBRF(date('Y-m-d', time()));
		$usd = $rates->GetRate("USD");
		$eur = $rates->GetRate("EUR");
		
		$this->load->model('Rate', 'currency');
		$colect = $this->currency->addFilterByField('av.value', 'EURO')->getCollection();
		if (count($colect)) 
		{
			$id = $colect[0]->getId();
			$this->currency->load($id)->setData('rate', $usd/$eur)->save();
		}
		
		
		/*
        try {
            $client = new FXWSService();
            $client->getAllLatestNoonRates();
			//$client->getAllNoonRates(time());
        }
        catch (Exception $e) {
            var_dump($e->getMessage());
        }*/
    }
	
}
 
