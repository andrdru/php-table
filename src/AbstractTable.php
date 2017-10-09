<?php

namespace Table;

abstract class AbstractTable implements TableInterface
{
    protected const PREFIX_TABLE = 't_';
    protected const PREFIX_IDNAME = 'idt_';
    protected $element;
    protected $table;
    protected $idname;

    protected function initTable($element, $table = '', $idname = '')
    {
        $this->element = $element;
        $this->table = $table;
        if (!$table) {
            $this->table = self::PREFIX_TABLE . $this->element;
        }
        $this->idname = $idname;
        if (!$idname) {
            $this->idname = self::PREFIX_IDNAME . $this->element;
        }
    }

    public function elementVal()
    {
        return $this->element;
    }

    public function tableVal()
    {
        return $this->table;
    }

    public function idnameVal()
    {
        return $this->idname;
    }
}
