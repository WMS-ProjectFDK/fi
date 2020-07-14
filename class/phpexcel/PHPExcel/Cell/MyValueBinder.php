<?php
class PHPExcel_Cell_MyValueBinder extends PHPExcel_Cell_DefaultValueBinder {

    public static function dataTypeForValue($pValue = null) {
        if (/* your condition */) {
            // if you want to return a value, and i guess it's what you want, you can
            return PHPExcel_Cell_DataType::YOUR_TYPE;
        }
        // you call the fonction PHPExcel_Cell_DefaultValueBinder::dataTypeForValue();
        // so the already existant conditions are still working.
        return parent::dataTypeForValue($pValue);
    }

}
?>