<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model
{
    use HasFactory;

    /**
     * table
     *
     * @var string
     */
    protected $table = 'carpetas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'onedrive_id',
        'children_id',
        'nombre'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    /**
     * Relationship with Carpeta
     *
     * @return object
     */
    public function carpeta()
    {
        return $this->belongsTo(Carpeta::class, 'children_id');
    }

    /**
     * Relationship with Carpeta
     *
     * @return object
     */
    public function carpetas()
    {
        return $this->hasMany(Carpeta::class, 'children_id');
    }

    /**
     * Relationship with Archivo
     *
     * @return object
     */
    public function archivos()
    {
        return $this->hasMany(Archivo::class);
    }
}
