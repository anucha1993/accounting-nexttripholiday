<?php

namespace App\Exports;

use App\Models\payments\paymentWholesaleModel;
use App\Models\quotations\quotationModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class paymentWholesaleExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = paymentWholesaleModel::query()->with(['quote.quoteWholesale']);

        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $this->request->start_date . ' 00:00:00',
                $this->request->end_date . ' 23:59:59'
            ]);
        }
        if ($this->request->filled('wholesale_id')) {
            $quoteIds = quotationModel::where('quote_wholesale', $this->request->wholesale_id)->pluck('quote_id');
            $query->whereIn('payment_wholesale_quote_id', $quoteIds);
        }
        if ($this->request->filled('quote_number')) {
            $query->whereHas('quote', function ($q) {
                $q->where('quote_number', 'like', '%' . $this->request->quote_number . '%');
            });
        }

        $paymentWholesale = $query->latest()->get();
        return view('exports.payment-wholesale', [
            'paymentWholesale' => $paymentWholesale
        ]);
    }
}
