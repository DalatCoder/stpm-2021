<?php

namespace Ninja;

class StringParser
{
    protected $variables;

    public function __construct($variables = array())
    {
        $this->variables = $variables;
    }

    protected function eval_block($matches)
    {
        if( is_array($this->variables) && count($this->variables) )
        {
            foreach($this->variables as $var_name => $var_value)
            {
                $$var_name = $var_value;
            }
        }

        $eval_end = '';

        if( $matches[1] == '<?=' || $matches[1] == '<?php' )
        {
            if( $matches[2][count($matches[2]-1)] !== ';' )
            {
                $eval_end = ';';
            }
        }

        $return_block = '';

        eval('$return_block = ' . $matches[2] . $eval_end);

        return $return_block;
    }

    public function parse($string)
    {
        preg_match_all('/(\<\?=|\<\?php=|\<\?php)(.*?)\?\>/', $string, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $value) {
            
        }
        
        return preg_replace_callback('/(\<\?=|\<\?php=|\<\?php)(.*?)\?\>/', array(&$this, 'eval_block'), $string);
    }
}
