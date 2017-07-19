<?php
namespace Cedar;

use Cedar\Exception\InvalidConfiguration;

class Service
{

    protected $version;
    protected $name;
    protected $config;
    protected $url;
    protected $params;
    protected $routeParams;
    protected $headers;
    protected $verifySSL;

    public function __construct($serviceName = null, $version = 'v1', array $config = [])
    {
        $this->setVersion($version);
        $this->setConfig($config);
        $this->name = $serviceName ? $serviceName : strtolower(class_basename($this));
    }

    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function setConfig(array $config = [])
    {
        if (!empty($config)) {
            $this->config = $config;
            $this->validateConfig();
        }

        return $this;
    }

    protected function validateConfig()
    {
        $this->config['versions'][$this->version]['params'] = [];
        $config = $this->config['versions'][$this->version]['services'][$this->name];

        if (!isset($config['params'])) {
            $config['params'] = [];
        }

        if (!isset($config['headers'])) {
            $config['headers'] = [];
        }

        if (empty($config['url']) || !in_array($config['type'], ['GET', 'POST']) || !is_array($config['params']) || !is_array($config['headers'])) {
            throw new InvalidConfiguration("Wrong Configuration!");
        }

        $config['params'] = array_merge($this->config['versions'][$this->version]['params'], $config['params']);
        $config['headers'] = array_merge($this->config['versions'][$this->version]['headers'], $config['headers']);

        $this->config['versions'][$this->version]['services'][$this->name] = $config;
    }

    public function build()
    {
        if (empty($this->config)) {
            throw new InvalidConfiguration("Wrong Configuration!");
        }

        $this->init();
        return $this;
    }

    protected function init()
    {
        $config = $this->config['versions'][$this->version]['services'][$this->name];

        $this->parseUrl($config['url']);
        $this->headers = $config['headers'];

        $this->params = $this->_getArrValuesOrDefault($config['params']);
        $type = empty($config['type']) ? 'get' : $config['type'];
        $this->type = in_array(strtoupper($type), ['GET', 'POST']) ? strtoupper($type) : 'GET';

        $ssl = !empty($config['ssl']) ? $config['ssl'] : false;
        $this->verifySSL = $ssl !== 'false' ? ((bool)$ssl) : false;

        unset($this->config);
    }

    protected function parseUrl($url)
    {
        preg_match_all('~\{([^\}]+)\}~', $url, $matches);

        if (count($matches) > 1) {
            foreach ($matches[1] as $q) {
                $parts = explode('=', $q);
                if (count($parts) > 1) {
                    $this->routeParams[array_shift($parts)] = implode('=', $parts);
                } else {
                    $this->routeParams[$parts[0]] = null;
                }
            }
        }

        $versionUrlPerfix = $this->version == 'default' ? '' : $this->version;
        $urlPerfix = isset($this->config['versions'][$this->version]['url_perfix']) ? $this->config['versions'][$this->version]['url_perfix'] . '/' : $versionUrlPerfix;
        $this->url = $this->config['versions'][$this->version]['url'] . '/' . $urlPerfix . '/' . trim($url, '/');

    }

    private function _getArrValuesOrDefault($arr)
    {
        $ret = [];
        foreach ($arr as $key => $value) {
            if (is_int($key)) {
                $ret[$value] = null;
            } else {
                $ret[$key] = $value;
            }
        }
        return $ret;
    }

    public function get($needle = null)
    {
        return empty($needle)
            ? $this->getResponse()
            : $this->getResponseByKey($needle);
    }

    public function getJson($array = false){
        $x = $this->get();
        return json_decode($x,$array);
    }
    protected function getResponse()
    {
        $post = false;

        $this->applyRouteParams();

        switch ($this->type) {
            case 'POST':
                $post = $this->getPostData();
                break;

            default:
                if ($q = $this->getQueryString()) {
                    $this->url .= (strpos($this->url, '?') ? '&' : '?') . $q;
                }
                break;
        }

        return $this->make($post);
    }

    protected function applyRouteParams()
    {
        foreach ($this->routeParams as $key => $value) {
            $this->url = str_replace("{{$key}}", $value, $this->url);
        }
    }

    protected function getPostData()
    {
        return json_encode($this->params);
    }

    protected function getQueryString()
    {
        $ret = [];
        foreach ($this->params as $key => $value) {
            $ret[] = $key . '=' . $value;
        }
        return implode('&', $ret);
    }

    protected function make($postData = [])
    {
        $ch = curl_init($this->url);

        if ($this->type != 'GET') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, true);
        }

        $headers = $this->getHeaders();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch,CURLOPT_ENCODING , "gzip");
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, config('cedar.timeout', 300));
        
        $output = curl_exec($ch);

        if ($output === false) {
            throw new \ErrorException(curl_error($ch));
        }

        curl_close($ch);
        return $output;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach ($this->headers as $k => $v) {
            $headers[] = "{$k}: {$v}";
        }
        return $headers;
    }

    protected function getResponseByKey($key)
    {
        $response = json_decode($this->getResponse(), true);
        if (empty($response)) {
            return null;
        }

        $response = array_dot($response);
        return array_get($response, $key);
    }

    public function getParamByKey($key)
    {
        if (array_key_exists($key, array_dot($this->getParams()))) {
            return array_get($this->params, $key);
        }
    }

    public function getParams()
    {
        return array_merge($this->params, $this->routeParams);
    }

    public function setParams(array $params)
    {
        foreach ($params as $key => $value) {
            $this->setParamByKey($key, $value);
        }

        return $this;
    }

    public function setParamByKey($key, $value)
    {
        $paramArr = array_dot($this->params);
        $routeParamArr = array_dot($this->routeParams);

        if (array_key_exists($key, $paramArr)) {
            array_set($this->params, $key, $value);
        } else if (array_key_exists($key, $routeParamArr)) {
            array_set($this->routeParams, $key, $value);
        }

        return $this;
    }
}
