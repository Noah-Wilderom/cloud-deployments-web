<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Valorin\Random\Random;

trait HasPublicId {

    public static function bootHasPublicId() {
        static::creating(function (Model $model) {
            if(!in_array('public_id', $model->getFillable())) {
                throw new \RuntimeException(sprintf("Could not find 'public_id' in fillable for [%s]", $model::class));
            }

            $model->public_id = $model->fillPublicId();
        });
    }

    public static function findByPublicId(string $publicId): ?Model {
        return static::query()->where('public_id', $publicId)->first();
    }

    public static function findByPublicIdOrFail(string $publicId): ?Model {
        return static::query()->where('public_id', $publicId)->firstOrFail();
    }

    public function scopePublicId(Builder $query, string $publicId): Builder {
        return $query->where('public_id', $query);
    }

    public function fillPublicId(): string {
        $publicId = $this->generateNewPublicId(
            prefix: property_exists(static::class, 'publicIdPrefix') ? $this->publicIdPrefix : '',
            length: property_exists(static::class, 'publicIdLength') ? $this->publicIdLength : 16,
        );

        if(!!$this->findByPublicId($publicId)) {
            return $this->generateNewPublicId();
        }

        return $publicId;
    }

    public static function generateNewPublicId(string $prefix = '', int $length = 16): string {
        return $prefix . Random::string(
                length: $length,
                lower: true,
                upper: true,
                numbers: true,
                symbols: false,
                requireAll: false,
            );
    }
}
