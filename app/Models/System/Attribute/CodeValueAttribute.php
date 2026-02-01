<?php
namespace App\Models\System\Attribute;

trait CodeValueAttribute
{
    public function systemDefined() {
        return $this->is_system_defined == 1;
    }

    /*Mandatory*/
    public function is_mandatory(){
        return $this->is_mandatory == 1;
    }

    /*Is Active flag*/
    public function is_active(){
        return $this->isactive == 1;
    }

    public static function getCodeValueByCodeId($codeId)
    {
        return self::query()->where('code_id', $codeId)->get();
    }

    public static function getCodeValueByReference($reference)
    {
        return self::query()->where('reference', $reference)->first();
    }
}
