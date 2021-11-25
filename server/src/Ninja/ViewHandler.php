<?php

namespace Ninja;

class ViewHandler
{
    const EXTEND_PATTERN = '/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i';
    const BLOCK_PATTERN = '/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is';
    const YIELD_PATTERN = '/{% ?yield ?(.*?) ?%}/i';
    const ECHO_SUGAR_PATTERN = '~\{{\s*(.+?)\s*\}}~is';
    const ESCAPED_ECHO_SUGAR_PATTER = '~\{{{\s*(.+?)\s*\}}}~is';
    const PHP_SUGAR_PATTERN = '~\{%\s*(.+?)\s*\%}~is';
    
    private $general_args;

    private string $view_directory = '';
    private array $block_sections = [];
    
    public function set_general_args($general_args)
    {
        $this->general_args = $general_args;
    }
    
    public function set_view_directory($directory_path): ViewHandler
    {
        $this->view_directory = rtrim($directory_path, '/') . '/';
        return $this;
    }

    public function render($template_file_path, $template_args = [])
    {
        try {
            $template_file_path = ltrim($template_file_path, '/');

            if (empty($this->view_directory))
                $this->load_view_path_from_configuration();

            $source = $this->include_files($template_file_path);
            
            $source = $this->compile_source($source);

            extract($template_args ?? []);
            extract($this->general_args ?? [], EXTR_SKIP);

            // TODO: Dangerous code, replace me later
            eval("?> $source <?php");

            exit();
        }
        catch (NinjaException $exception) {
            $this->handle_on_view_not_found($exception);
        }
    }
    
    private function handle_on_view_not_found(NinjaException $exception)
    {
        echo $exception->getMessage();
    }
    
    private function load_view_path_from_configuration()
    {
        $this->view_directory = ROOT_DIR . '/' . trim(NJConfiguration::get('view_folder'), '/') . '/';
    }

    /**
     * @throws NinjaException
     */
    private function include_files($starter_file_path)
    {
        $file = $this->view_directory . $starter_file_path;
        if (!file_exists($file)) 
            throw new NinjaException('Đường dẫn layout không tồn tại: ' . $file);
        
        $source = file_get_contents($file);
        
        preg_match_all(self::EXTEND_PATTERN, $source, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $value) {
            $source = str_replace($value[0], $this->include_files($value[2]), $source);
        }
        
        // Clear unnecessary 'extend' 'include'
        return preg_replace(self::EXTEND_PATTERN, '', $source);
    }

    private function compile_source($source)
    {
        $source = $this->compile_block_syntax($source);
        $source = $this->compile_yield_syntax($source);
        $source = $this->compile_echo_sugar_syntax($source);
        $source = $this->compile_escaped_echo_sugar_syntax($source);

        return $this->compile_php_sugar_syntax($source);
    }

    private function compile_block_syntax($source)
    {
        preg_match_all(self::BLOCK_PATTERN, $source, $matches, PREG_SET_ORDER);

        foreach ($matches as $value) {
            if (!array_key_exists($value[1], $this->block_sections))
                $this->block_sections[$value[1]] = '';

            if (strpos($value[2], '@parent') === false) {
                $this->block_sections[$value[1]] = $value[2];
            } else {
                $this->block_sections[$value[1]] = str_replace('@parent', $this->block_sections[$value[1]], $value[2]);
            }

            $source = str_replace($value[0], '', $source);
        }

        return $source;
    }

    private function compile_yield_syntax($source)
    {
        foreach ($this->block_sections as $block => $value) {
            $source = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $source);
        }

        return preg_replace(self::YIELD_PATTERN, '', $source);
    }

    private function compile_echo_sugar_syntax($source)
    {
        return preg_replace(self::ECHO_SUGAR_PATTERN, '<?php echo $1 ?>', $source);
    }

    private function compile_escaped_echo_sugar_syntax($source)
    {
        return preg_replace(self::ESCAPED_ECHO_SUGAR_PATTER, '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $source);
    }

    private function compile_php_sugar_syntax($source)
    {
        return preg_replace(self::PHP_SUGAR_PATTERN, '<?php $1 ?>', $source);
    }
}
