<?php

namespace Ramsesrom\Activity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

trait Activity{

    /**
     * set up the Log
     *
     * @param $action - activity name
     * @param $model
     * @return void
     */
    public static function log($action,$model){
        //who
        $user = static::getUser('Guest');
        $ipAddress = static::getClientIp();

        //what
        $modelName = class_basename($model);
        $modelId = $model->getKey();

        //how
        $payload = json_encode(static::hideFields($model));

        static::insertLog([
            'user'=>$user,
            'ip_address'=>$ipAddress,
            'model_name'=>$modelName,
            'model_id'=>$modelId,
            'payload'=>$payload,
            'action'=>$action,
        ]);
    }

    /**
     * insert the log activity
     *
     * @param $log
     * @return bool
     */
    public static function insertLog($log){
        return ActivityLog::create($log);
    }

    /**
     * records the activity
     *
     * @return void
     */
    public static function bootActivity(){
        static::created(function ($model){
            static::log("Created",$model);
        });

        static::updated(function ($model){
            static::log("Updated",$model);
        });

        static::deleted(function ($model){
            static::log("Deleted",$model);
        });
    }

    /**
     * get user's ip address of the activity
     *
     * @return string|null
     */
    public static function getClientIp(){
        return request()->server('REMOTE_ADDR');
    }

    /**
     * get user of the activity
     *
     * @param $default - default name of the user
     * @return string
     */
    public static function getUser($default =''){
        $userNameColumn = config('activity.user_name_column');
        return isset(Auth::user()->$userNameColumn) ? Auth::user()->$userNameColumn : $default;
    }

    /**
     * hide sensitive information form the model
     *
     * @param $model
     * @return array
     */
    public static function hideFields($model){
        $newArray = $model->getDirty();
        $modelName = class_basename($model);
        foreach($newArray as $i=>$v):
            $hideColumns = config('activity.hide_column_array.'.$modelName.'.'.$i);
            if($hideColumns):
                $newArray[$i] = $hideColumns;
            endif;
        endforeach;
        return $newArray;
    }
}
