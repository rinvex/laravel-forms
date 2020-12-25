<?php

declare(strict_types=1);

namespace Rinvex\Forms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Rinvex\Forms\Models\FormResponse.
 *
 * @property int                 $id
 * @property string              $unique_identifier
 * @property array               $content
 * @property int                 $form_id
 * @property int                 $user_id
 * @property string              $user_type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent                              $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\FormResponse ofUser(\Illuminate\Database\Eloquent\Model $user)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\FormResponse whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\FormResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\FormResponse whereFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\FormResponse whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\FormResponse whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\FormResponse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FormResponse extends Model
{
    use ValidatingTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'unique_identifier',
        'content',
        'form_id',
        'user_id',
        'user_type',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'unique_identifier' => 'string',
        'content' => 'json',
        'form_id' => 'integer',
        'user_id' => 'integer',
        'user_type' => 'string',
        'deleted_at' => 'datetime',
    ];

    /**
     * {@inheritdoc}
     */
    protected $observables = [
        'validating',
        'validated',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Whether the model should throw a
     * ValidationException if it fails validation.
     *
     * @var bool
     */
    protected $throwValidationExceptions = true;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('rinvex.forms.tables.form_responses'));
        $this->setRules([
            'unique_identifier' => 'nullable|string|strip_tags|max:150',
            'content' => 'required|array',
            'form_id' => 'required|integer|exists:'.config('rinvex.forms.tables.forms').',id',
            'user_id' => 'nullable|integer',
            'user_type' => 'nullable|string|strip_tags|max:150',
        ]);
    }

    /**
     * Get the form response user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function user()
    {
        return $this->morphTo('user', 'user_type', 'user_id', 'id');
    }

    /**
     * Get form responses of the given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfUser(Builder $builder, Model $user): Builder
    {
        return $builder->where('user_type', $user->getMorphClass())->where('user_id', $user->getKey());
    }

    /**
     * The form response always belongs to a form.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(config('rinvex.forms.models.form'), 'form_id', 'id', 'form');
    }
}
