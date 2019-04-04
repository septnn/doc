<?php namespace Todaymade\Daux\Extension;

use Todaymade\Daux\Tree\Root;

class Processor extends \Todaymade\Daux\Processor
{
    public function manipulateTree(Root $root)
    {
    	$this->format($root);
        // print_r($root->dump());exit;
        // echo '111111111'.PHP_EOL;
    }

    public function format($root)
    {
    	foreach ($root as $key => $value) {
    		print_r($value->path);exit;
    	}
    }
}
