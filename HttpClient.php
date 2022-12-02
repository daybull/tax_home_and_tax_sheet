<?php

class HttpClient{

    protected $url =  false;
    protected $ssl_verifyhost =  2;
    protected $ssl_verifypeer =  0;
    protected $returntransfer =  1;
    protected $customrequest = 'POST';
    protected $timeout =  1;
    protected $postfields = "";
    protected $http_header = ['Content-Type:application/json'];

	public function send(){
        $result = false;
        if($this->url && $this->http_header){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->ssl_verifyhost);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->returntransfer);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->customrequest);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postfields);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->http_header);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return $result;
    }
    
    public function url($url){ 
        $this->url = $url; 
        return $this;  
    }
    
    public function ssl_verifyhost($ssl_verifyhost){ 
        $this->ssl_verifyhost = $ssl_verifyhost; 
        return $this;  
    }
    
    public function ssl_verifypeer($ssl_verifypeer){ 
        $this->ssl_verifypeer = $ssl_verifypeer; 
        return $this;  
    }
    
    public function returntransfer($returntransfer){ 
        $this->returntransfer = $returntransfer; 
        return $this;  
    }
    
    public function customrequest($customrequest){ 
        $this->customrequest = $customrequest; 
        return $this;  
    }
    
    public function timeout($timeout){ 
        $this->timeout = $timeout; 
        return $this;  
    }

    public function postfields($postfields){
        $this->postfields = $postfields;
        return $this;
    }
    public function http_header($http_header){
        $this->http_header = $http_header;
        return $this;
    }

}
