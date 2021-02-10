<?php

declare(strict_types=1);

namespace Rinvex\Forms\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasForms
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    abstract public static function deleted($callback);

    /**
     * Define a polymorphic one-to-many relationship.
     *
     * @param string $related
     * @param string $name
     * @param string $type
     * @param string $id
     * @param string $localKey
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    abstract public function morphMany($related, $name, $type = null, $id = null, $localKey = null);

    /**
     * Boot the HasForms trait for the model.
     *
     * @return void
     */
    public static function bootHasForms()
    {
        static::deleted(function (self $model) {
            $model->forms()->delete();
        });
    }

    /**
     * Get all attached forms to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function forms(): MorphMany
    {
        return $this->morphMany(config('rinvex.forms.models.form'), 'entity', 'entity_type', 'entity_id');
    }
}
