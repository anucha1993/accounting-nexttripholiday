<?php
namespace App\Services;

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QuotationFilterService
{
    public static function filter(Request $request)
    {
        $user = Auth::user();
        $userRoles = $user->getRoleNames();

        $query = quotationModel::where('quote_status', 'success');

        if ($userRoles->contains('sale')) {
            $query->where('quote_sale', $user->sale_id);
        }

        if ($request->filled('date_start')) {
            $query->where('quote_date_start', '>=', $request->input('date_start'));
        }

        if ($request->filled('date_end')) {
            $query->where('quote_date_start', '<=', $request->input('date_end'));
        }

        if ($request->filled('sale_id')) {
            $query->where('quote_sale', $request->input('sale_id'));
        }

        if ($request->filled('campaign_source_id')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('customer_campaign_source', $request->campaign_source_id);
            });
        }

        if ($request->filled('wholsale_id')) {
            $query->where('quote_wholesale', $request->input('wholsale_id'));
        }

        if ($request->filled('country_id')) {
            $query->where('quote_country', $request->input('country_id'));
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('quote_number', 'LIKE', "%$keyword%")
                  ->orWhere('quote_tour_name', 'LIKE', "%$keyword%")
                  ->orWhereHas('customer', function ($q2) use ($keyword) {
                      $q2->where('customer_name', 'LIKE', "%$keyword%");
                  });
            });
        }

        return $query->get()->filter(function ($item) {
            $customerPaid = $item->GetDeposit() ?? 0;
            $grandTotal = $item->quote_grand_total ?? 0;

            $wholesaleOutstanding = 0;
            if (method_exists($item, 'inputtaxTotalWholesale') && method_exists($item, 'getWholesalePaidNet')) {
                $wholesaleOutstanding = $item->inputtaxTotalWholesale() - $item->getWholesalePaidNet();
            }

            $status = getQuoteStatusQuotePayment($item);
            $forbidden = ['รอคืนเงินลูกค้า', 'ยังไม่ได้คืนเงินลูกค้า'];
            foreach ($forbidden as $word) {
                if (strpos($status, $word) !== false) {
                    return false;
                }
            }

            $wholesaleStatus = getStatusPaymentWhosale($item);
            $forbiddenWholesale = [
                'รอโฮลเซลล์คืนเงิน',
                'โอนเงินให้โฮลเซลล์เกิน',
                'รอชำระเงินมัดจำ',
                'รอชำระเงินส่วนที่เหลือ',
            ];
            foreach ($forbiddenWholesale as $word) {
                if (strpos($wholesaleStatus, $word) !== false) {
                    return false;
                }
            }

            return ($customerPaid >= $grandTotal) && ($wholesaleOutstanding == 0);
        })->values();
    }
}
