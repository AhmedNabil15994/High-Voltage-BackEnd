<?php

namespace Modules\Variation\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;
use Spatie\Translatable\HasTranslations;

class OptionValue extends Model
{
  use HasTranslations, SoftDeletes, ScopesTrait;

  protected $with               = [];
  protected $fillable           = ["status", "option_id", "title"];
  public $translatable   = ['title'];

  public function option()
  {
    return $this->belongsTo(Option::class);
  }

  public function productVariantValues()
  {
    return $this->hasMany(ProductVariantValue::class, 'option_value_id');
  }
}
