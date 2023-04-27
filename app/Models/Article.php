<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Article"),
 *   @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *   @OA\Property(property="user", type="onject", ref="#/components/schemas/User"),
 *   @OA\Property(property="title", type="string", readOnly="true", description="Title of the article", example="Demo article"),
 *   @OA\Property(property="description", type="string", readOnly="true", description="Description of the article", example="This is description of the article!"),
 *   @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 *   @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 * )
 * 
 * Class Article
 *
 */
class Article extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
