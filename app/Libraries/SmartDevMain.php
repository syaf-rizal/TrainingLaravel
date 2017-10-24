<?php
/**
 * SmartDevMain
 * Versioning 1.0 Alpha
 * Modified by Syafrizal
 */

 namespace App\Libraries;
 use DB;
 error_reporting(E_ERROR);

 class SmartDevMain {

    public function __constructor() { }

    /**
     * Generator UUI PHP v4
     * http://www.php.net/manual/en/function.uniqid.php#94959
     */
    public function generatorUuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    public function setSelectCombo($sQuery, $sKey, $sValue, $iEmpty = FALSE, &$disable = "")
    {
        $arrComboBox = [];
        $objData = DB::select($sQuery);
        if($iEmpty) $arrComboBox[''] = '';
        if(sizeof($objData) > 0)
        {
            $sCodeDisabled = "";
            $arrCodeDisabled = [];
            $arrData = json_decode(json_encode($objData), TRUE);
            foreach ($arrData as $row) 
            {
                if (is_array($disable)) 
                {
                    if ($sCodeDisabled == $row[$disable[0]]) 
                    {
						if (!array_key_exists($row[$sKey], $arrComboBox))
							$arrComboBox[$row[$sKey]] = "&nbsp; &nbsp;&nbsp;&nbsp;" . $row[$sValue];
                    } 
                    else 
                    {
						if (!array_key_exists($row[$disable[0]], $arrComboBox))
							$arrComboBox[$row[$disable[0]]] = $row[$disable[1]];
						if (!array_key_exists($row[$sKey], $arrComboBox))
							$arrComboBox[$row[$sKey]] = "&nbsp; &nbsp;&nbsp;&nbsp;" . $row[$sValue];
					}
					$sCodeDisabled = $row[$disable[0]];
					if (!in_array($sCodeDisabled, $arrCodeDisabled))
						$arrCodeDisabled[] = $sCodeDisabled;
                }
                else
                {
                    $arrComboBox[$row[$sKey]] = str_replace("'", "\'", $row[$sValue]);
                }
            }
            $disable = $arrCodeDisabled;
        }
        return $arrComboBox;
    }

    public function post2Query($array, $except = "")
	{
		$data = array();
		foreach ($array as $a => $b) {
			if (is_array($except)) {
				if (!in_array($a, $except))
					$data[$a] = $b;
			} else {
				$data[$a] = $b;
			}
		}
		return $data;
	}

 }