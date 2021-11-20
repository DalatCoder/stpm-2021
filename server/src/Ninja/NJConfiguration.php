<?php

namespace Ninja;

class NJConfiguration
{
    static $configuration;
    
    public static function read_config_file()
    {
        try {
            $config_file_path = ROOT_DIR . '/ninja-config.json';

            if (!file_exists($config_file_path))
                throw new NinjaException('Đường dẫn chứa tập tin cấu hình không tồn tại: ' . $config_file_path);
            
            $file_content = file_get_contents($config_file_path);
            
            $json_content = json_decode($file_content);
            
            $json_error = json_last_error();
            if ($json_error !== JSON_ERROR_NONE) 
                throw new NinjaException('Lỗi trong quá trình chuyển đổi tập tin cấu hình, kiểm tra lại cấu trúc JSON');
            
            self::$configuration = $json_content;
            return $json_content;
        }
        
        catch (NinjaException $exception) {
            echo $exception->getMessage();
        }
    }
    
    public static function get($key)
    {
        if (!self::$configuration)
            self::read_config_file();
        
        $json = self::$configuration;
        
        if (empty($json))
            $json = new \stdClass();
        
        return $json->{$key} ?? null;
    }
    
    public static function set($key, $value)
    {
        if (!self::$configuration)
            self::read_config_file();

        $json = self::$configuration;

        if (empty($json))
            $json = new \stdClass();
        
        $json->{$key} = $value;

        $config_file_path = ROOT_DIR . '/ninja-config.json';
        file_put_contents($config_file_path, json_encode($json));
        
        self::$configuration = $json;
    }
}
