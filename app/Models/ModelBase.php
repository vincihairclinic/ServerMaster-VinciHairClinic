<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class ModelBase
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModelBase exclude($exclude = array())
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModelBase excludeLanguage($language = null, $exclude = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModelBase selectAll()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ModelBase paginateSimple($next = false)
 * @mixin \Eloquent
 */
class ModelBase extends Model
{
    const PAGINATE_LIMIT = 12;

    static function deleteAll()
    {
        foreach (self::all() as $model){
            $model->delete();
        }
    }

    static function implodeIds()
    {
        return static::get()->implode('id', ',');
    }

    public function getColumns()
    {
        $columns = !empty($this->columns) ? array_merge($this->fillable, $this->columns) : $this->fillable;
        if(!empty($columns)){
            if(isset($this->casts['id'])){
                $columns[] = 'id';
            }
            $columns = array_values(array_unique($columns));
            return $columns;
        }
        return [];
    }

    public function scopeStatusRange($query, $statusRange)
    {
        return $query->where('status_id', '>=', $statusRange[0])->where('status_id', '<=', $statusRange[1]);
    }

    public function scopePaginateSimple($query)
    {
        $limit = self::PAGINATE_LIMIT;
        $offset = 0;
        $page = 1;
        $request = request();

        if(!empty($request)){
            if(is_numeric($request)){
                $page = $request;
            }else{
                $page = !empty($request->page) ? $request->page : $page;
            }
            $offset = $page > 1 ? ($page-1) * self::PAGINATE_LIMIT : 0;
        }

        return $query->offset($offset)->limit($limit);
    }

    public function scopeSelectAll($query)
    {
        return $query->select($this->getColumns());
    }

    public function scopeQuery($query)
    {
        return $query;
    }

    public function scopeExclude($query, $exclude = [])
    {
        return $query->select(array_diff($this->getColumns(), $exclude));
    }

    public function scopeExcludeLanguage($query, $language = 'ua', $exclude = [])
    {
        $languageExclude = $language == 'ua' ? 'en' : 'ua';
        foreach ($this->fillable as $field){
            $fieldExplode = explode('_', $field);
            if($fieldExplode[count($fieldExplode)-1] == $languageExclude){
                $exclude[] = $field;
            }
        }

        return $query->select(array_diff($this->getColumns(), $exclude));
    }

    public static function getDefaultHiddens() {
        return with(new static)->getHiddens();
    }

    public function getHiddens(){
        return $this->hidden;
    }

    static function incrementAllNextRecords($fromId = 0, $table = null)
    {
        $table = $table ? $table : (new static())->getTable();

        \DB::beginTransaction();
        \DB::update('UPDATE '.$table.' SET id = id + 1 WHERE id >= '.$fromId.' order by id DESC;');
        \DB::commit();
    }

    static function resetAutoIncrement($table = null)
    {
        $table = $table ? $table : (new static())->getTable();
        \DB::statement("ALTER TABLE ".$table." AUTO_INCREMENT = 0;");
    }

    static function resetAutoIncrementIfEmpty($table = null)
    {
        if(!self::count()){
            self::resetAutoIncrement($table);
        }
    }

    static function activeAllChildrens($model, $childrensRelation, $fieldName, $isActive = 1)
    {
        if(isset($model->{$childrensRelation})){
            foreach ($model->{$childrensRelation} as $item){
                $item->update([
                    $fieldName => $isActive
                ]);
                self::activeAllChildrens($item, $childrensRelation, $fieldName, $isActive);
            }
        }
    }

    static function activeAllParents($model, $parentRelation, $childrensRelation, $fieldName, $isActive = 1)
    {
        if(isset($model->{$parentRelation})){
            if($isActive){
                $model->{$parentRelation}->update([$fieldName => 1]);
                self::activeAllParents($model->{$parentRelation}, $parentRelation, $childrensRelation, $fieldName, $isActive);
            }else{
                if(!$model->{$childrensRelation}->where($fieldName, 1)->all()){
                    $model->{$parentRelation}->update([$fieldName => 0]);
                    self::activeAllParents($model->{$parentRelation}, $parentRelation, $childrensRelation, $fieldName, $isActive);
                }
            }
        }
    }
}
