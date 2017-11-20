<?php

namespace Table;

use Db\DbInterface;
use Db\Query\Delete;
use Db\Query\Select;
use Db\Query\Update;
use Util\Dbutil;

abstract class AbstractAction extends AbstractTable
{
    //please call initTable() and initDb() in constructor
    //abstract public function __construct();
    protected $db;

    public function initDb(DbInterface $db)
    {
        $this->db = $db;
    }

    public function getList($limit = 25, $offset = 0, $add_data = array(), $select = '*')
    {
        $dbutil = new Dbutil($this->db);
        return $dbutil->getTable($this->table, $limit, $offset, $add_data, $select, $this->idname);
    }

    public function del($id)
    {
        $ans = false;
        $delete = new Delete($this->db);
        $ret = $delete->from($this->table)->where($this->idname)->val($id)->do();
        if ($ret) {
            $ans = true;
        }
        return $ans;
    }

    public function get($id, $select = '*', $idname = '')
    {
        $ans = false;
        $sel = new Select($this->db);
        if (!$idname) {
            $idname = $this->idname;
        }
        $ret = $sel->select($select)->from($this->table)->where($idname)->val($id)->do();
        if (\is_array($ret)) {
            $ans = $ret[0];
        }
        return $ans;
    }

    public function edit(array $fc, $allowedKeys)
    {
        $ans = false;
        if (isset($fc[$this->idname])) {
            $id = $fc[$this->idname];
            unset($fc[$this->idname]);
            if (\count($fc) > 0) {
                $allowedArr = explode(',', $allowedKeys);
                $update = new Update($this->db);
                $obj = $update->update($this->table);
                $doEdit = false;
                foreach ($fc as $k => $el) {
                    if (\in_array($k, $allowedArr, false)) {
                        $obj = $obj->set($k)->val($el);
                        $doEdit = true;
                    }
                }
                if ($doEdit) {
                    $obj = $obj->where($this->idname)->val($id);
                    $ans = $obj->do();
                }
            }
        }
        return $ans;
    }
}
