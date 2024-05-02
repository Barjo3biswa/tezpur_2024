<?php

namespace App;

use App\Models\AdmissionCategory;
use App\Models\OnlinePaymentSuccess;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelReceipt extends Model
{
    use SoftDeletes;
    protected $guarded=['id'];

    public function collections()
    {
        return $this->hasMany(HostelCollection::class, 'receipt_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(OnlinePaymentSuccess::class, "online_payment_id", "id")->withTrashed();
    }
    public function admission_category()
    {
        return $this->belongsTo(AdmissionCategory::class, "category_id", "id")->withDefault([
            "name"  => "NA"
        ]);
    }
}
