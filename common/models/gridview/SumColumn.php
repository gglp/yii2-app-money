<?php

namespace common\models\gridview;

use yii\grid\DataColumn;

class SumColumn extends DataColumn
{
    private $_total = 0;

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        $this->_total += $value;
        return $value;
    }

    protected function renderFooterCellContent()
    {
        return $this->grid->formatter->format($this->_total, $this->format);
    }
}