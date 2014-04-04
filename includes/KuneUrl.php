<?php
class KuneUrl
{
    private $url;
    private $server;
    private $hash;

    public function __construct($url)
    {
        $this->url = $url;
        
        # http://www.phpliveregex.com/
        preg_match('/.*\//', $url, $serverMatches);
        preg_match('/#!{0,1}(.*$)/', $url, $hashMatches);
        if (sizeof($serverMatches) > 0)
            $this->server = $serverMatches[0];
        if (sizeof($hashMatches) > 0)
            $this->hash = $hashMatches[1];
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getServer() 
    {
        return $this->server;
    }

    public function isValid() 
    {
        return $this->server != '' && $this->hash != '';
    }

}