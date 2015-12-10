<?php 

namespace Modera\DemoBundle\Utils;

class RawToJSONTree
{

    public function convert($path){
$handle = fopen($path, "r");
$output = array();
$nodes_grouped = array();
$node_references = array();
$tree = array();
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $line = explode('|', $line);
        $node = array();
        //Childrens fill later. Not to constantly iterate the array
        $name = trim($line[2]);
        $id =  trim($line[0]);
        $parent_id = trim($line[1]);
        $node['text'] = $name;
        $node['id'] = $id;
        $node['children'] = array();
        $nodes_grouped[$parent_id][] = &$node;
        $node_references[$id] = &$node;
        unset($node);
    }

    fclose($handle);
    
}
//sort nodes alphbetical on each level
//fill tree
foreach ($nodes_grouped as $parent_id => &$level) {
    $this->array_sort_by_column($level, 'text', SORT_ASC, SORT_STRING);
    
    if ($parent_id == 0){
        $tree = $level;
    }else{
        $node_references[$parent_id]['children'] = $level;
    }
    
}
$json = json_encode($tree);

return $json;
    }
    
    protected function array_sort_by_column(&$arr, $col, $dir = SORT_ASC, $flag = SORT_STRING) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }
    
        array_multisort($sort_col, $dir, $flag, $arr);
    }
    
}
