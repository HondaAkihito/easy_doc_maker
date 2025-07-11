<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BentoName extends Model
{
    use HasFactory;

    // リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function bentoBrand()
    {
        return $this->belongsTo(BentoBrand::class);
    }

    // このモデルで一括代入（mass assignment）してよい属性の一覧
    protected $fillable = [
        'name', 'user_id', 'bento_brand_id'
    ];

    // 検索
    public function scopeSearch($query, $search)
    {
        if($search !== null){
            $search_split = mb_convert_kana($search, 's'); // 全角スペース → 半角
            $keywords = preg_split('/[\s]+/', $search_split); // 空白で分割

            foreach($keywords as $word) {
                // `$query`ごとにAND検索
                $query->where(function($q) use($word) {
                    // どれか部分一致でヒットのor検索
                    $q->orWhere('bento_names.name', 'like', '%' . $word . '%')
                    ->orWhere('bento_brands.name', 'like', '%' . $word . '%');
                });
            }
        }

        return $query;
    }
}
