<?php

namespace App\Traits;

use App\Exceptions\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model as EloquentModel;

trait DatabaseRepositoryTrait
{
  /**
   * Get all of the models from the database.
   *
   * @param array $related
   * @return \Illuminate\Database\Eloquent\Collection
   */
    public function all(array $related = null)
    {
        return $this->query()->get();
    }

    /**
     * Get the paginated models from the database.
     *
     * @param  int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 10)
    {
        return $this->query()->latest()->paginate($perPage);
    }

    /**
     * Get a model by its primary key.
     *
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \App\Exceptions\ModelNotFoundException
     */
    public function get($id = null, array $related = null, $throw = true)
    {
        $query = $this->query();

        if(is_null($id)){
            return $query->get();
        }

        $model = $query->find($id);

        if ($throw && ! $model) {
            $this->throwNotFoundException();
        }

        return $model;
    }

    /**
     * Get models by the value.
     *
     * @param int $id
     * @param array $moreWhere
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @throws \App\Exceptions\ModelNotFoundException
     */
    public function getWhere($column, $value, array $moreWhere = null, $throw = true, $modelClass = null, $orderBy = null, $order = null)
    {
        $query = $this->query()->where($column, $value);

        if (! is_null($moreWhere)) {
            $query->where($moreWhere);
        }

        if ($orderBy) {
            $query->orderBy($orderBy, $order);
        }
        else {
            $query->latest();
        }

        $models = $query->get();

        if ($throw && $models->isEmpty()) {
            $this->throwNotFoundException();
        }

        return $models;
    }

    /**
     * Get the model data by adding the given query
     *
     * @param  string $column
     * @param  mixed $value
     * @param  array $moreWhere
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWhereIn($column, $value, array $moreWhere = null, $throw = true, $modelClass = null, $orderBy = null, $order = null)
    {
        $query = $this->query()->whereIn($column, $value);

        if (! is_null($moreWhere)) {
            $query->where($moreWhere);
        }

        if ($orderBy) {
            $query->orderBy($orderBy, $order);
        }

        $models = $query->get();

        if ($throw && $models->isEmpty()) {
            $this->throwNotFoundException();
        }

        return $models;
    }

    /**
     * Create a new instance of the model.
     *
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance(array $attributes = [], $exists = false)
    {
        $modelName = $this->model;

        return new $modelName($attributes, $exists);
    }

    /**
     * Save a new model and return the instance.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes)
    {
        // dd($attributes);
        $modelName = $this->model;

        return $modelName::create($attributes);
    }

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrNew(array $attributes, array $values = [])
    {
        return $this->query()->firstOrNew($attributes, $values);
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->query()->firstOrCreate($attributes, $values);
    }

    /**
     * Update the model by the given attributes.
     *
     * @param  \Illuminate\Database\Eloquent\Model|int $model
     * @return bool|int
     */
    public function update($model, array $attributes)
    {
        return ($model instanceof EloquentModel) ? $model->update($attributes) : $this->get($model)->update($attributes);
    }

    /**
     * Fill the model with an array of attributes and save it. Force mass assignment.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param  array  $attributes
     * @return bool
     */
    public function forceUpdate($model, array $attributes)
    {
        return $model->forceFill($attributes)->save();
    }

     /**
     * Chunk the results of the query.
     *
     * @param  int  $count
     * @param  callable  $callback
     * @return bool
     */
    public function chunk($count, callable $callback)
    {
        $results = $this->query()->forPage($page = 1, $count)->get();

        while (count($results) > 0) {
            // On each chunk result set, we will pass them to the callback and then let the
            // developer take care of everything within the callback, which allows us to
            // keep the memory low for spinning through large result sets for working.
            if (call_user_func($callback, $results, $page) === false) {
                return false;
            }

            $page++;

            $results = $this->query()->forPage($page, $count)->get();
        }

        return true;
    }

     /**
     * Check if any relation exists.
     *
     * @param int $id
     * @param  array  $relations
     * @return bool
     */
    public function hasRelations($id, array $relations, $column = 'id')
    {
        if (count($relations) == 0) {
            throw new \InvalidArgumentException('The hasRelations function only accepts non empty array of relations.');
        }

        $query = $this->query();

        $query->where($column, $id);

        $query->where(function($q) use($relations) {
            foreach ($relations as $relation) {
                $q->orHas($relation);
            }
        });

        return $query->exists();
    }

      /**
     * Eager load relations on the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  array|string  $relations
     * @return void
     */
    public function load($model, $relations)
    {
        $model->load($relations);
    }


    /**
     * Delete the model from the database.
     *
     * @param  \Illuminate\Database\Eloquent\Model|int $model
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete($model)
    {
        return ($model instanceof EloquentModel) ? $model->delete() : $this->get($model)->delete();
    }


    /**
     * Get first model by the value.
     *
     * @param int $id
     * @param array $moreWhere
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @throws \App\Exceptions\ModelNotFoundException
     */
    public function firstWhere($column, $value, array $moreWhere = null, $throw = true, $modelClass = null)
    {
        $query = $this->query()->where($column, $value);

        if (! is_null($moreWhere)) {
            $query->where($moreWhere);
        }
        
        $model = $query->first();
    
        if ($throw && !$model) {
            $this->throwNotFoundException($modelClass);
        }
        
        return $model;
    }

    

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreate(array $attributes, array $values = [])
    {
        return $this->query()->updateOrCreate($attributes, $values);
    }

    /**
     * Sync the intermediate tables with a list of IDs or collection of models.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $relation
     * @param  \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array  $ids
     * @return array
     */
    public function sync($parent, $relation, $ids)
    {
        return $parent->$relation()->sync($ids);
    }

    /**
     * Attach a model to the parent.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     * @param  bool   $touch
     * @return void
     */
    public function attach($parent, $relation, $id, array $attributes = [], $touch = true)
    {
        $parent->$relation()->attach($id, $attributes, $touch);
    }

    /**
     * Detach models from the relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $relation
     * @param  mixed  $ids
     * @param  bool  $touch
     * @return int
     */
    public function detach($parent, $relation, $ids = null, $touch = true)
    {
        return $parent->$relation()->detach($ids, $touch);
    }

    /**
     * Create the record releated to given parent.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $relation
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createRelationally($parent, $relation, $attributes)
    {
        return $parent->$relation()->create($attributes);
    }

    /**
     * Create or update a related record matching the attributes, and fill it with values.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $relation
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreateRelationally($parent, $relation, array $attributes, array $values = [])
    {
        return $parent->$relation()->updateOrCreate($attributes, $values);
    }

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return call_user_func("{$this->model}::query");
    }

    /**
     * Throws ModelNotFoundException.
     *
     * @return void
     */
    public function throwNotFoundException($model)
    {
        throw new ModelNotFoundException(
            $model ? sprintf('No %s record found.', $model) : sprintf('No record found')
        );
    }
}
