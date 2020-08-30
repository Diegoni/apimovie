<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;
use App\Http\ResponseCode;
use App\Genre;

class ExternalDataControl 
{
    const CHECK_STRING = 'String';
    const CHECK_LIST = 'list';
    const CHECK_DATE = 'date';
    const CHECK_FLOAT = 'float';
    const CHECK_SHORT = 'short';
    const CHECK_BOOL = 'bool';

    public static function getDataCheck($objet){
        $arrayCheck = [];
        switch ($objet) {
            case 'movies':
                $genres = Genre::orderBy('id')->pluck('id')->toArray();
            
                $arrayCheck = [
                    ["popularity",          self::CHECK_FLOAT   ],
                    ["vote_count",          self::CHECK_SHORT   ],
                    ["video",               self::CHECK_BOOL    ],
                    ["poster_path",         self::CHECK_STRING, 128],
                    ["id",                  self::CHECK_SHORT   ],
                    ["adult",               self::CHECK_BOOL    ],
                    ["backdrop_path",       self::CHECK_STRING, 128],
                    ["original_language",   self::CHECK_STRING, 3],
                    ["original_title",      self::CHECK_STRING, 128],
                    ["genre_ids",           self::CHECK_LIST,   $genres],
                    ["title",               self::CHECK_STRING, 128],
                    ["vote_average",        self::CHECK_FLOAT   ],
                    ["overview",            self::CHECK_STRING, 128],
                    ["release_date",        self::CHECK_DATE,   'YYYY-MM-DD'],
                ];
                break;

            case 'signup':
                $arrayCheck = [
                    ["name",     self::CHECK_STRING, 128],
                    ["email",    self::CHECK_STRING, 128],
                    ["password", self::CHECK_STRING, 128],
                ];
                break;

             case 'login':
                $arrayCheck = [
                    ["email",        self::CHECK_STRING, 128],
                    ["password",       self::CHECK_STRING, 128],
                    ["remember_me", self::CHECK_BOOL],
                ];
                break;
        }

        return $arrayCheck;
    }

    public static function findError($data, $objet){
        $arrayCheck = self::getDataCheck($objet);
        $arrayError = [];       

        foreach ($arrayCheck as $check) {
            $field = $check[0];
            $type = $check[1];
            $extraData = isset($check[2]) ? $check[2] : NULL;
            $fieldName = $objet.'.'.$field;

            if (!isset($data->{$field})) {
                $arrayError[] = ['field'=>$fieldName, 'detail'=>'Data required'];
            } else {
                switch ($type) {
                    case self::CHECK_STRING:
                        $required = isset($check[3]) ? ( strlen($data->{$field}) > 0 ? false : true ) : false;
                        if ( $required ) {
                            $arrayError[] = ['field'=>$fieldName, 'detail'=>'Contains no data' ];
                        } else if ( strlen($data->{$field}) > $extraData) {
                            $arrayError[] = ['field'=>$fieldName, 'detail'=>'String max of '.$extraData ];
                        }
                        break;
                    case self::CHECK_LIST:
                        if(is_array($data->{$field})) {
                            foreach($data->{$field} as $field){
                                if (! in_array($field, $extraData) ) {
                                    $arrayError[] = ['field'=>$fieldName, 'detail'=>'Value '.$field.' not allowed, allowed values '.json_encode($extraData)];
                                }
                            }
                        } else {
                            if (! in_array($data->{$field}, $extraData) ) {
                                $arrayError[] = ['field'=>$fieldName, 'detail'=>'Allowed values '.json_encode($extraData)];
                            }
                        }                        
                        break;
                    case self::CHECK_DATE:
                        if (! self::validateDate($data->{$field}, $extraData)) {
                            $arrayError[] = ['field'=>$fieldName, 'detail'=>'Invalid date, format '.$extraData];
                        }
                        break;
                    case self::CHECK_FLOAT:
                        if (! is_float($data->{$field})) {
                            $arrayError[] = ['field'=>$fieldName, 'detail'=>'It is not a valid number'];
                        }
                        break;
                    case self::CHECK_SHORT:
                        $required = isset($check[2]) ? ( strlen($data->{$field}) > 0 ? false : true ) : false;
                        if ( $required ) {
                            $arrayError[] = ['field'=>$fieldName, 'detail'=>'Contains no data' ];
                        } else if (is_int($field)) {
                            $arrayError[] = ['field'=>$fieldName, 'detail'=>'It is not a whole number'];
                        }
                        break;
                    case self::CHECK_BOOL:
                        if (! is_bool($data->{$field})) {
                            $arrayError[] = ['field'=>$fieldName, 'detail'=>'Is not boolean'];
                        }
                        break;
                }
            }
        }

        if (!empty($arrayError)) {
            $returnJson = [
                "success" => false,
                "status_message" => $arrayError
            ];
            return $returnJson;
        } else {
            return false;
        }
    }

     public static function validateDate($date, $format = 'YYYY-MM-DD') {
        switch ($format) {
            case 'YYYY-MM-DD':
                $date = explode('-', $date);
                $year = $date[0];
                $month = $date[1];
                $day = $date[2];
                break;
        }

        return checkdate($month, $day, $year);
    }
}
