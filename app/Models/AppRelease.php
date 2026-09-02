<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppRelease extends Model
{
    protected $table = 'app_releases';


    protected $fillable = [

        'category_id',

        'version',

        'version_code',

        'title',

        'description',

        'apk_url',

        'apk_sha256',

        'apk_size',

        'is_required',

        'is_active',

        'released_at',

    ];


    protected $casts = [

        'version_code' =>
            'integer',

        'apk_size' =>
            'integer',

        'is_required' =>
            'boolean',

        'is_active' =>
            'boolean',

        'released_at' =>
            'datetime',

    ];


    /**
     * Категория релиза.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ReleaseCategory::class,
            'category_id'
        );
    }


    /**
     * Получить CID из IPFS URL.
     */
    public function getIpfsCidAttribute(): ?string
    {
        if (!$this->apk_url) {
            return null;
        }


        $path =
            parse_url(
                $this->apk_url,
                PHP_URL_PATH
            );


        if (!$path) {
            return null;
        }


        $path =
            trim(
                $path,
                '/'
            );


        /*
         * Если URL имеет вид:
         *
         * /ipfs/CID
         *
         * возвращаем CID.
         */

        if (
            str_starts_with(
                $path,
                'ipfs/'
            )
        ) {

            return substr(
                $path,
                5
            );
        }


        /*
         * Если каким-то образом в apk_url
         * сохранён просто CID.
         */

        if (
            !str_contains(
                $path,
                '/'
            )
        ) {

            return $path;
        }


        return null;
    }
}