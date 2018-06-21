<?php

declare(strict_types=1);

namespace Rinvex\Forms\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasFormResponses
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
     * Boot the HasFormResponses trait for the model.
     *
     * @return void
     */
    public static function bootHasFormResponses()
    {
        static::deleted(function (self $model) {
            $model->formResponses()->delete();
        });
    }

    /**
     * Get all attached form responses to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function formResponses(): MorphMany
    {
        return $this->morphMany(config('rinvex.forms.models.form_response'), 'user');
    }
}
