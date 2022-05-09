<?php

namespace Ramsesrom\Activity;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait Activity{
    public static function log($action,$model){
        //who
        $user = static::getUser('Guest');
        $ipAddress = static::getClientIp();
        //what
        $modelName = class_basename($model);
        $modelId = $model->getKey();
        //how
        $payload = json_encode($model->getDirty());

        static::insertLog([
            'user'=>$user,
            'ip_address'=>$ipAddress,
            'model_name'=>$modelName,
            'model_id'=>$modelId,
            'payload'=>$payload,
            'action'=>$action,
        ]);
    }

    public static function insertLog($log){
//        dd($log);
        return ActivityLog::create($log);
    }

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

    public static function getClientIp(){
        return request()->server('REMOTE_ADDR');
    }

    public static function getUser($default =''){
        $userNameColumn = config('activity.user_name_column');
        return isset(Auth::user()->$userNameColumn) ? Auth::user()->$userNameColumn : $default;
    }
}
