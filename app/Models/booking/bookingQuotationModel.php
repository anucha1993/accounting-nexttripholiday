<?php

namespace App\Models\booking;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookingQuotationModel extends Model
{
    protected $table = 'booking_quotation';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tour_id',
        'period_id',
        'code',
        'start_date',
        'end_date',
        'num_twin',
        'num_single',
        'num_child',
        'num_childnb',
        'price1',
        'sum_price1',
        'price2',
        'sum_price2',
        'price3',
        'sum_price3',
        'price4',
        'sum_price4',
        'total_price',
        'total_qty',
        'member_id',
        'name',
        'surname',
        'email',
        'phone',
        'sale_id',
        'detail',
        'status',
        'remark',

    ];

}
