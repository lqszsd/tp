<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/2
 * Time: 13:25
 */

namespace app\index\model;

use think\Db;
use think\Exception;

class BaseModel extends \think\Model
{
    public static $handle = null;
    public $table_name = "test";
    public $table_array=[];
    public static function getInstance()
    {
        $class = get_called_class();
        if (static::$handle == null) {
            static::$handle = new static();
        }
        return static::$handle;
    }
    const INDEX_TYPE_NONE = false;
    const INDEX_TYPE_INDEX = "INDEX";
    const INDEX_TYPE_UNIQUE = "UNIQUE ";

    public function __construct()
    {
        $this->table_name=__CLASS__;
        $table_info = Db::query("show tables");
        $this->table_info = $table_info;
        $this->createTable();
        $this->tableFileds();
        $this->serachFiled();
    }

    //检查是否存在数据表
    public function existTable()
    {
        $count = count($this->table_info);
        for ($i = 0; $i < $count; $i++) {
            foreach ($this->table_info[$i] as $item) {
                if ($item == config('database.prefix') . $this->table_name) {
                    return true;
                }
            }
        }
        return false;
    }

    //建表
    public function createTable()
    {
        $data = $this->existTable();
        if ($data == false) {
            $profix = config('database.prefix');
            //执行建表操作
            $result = Db::query("CREATE TABLE `{$profix}{$this->table_name}` ( `id` INT NOT NULL AUTO_INCREMENT, `created_at` DATETIME NOT NULL , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP() NOT NULL DEFAULT CURRENT_TIMESTAMP() , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
            if (!$result) {
                throw new Exception("创建失败");
            }
        }
    }

    //获取字段信息
    public function getFiled()
    {
        $profix = config('database.prefix');
        $data = Db::query("SHOW COLUMNS FROM`{$profix}{$this->table_name}`");
        return $data;
    }

    //定义要添加的字段
    public function createFiled($name, $comment, $length, $default,$type, $index)
    {
        return [
            'name' => $name,
            'comment'=>$comment,
            'length'=>$length,
            'default'=>$default,
            'index'=>$index,
            'type'=>$type
        ];
    }
    public function createInt($name,$comment='',$length=11,$default='2',$type='INT',$index=self::INDEX_TYPE_NONE){
        $data=$this->createFiled($name,$comment,$length,$default,$type,$index);
        array_push($this->table_array,$data);
    }
    public function tableFileds(){
        $this->createInt('qaqss','2');
    }
    public function serachFiled(){
        for($i=0;$i<count($this->getFiled());$i++){
                $tablehash[]=$this->getFiled()[$i]['Field'];
        }
        $profix = config('database.prefix');
        for($i=0;$i<$this->table_array;$i++){
            for($j=0;$i<count($tablehash);$j++) {
                if ($tablehash[$j]==$this->table_array[$i]['name']) {
                    dump($tablehash);
                    exit($tablehash[$j][$this->table_array[$i]['name']]);
                    //echo "ALTER TABLE `{$profix}{$this->table_name}` ADD `{$this->table_array[$i]['name']}` {$this->table_array[$i]['type']}({$this->table_array[$i]['length']}) NOT NULL DEFAULT '{$this->table_array[$i]['default']}' COMMENT '{$this->table_array[$i]['comment']}'";
                    $this->query("ALTER TABLE `{$profix}{$this->table_name}` ADD `{$this->table_array[$i]['name']}` {$this->table_array[$i]['type']}({$this->table_array[$i]['length']}) NOT NULL DEFAULT '{$this->table_array[$i]['default']}' COMMENT '{$this->table_array[$i]['comment']}'");
                }
            }
        }
    }

}