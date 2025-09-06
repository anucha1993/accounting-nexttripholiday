<?php
namespace App\Services;

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;

class QuotationFilterService
{
    public static function filter(Request $request)
    {
        $user = Auth::user();

        $query = quotationModel::with([
            'customer', // For customer details and filtering
            'paymentWholesale', // For wholesale payment calculations
            'quoteWholesale', // For wholesale details
            'Salename', // For sale person details
            'InputTaxVat', // For tax calculations
            'invoiceVat', // For invoice details
            'payment', // For payment calculations
            'quoteBooking', // For booking details
            'quoteCountry', // For country details
            'airline' // For airline details
        ])
        ->where('quote_status', 'success');

        // Only show quotes for the user's sale_id if they have the 'sale' role
        if ($user && $user->roles->pluck('name')->contains('sale')) {
            $query = $query->where('quote_sale', $user->sale_id);
        }

        if ($request->filled('date_start')) {
            $query = $query->where('quote_date_start', '>=', $request->input('date_start'));
        }

        if ($request->filled('date_end')) {
            $query = $query->where('quote_date_start', '<=', $request->input('date_end'));
        }

        if ($request->filled('sale_id')) {
            $query = $query->where('quote_sale', $request->input('sale_id'));
        }

        if ($request->filled('campaign_source_id')) {
            $query = $query->whereHas('customer', function ($q) use ($request) {
                $q->where('customer_campaign_source', $request->campaign_source_id);
            });
            // Add campaign source info to eager loading
            $query->with(['customer' => function($q) use ($request) {
                $q->select(['customer_id', 'customer_name', 'customer_campaign_source']);
            }]);
        }

        if ($request->filled('wholsale_id')) {
            $query = $query->where('quote_wholesale', $request->input('wholsale_id'));
        }

        if ($request->filled('country_id')) {
            $query = $query->where('quote_country', $request->input('country_id'));
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query = $query->where(function ($q) use ($keyword) {
                $q->where('quote_number', 'LIKE', "%$keyword%")
                  ->orWhere('quote_tour_name', 'LIKE', "%$keyword%")
                  ->orWhereHas('customer', function ($q2) use ($keyword) {
                      $q2->where('customer_name', 'LIKE', "%$keyword%");
                  });
            });
        }

        // Order by quote date_start for better pagination performance
        $query->orderBy('quote_date_start', 'desc');
        
        // Get all results with selected relationships
        $results = $query->get();
        
        // Apply additional filtering
        $filteredItems = collect($results)->filter(function ($item) {
            // Check customer payment status
            $customerPaid = $item->GetDeposit() ?? 0; // Using eager loaded payment relationship
            $grandTotal = $item->quote_grand_total ?? 0;

            // Check wholesale payment status
            $wholesaleOutstanding = 0;
            if ($item->quoteWholesale) { // Using eager loaded relationship
                $wholesaleOutstanding = $item->inputtaxTotalWholesale() - $item->getWholesalePaidNet();
            }

            // Check forbidden quote payment statuses
            $status = getQuoteStatusQuotePayment($item); // Helper function using eager loaded relationships
            $forbidden = ['รอคืนเงินลูกค้า', 'ยังไม่ได้คืนเงินลูกค้า'];
            if (Str::contains($status, $forbidden)) {
                return false;
            }

            // Check forbidden wholesale payment statuses
            $wholesaleStatus = getStatusPaymentWhosale($item); // Helper function using eager loaded relationships
            $forbiddenWholesale = [
                'รอโฮลเซลล์คืนเงิน',
                'โอนเงินให้โฮลเซลล์เกิน',
                'รอชำระเงินมัดจำ',
                'รอชำระเงินส่วนที่เหลือ',
            ];
            if (Str::contains($wholesaleStatus, $forbiddenWholesale)) {
                return false;
            }

            // Only include items where customer has paid enough and no wholesale outstanding balance
            return ($customerPaid >= $grandTotal) && ($wholesaleOutstanding == 0);
        });

        // Create a new paginator with filtered results
        $perPage = $request->input('length', 10);
        $page = $request->input('page', 1);
        $items = $filteredItems->forPage($page, $perPage);
        
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $filteredItems->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query()
            ]
        );
    }
}
