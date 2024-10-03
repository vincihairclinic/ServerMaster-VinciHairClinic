<?php

namespace App\Models;

use App\Models\Datasets\UserLogAction;
use App\Traits\DynamicHiddenVisibleTrait;
use Illuminate\Support\Str;

/**
 * Class UserLog
 * 
 * @property int $id
 * @property int $user_id
 * @property string $label
 * @property string $controller
 * @property int $action_id
 * @property string $model
 * @property int $model_id
 * @property array $changes
 * @property \Carbon\Carbon $created_at
 *
 * @property User $user
 *
 * @package App\Models
 */
class UserLog extends ModelBase
{
    use DynamicHiddenVisibleTrait;
	public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'action_id' => 'int',
        'model_id' => 'int',
    ];

	protected $fillable = [
		'user_id',
		'action_id',
		'label',
		'controller',
		'model',
		'model_id',
		'changes'
	];

    protected $attributes = [
        'action_id' => UserLogAction::NONE,
    ];

    //----------------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //----------------------------------------------------

    public function setUserIdAttribute($value)
    {
        $this->attributes['user_id'] = \Auth::id();
    }

    //-------------------

    public function getChangesAttribute($value)
    {
        $value = !empty($value) ? json_decode($value) : [];
        if(!empty($value) && (is_object($value) || is_array($value))){
            foreach ($value as $i => $v){
                if(!empty($v->is_array) || !empty($v->is_object)){
                    $value->{$i}->value_befor = json_decode($v->value_befor);
                    $value->{$i}->value_after = json_decode($v->value_after);
                }
            }
        }
        return !empty($value) ? $value : [];
    }

    public function setChangesAttribute($value)
    {
        $value = (is_object($value) || is_array($value)) && !empty($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : null;
        $this->attributes['changes'] = !empty($value) ? $value : null;
    }

    //----------------------------------------------------

    static function add($model_befor_changes = null, $model_after_changes = null, $label = null, $model_name = null, $model_id = null)
    {
        try {
            $model = !empty($model_befor_changes) ? $model_befor_changes : $model_after_changes;
            $model_name = !empty($model_name) ? $model_name : (!empty($model) ? class_basename($model) : null);
            $model_id = !empty($model_id) ? $model_id : (!empty($model_after_changes) && !empty($model_after_changes->id) ? $model_after_changes->id : (!empty($model_befor_changes) && !empty($model_befor_changes->id) ? $model_befor_changes->id : null));

            if(empty($model)){
                return;
            }
            $changes = [];
            $keys_befor = !empty($model_befor_changes) ? array_keys($model_befor_changes->toArray()) : [];
            $keys_after = !empty($model_after_changes) ? array_keys($model_after_changes->toArray()) : [];
            $keys = array_values(array_unique(array_merge($keys_befor, $keys_after)));

            foreach ($keys as $i){
                $value_befor = !empty($model_befor_changes) && !empty($model_befor_changes[$i]) ? (is_object($model_befor_changes[$i]) || is_array($model_befor_changes[$i]) ? json_encode($model_befor_changes[$i]) : $model_befor_changes[$i]) : null;
                $value_after = !empty($model_after_changes) && !empty($model_after_changes[$i]) ? (is_object($model_after_changes[$i]) || is_array($model_after_changes[$i]) ? json_encode($model_after_changes[$i]) : $model_after_changes[$i]) : null;

                if(empty($value_befor) || empty($value_after) || $value_befor != $value_after){
                    $is_object = false;
                    $is_array = false;
                    $is_date = false;
                    if(!empty($model_befor_changes) && !empty($model_befor_changes[$i])){
                        if(is_array($model_befor_changes[$i])){
                            $is_array = true;
                            if(!empty($model_befor_changes[$i][0])){
                                if(is_object($model_befor_changes[$i][0])){
                                    if(class_basename($model_befor_changes[$i][0]) == 'Carbon'){
                                        $is_date = true;
                                    }else{
                                        $is_object = true;
                                    }
                                }
                            }
                        }else if(is_object($model_befor_changes[$i])){
                            if(class_basename($model_befor_changes[$i]) == 'Carbon'){
                                $is_date = true;
                            }else{
                                $is_object = true;
                            }
                        }
                    }
                    if(!empty($model_after_changes) && !empty($model_after_changes[$i])){
                        if(is_array($model_after_changes[$i])){
                            $is_array = true;
                            if(!empty($model_after_changes[$i][0])){
                                if(is_object($model_after_changes[$i][0])){
                                    if(class_basename($model_after_changes[$i][0]) == 'Carbon'){
                                        $is_date = true;
                                    }else{
                                        $is_object = true;
                                    }
                                }
                            }
                        }else if(is_object($model_after_changes[$i])){
                            if(class_basename($model_after_changes[$i]) == 'Carbon'){
                                $is_date = true;
                            }else{
                                $is_object = true;
                            }
                        }
                    }

                    $changes[$i] = (object)[
                        'column' => $i,
                        'value_befor' => $value_befor,
                        'value_after' => $value_after,
                        'is_array' => $is_array,
                        'is_object' => $is_object,
                        'is_date' => $is_date,
                    ];
                }
            }

            foreach ($changes as $i => $v){
                if($v->value_befor == $v->value_after || Str::startsWith($i, 'url_') || Str::endsWith($i, '_url_image') || Str::endsWith($i, '_url_images') || in_array($v->column, (!empty($model->disableLogColumns) ? $model->disableLogColumns : []))){
                    $changes[$i] = null;
                }
            }
            $changes = array_filter($changes);

            if(!empty($changes)){
                self::create([
                    'user_id' => \Auth::id(),
                    'label' => $label,
                    'controller' => !empty(\Route::currentRouteAction()) ? substr(str_replace('App\Http\Controllers\Dashboard', '', \Route::currentRouteAction()), 1) : null,
                    'action_id' => empty($model_befor_changes) ? UserLogAction::CREATE : (empty($model_after_changes) ? UserLogAction::DELETE :  (!empty($model_befor_changes) && !empty($model_after_changes) ? UserLogAction::UPDATE : UserLogAction::NONE)),
                    'changes' => $changes,
                    'model' => $model_name,
                    'model_id' => $model_id,
                ]);
            }
        }catch (\Exception $e){}
    }

    /*

     $modelCopy = clone $model;

     UserLog::add($modelCopy, $model);


    **************

    $modelCopy = User::where('id', $model->id)->with([
        'user_educations',
        'ethnicity_countries',
        'professional_qualifications',
    ])->first();



    UserLog::add($modelCopy, User::where('id', $model->id)->with([
        'user_educations',
        'ethnicity_countries',
        'professional_qualifications',
    ])->first());

    */

}



