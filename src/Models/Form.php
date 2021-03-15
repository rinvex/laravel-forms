<?php

declare(strict_types=1);

namespace Rinvex\Forms\Models;

use Spatie\Sluggable\SlugOptions;
use Rinvex\Support\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Rinvex\Support\Traits\HasTranslations;
use Rinvex\Support\Traits\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Rinvex\Forms\Models\Form.
 *
 * @property int                 $id
 * @property int                 $entity_id
 * @property string              $entity_type
 * @property string              $slug
 * @property string              $name
 * @property string              $description
 * @property array               $content
 * @property array               $actions
 * @property array               $submission
 * @property bool                $is_active
 * @property bool                $is_public
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent                              $entity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereActions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereSubmission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Rinvex\Forms\Models\Form whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Form extends Model
{
    use HasSlug;
    use SoftDeletes;
    use ValidatingTrait;
    use HasTranslations;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'entity_id',
        'entity_type',
        'slug',
        'name',
        'description',
        'content',
        'actions',
        'submission',
        'is_active',
        'is_public',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'entity_id' => 'integer',
        'entity_type' => 'string',
        'slug' => 'string',
        'name' => 'string',
        'description' => 'string',
        'content' => 'json',
        'actions' => 'json',
        'submission' => 'json',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
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
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    /**
     * The default rules that the model will validate against.
     *
     * @var array
     */
    protected $rules = [
        'entity_id' => 'nullable|integer',
        'entity_type' => 'nullable|string|strip_tags|max:150',
        'slug' => 'required|alpha_dash|max:150',
        'name' => 'required|string|strip_tags|max:150',
        'description' => 'nullable|string|max:32768',
        'content' => 'required|array',
        'actions' => 'required|array',
        'submission' => 'required|array',
        'is_active' => 'sometimes|boolean',
        'is_public' => 'sometimes|boolean',
    ];

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

        $this->setTable(config('rinvex.forms.tables.forms'));
    }

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function (self $model) {
            $model->responses()->delete();
        });
    }

    /**
     * Get the options for generating the slug.
     *
     * @return \Spatie\Sluggable\SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->doNotGenerateSlugsOnUpdate()
                          ->generateSlugsFrom('name')
                          ->saveSlugsTo('slug');
    }

    /**
     * The form may have many responses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function responses(): HasMany
    {
        return $this->hasMany(config('rinvex.forms.models.form_response'), 'form_id', 'id');
    }

    /**
     * Get the owner model of the form.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function entity(): MorphTo
    {
        return $this->morphTo('entity', 'entity_type', 'entity_id', 'id');
    }

    /**
     * Activate the form.
     *
     * @return $this
     */
    public function activate()
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    /**
     * Deactivate the form.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }
}
