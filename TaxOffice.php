<?php

/**
 * Client dosyasını da yanına bıraktım onuda orda sayfaya dahil edenilirsiniz bu sayede çalışıtırsanız düzenlem ihtiyacı olmaz
 * Vergi ofisi sorgulama ve doğrulama işlemi
 **/

class TaxOffice{
	protected $login_url ="https://ivd.gib.gov.tr/tvd_server/assos-login";
	protected $query_url ="https://ivd.gib.gov.tr/tvd_server/dispatch";
	protected $token = false;
	
	protected function createToken(){
		$data["assoscmd"] = "cfsession";
		$data["rtype"] = "json";
		$data["fskey"] = "intvrg.fix.session";
		$data["fuserid"] = "INTVRG_FIX";
		$res = $this->send($this->login_url, $this->data_encode_url($data),'POST',['Content-Type: application/x-www-form-urlencoded']);
		if($res != "assoscmd=login|logout"){
			$this->token = json_decode($res)->token;
		}
	}

	public function check_tax_office($vkn1 = "", $tckn1="", $iller="34", $vergidaireleri){
		$res = false;
		$this->createToken();
		if($this->token && ($vkn1 || $tckn1) && $iller && $vergidaireleri){
			$jp["dogrulama"]['vkn1'] = $vkn1;
			$jp["dogrulama"]['tckn1'] = $tckn1;
			$jp["dogrulama"]['iller'] = "0".$iller;
			$jp["dogrulama"]['vergidaireleri'] = $vergidaireleri;
			$data["cmd"] = "vergiNoIslemleri_vergiNumarasiSorgulama";
			$data["callid"] = "4671d0d214b81-31";
			$data["pageName"] = "R_INTVRG_INTVD_VERGINO_DOGRULAMA";
			$data["token"] = $this->token;
			$data["jp"] = json_encode($jp); //sorgulanan data
			$data = $this->data_encode_url($data);
			$res = $this->send($this->query_url, $data,'POST',['Content-Type: application/x-www-form-urlencoded']);
			$res = ($res !="assoscmd=login|logout")? json_decode($res,true): false;
			if(!isset($res['data']) || count($res['data']) < 1){
				$res = false;
			}
		}
		return $res;
	}

	public function check_tax_sheet($vkn1 = "", $tckn1="", $iller="34", $vergidaireleri="034296"){
		$res = false;
		$this->createToken();
		if($this->token && ($vkn1 || $tckn1) && $iller && $vergidaireleri){
			$jp['islemTip'] = "0";
			$jp['sorgulayanTckn'] = "";
			$jp['sorgulanacakVkn'] = $vkn1;
			$jp['sorgulanacakTckn'] = $tckn1;
			$jp['sorgulanacakVDIl'] = "0".$iller;
			$jp['sorgulanacakVDAd'] = $vergidaireleri;
			$data["cmd"] = "vergiLevhasiDetay_sorgula";
			$data["callid"] = "4671d0d214b81-31";
			$data["pageName"] = "P_INTVRG_INTVD_E_VERGI_LEVHA_SORGULA";
			$data["token"] = $this->token;
			$data["jp"] = json_encode($jp); //sorgulanan data
			$data = $this->data_encode_url($data);
			$res = $this->send($this->query_url, $data,'POST',['Content-Type: application/x-www-form-urlencoded']);
			if($res == "assoscmd=login|logout" || isset(json_decode($res)->error)){
				$res = false;
			}else{
				$res = json_decode($res,true);
			}
		}
		return $res;
	}

	protected function data_encode_url($data){//array den url_encoding hale getirir
		return http_build_query($data);
	}

	protected function send($url,$data,$type,$content_type){
		$res = false;
		$http = new HttpClient();
		$res = $http->url($url)->http_header($content_type)->postfields($data)->customrequest($type)->send();
		return $res;
	}
}
