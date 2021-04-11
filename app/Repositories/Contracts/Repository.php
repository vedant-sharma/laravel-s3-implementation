<?php 

namespace App\Repositories\Contracts;

interface Repository
{
    /**
     * Get all of the models from the data source.
     * 
     * @param array $related
     * @return \Illuminate\Support\Collection
     */
    public function all(array $related = null);

    /**
     * Get the paginated models from the data source.
     * 
     * @param  int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = 10);

    /**
     * Get a model by its primary key.
     *
     * @param int $id
     * @param array $related
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \App\Exceptions\ModelNotFoundException
     */
    public function get($id, array $related = null);

    /**
     * Get the model data by adding the given where query.
     * 
     * @param  string     $column
     * @param  mixed      $value
     * @param  array|null $related
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @throws \App\Exceptions\ModelNotFoundException
     */
    public function getWhere($column, $value, array $related = null);

    /**
     * Get the model data by adding the given whereIn query.
     * 
     * @param  string     $column
     * @param  mixed      $value
     * @param  array|null $related
     * @return \Illuminate\Support\Collection
     */
    public function getWhereIn($column, $value, array $related = null);

    /**
     * Create a new instance of the model.
     *
     * @param  array  $attributes
     * @param  bool  $exists
     * @return static
     */
    public function newInstance(array $attributes, $exists);

    /**
     * Save a new model and return the instance.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data);

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrNew(array $attributes);

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $attributes);

    /**
     * Update the model by the given attributes.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param  array  $attributes
     * @return bool|int
     */
    public function update($model, array $attributes);

    /**
     * Fill the model with an array of attributes and save it. Force mass assignment.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param  array  $attributes
     * @return bool
     */
    public function forceUpdate($model, array $attributes);

    /**
     * Chunk the results of the query.
     *
     * @param  int  $count
     * @param  callable  $callback
     * @return bool
     */
    public function chunk($count, callable $callback);

    /**
     * Check if any relation exists.
     *
     * @param int $id
     * @param  array  $relations
     * @return bool
     */
    public function hasRelations($id, array $relations);

    /**
     * Eager load relations on the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  array|string  $relations
     * @return void
     */
    public function load($model, $relations);

    /**
     * Delete the model from the data source.
     *
     * @return \Illuminate\Database\Eloquent\Model $model
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete($id);

    /**
     * Begin querying the model.
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query();

    /**
     * Get first model by the value.
     *
     * @param int $id
     * @param array $moreWhere
     * @return \Illuminate\Database\Eloquent\Collection
     *
     * @throws \App\Exceptions\ModelNotFoundException
     */
    public function firstWhere($column, $value, array $moreWhere = null, $throw = true, $modelClass = null);

    /**
     * Create or update a record matching the attributes, and fill it with values.
     *
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreate(array $attributes, array $values = []);

     /**
     * Sync the intermediate tables with a list of IDs or collection of models.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $relation
     * @param  \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array  $ids
     * @return array
     */
    public function sync($parent, $relation, $ids);

    /**
     * Attach a model to the parent.
     *
     * @param  mixed  $id
     * @param  array  $attributes
     * @param  bool   $touch
     * @return void
     */
    public function attach($parent, $relation, $id, array $attributes = [], $touch = true);

    /**
     * Detach models from the relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $relation
     * @param  mixed  $ids
     * @param  bool  $touch
     * @return int
     */
    public function detach($parent, $relation, $ids = null, $touch = true);

     /**
     * Create the record releated to given parent.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $relation
     * @param  array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createRelationally($parent, $relation, $attributes);

    /**
     * Create or update a related record matching the attributes, and fill it with values.
     *
     * @param  \Illuminate\Database\Eloquent\Model $parent
     * @param  string $relation
     * @param  array  $attributes
     * @param  array  $values
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreateRelationally($parent, $relation, array $attributes, array $values = []);
}
