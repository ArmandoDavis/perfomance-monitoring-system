<?php
/**
 * Created by PhpStorm.
 * User: Yohana Samile
 * Date: 20/09/2025
 * Time: 19:22 PM
 */
namespace App\Models\BaseModel;

use App\Models\BaseModel\Traits\Attribute\BaseModelAttribute;
use App\Models\BaseModel\Traits\Relationship\BaseModelRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model implements AuditableContract {
    use SoftDeletes, HasFactory, Auditable, BaseModelRelationship, BaseModelAttribute;
    protected $guarded = [];

    /**
     * Get the route key for the model.
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
