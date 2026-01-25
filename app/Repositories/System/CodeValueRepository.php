<?php

namespace App\Repositories\System;

use App\Models\System\CodeValue;
use App\Repositories\BaseRepository;

/**
 * Class CodeValueRepository
 * @package App\Repositories\Sysdef
 * @description Use this class with care, could break the system.
 * Controls all the data dictionaries of the system.
 * @author Yohana Samile <yohanasamile@gmail.com>
 */
class CodeValueRepository extends BaseRepository
{
    const MODEL = CodeValue::class;
    protected $code_repo;

    public function __construct(){
        $this->code_repo = new CodeRepository();
    }

    /*
     * Translate CodeValues Entries using lang>code_value
     */
    public function mapIdsForLang($query)
    {
//        $return = $query->map(function ($item, $key) {
//            return ['id' => $item['id'], 'name' => __("code_value." . $item['id'])];
//        });
        return $query;
    }

    /*Query active only*/
    public function queryActiveOnly()
    {
        return $this->query()->where('isactive', 1);
    }

    /**
     * Get code value name for translation
     * @param $id
     * @return array|null|string
     */
    public function name($id)
    {
        return $this->find($id)->name;
    }

    public function nameByReference($reference)
    {
        return  $this->query()->where('reference',$reference)->first()->name;
    }

    /*Get reference*/
    public function reference($cv_id)
    {
        return $this->find($cv_id)->reference ?? null;
    }

    /*
     * Get all code values by code_id
     * For initiating chained selects
     */
    public function getAllByCode($code_id)
    {
        return $this->query()->select(['id', 'name', 'code_id'])->where("code_id", $code_id)->get();
    }

    public function getCodeValuesForSelect($code_id)
    {
        return $this->queryActiveOnly()->select(['id', 'name'])->where("code_id", $code_id)->orderBy('id', 'asc')->get();
    }


    /*Get code values by reference for select*/
    public function getCodeValuesReferenceForSelect($code_id)
    {
        return $this->queryActiveOnly()->select(['name', 'reference'])->where("code_id", $code_id)->orderBy('sort')->get();
    }

    /**
     * Get CV by reference
     * @param $reference
     * @return mixed
     */
    public function getCodeValueByReference($reference){
        return $this->query()->where("reference", $reference)->first();
    }


    /**
     * @return array
     * Get days for select
     */
    public function getDaysForSelect()
    {
        return [
            '1' => 'MONDAY',
            '2' => 'TUESDAY',
            '3' => 'WEDNESDAY',
            '4' => 'THURSDAY',
            '5' => 'FRIDAY',
            '6' => 'SATURDAY',
            '7' => 'SUNDAY',
        ];
    }

}
