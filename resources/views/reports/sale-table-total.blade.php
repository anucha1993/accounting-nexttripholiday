<div class="table-responsive">
     <table class="table mb-0" id="saleTableTotal">
         <thead>
             <tr>
                 <th>No.</th>
                 <th>Quotes</th>
                 <th>ช่วงเวลาเดินทาง</th>
                 <th>โฮลเซลล์</th>
                 <th>ชื่อลูกค้า</th>
                 <th>ประเทศ</th>
                 <th>แพคเกจทัวร์ที่ซื้อ</th>
                 <th>ที่มา</th>
                 <th>เซลล์ผู้ขาย</th>
                 <th>PAX</th>
                   @if(!Auth::user()->getRoleNames()->contains('sale'))
                 <th>ค่าบริการ</th>
                 <th>ส่วนลด</th>
                 <th>ยอดรวมสุทธิ</th>
                 <th>ยอดชำระโฮลเซลล์</th>
                 <th>ต้นทุนอื่นๆ</th>
                 <th>ต้นทุนรวม</th>
                 <th>กำไร</th>
                 <th>กำไรเฉลี่ย:คน</th>
                 @endif
                 <th>คอมมิชชั่นทั้งสิ้น</th>
                 <th>CommissionGroup</th>

             </tr>
         </thead>
         <tbody>
             @php
                 // ใช้ $saleGroups ที่ controller ส่งมา ไม่ต้อง group ใน blade
             @endphp
             @foreach ($saleGroups as $group)
                 @php
                     $saleName = $group['items']->first()->Salename->name ?? '';
                     $netProfitSum = $group['net_profit_sum'];
                     $paxSum = $group['pax_sum'];
                     // หา commission status ของ group (ถ้าใน group มี quote_commission = 'N' อย่างน้อย 1 รายการ ให้ถือว่าไม่จ่ายค่าคอม)
                     $hasNoCommission = $group['items']->contains(function($item) {
                         return $item->quote_commission === 'N';
                     }) ? 'Y' : 'N';
                     
                     $commission = calculateCommission($netProfitSum, $group['sale_id'], 'total', $paxSum, $hasNoCommission);
                     // รวมข้อมูล Quotes, ช่วงเวลาเดินทาง, โฮลเซลล์, ชื่อลูกค้า, ประเทศ, แพคเกจทัวร์, ที่มา
                     $quotes = $group['items']->pluck('quote_number')->implode(', ');
                     $dateRanges = $group['items']->map(function($item) {
                         return date('d/m/Y', strtotime($item->quote_date_start)) . '-' . date('d/m/Y', strtotime($item->quote_date_end));
                     })->unique()->implode(', ');
                     $wholesales = $group['items']->map(function($item) {
                         return $item->quoteWholesale->code ?? '';
                     })->unique()->implode(', ');
                     $customers = $group['items']->map(function($item) {
                         return $item->customer->customer_name ?? '';
                     })->unique()->implode(', ');
                     $countries = $group['items']->map(function($item) {
                         return $item->quoteCountry->iso2 ?? '';
                     })->unique()->implode(', ');
                     $tours = $group['items']->map(function($item) {
                         return $item->quote_tour_name ?? $item->quote_tour_name1 ?? '';
                     })->unique()->implode(', ');
                     $sources = $group['items']->map(function($item) use ($campaignSource) {
                         $sourceName = '';
                         if (isset($item->customer->customer_campaign_source) && !empty($item->customer->customer_campaign_source) && isset($campaignSource)) {
                             $source = $campaignSource->firstWhere('campaign_source_id', $item->customer->customer_campaign_source);
                             $sourceName = $source ? $source->campaign_source_name : '';
                         }
                         return $sourceName ?: 'none';
                     })->unique()->implode(', ');
                 @endphp
                 <tr>
                     <td>
                        {{ $quotationSuccess->count() - $loop->index }}
                    </td>
                     <td>{{ $quotes }}</td>
                     <td>{{ $dateRanges }}</td>
                     <td>{{ $wholesales }}</td>
                     <td>{{ $customers }}</td>
                     <td>{{ $countries }}</td>
                     <td>{{ $tours }}</td>
                     <td>{{ $sources }}</td>
                     <td>{{ $saleName }}</td>
                     <td>{{ $paxSum }}</td>
                        @if(!Auth::user()->getRoleNames()->contains('sale'))
                     <td>{{ number_format($group['items']->sum(function($item) { return $item->quote_grand_total + $item->quote_discount; }), 2) }}</td>
                     <td>{{ number_format($group['items']->sum('quote_discount'), 2) }}</td>
                     <td>{{ number_format($group['items']->sum('quote_grand_total'), 2) }}</td>
                     <td>{{ number_format($group['items']->sum(function($item) { return $item->getWholesalePaidNet(); }), 2) }}</td>
                     <td>{{ number_format($group['items']->sum(function($item) { return $item->getTotalOtherCost(); }), 2) }}</td>
                     <td>{{ number_format($group['items']->sum(function($item) { return $item->getTotalCostAll(); }), 2) }}</td>
                     <td>{{ number_format($netProfitSum, 2) }}</td>
                     <td>{{ $paxSum > 0 ? number_format($netProfitSum / $paxSum, 2) : '0.00' }}</td>
                     <td>{{ number_format($commission['calculated'] ?? 0, 2) }}</td>
                     @endif
                     <td>
                         @if ($hasNoCommission === 'Y')
                             <small><b>ไม่จ่ายค่าคอมมิชชั่น :</b>
                                 {{ optional($group['items']->first(function($item){ return $item->quote_commission === 'N'; }))->quote_note_commission ?? '' }}
                             </small>
                         @else
                             <small>{{ $commission['amount'] ?? '' }}/</small>
                             <small>{{ $commission['group_name'] ?? '' }}</small>
                         @endif
                     </td>
                 </tr>
             @endforeach
         </tbody>
         <tfoot>
            <tr>
                <th colspan="9">รวม</th>
                <th>{{ $saleGroups->sum('pax_sum') }}</th>
                  @if(!Auth::user()->getRoleNames()->contains('sale'))
                <th>{{ number_format($saleGroups->sum(function($group) { return $group['items']->sum(function($item) { return $item->quote_grand_total + $item->quote_discount; }); }), 2) }}</th>
                <th>{{ number_format($saleGroups->sum(function($group) { return $group['items']->sum('quote_discount'); }), 2) }}</th>
                <th>{{ number_format($saleGroups->sum(function($group) { return $group['items']->sum('quote_grand_total'); }), 2) }}</th>
                <th>{{ number_format($saleGroups->sum(function($group) { return $group['items']->sum(function($item) { return $item->getWholesalePaidNet(); }); }), 2) }}</th>
                <th>{{ number_format($saleGroups->sum(function($group) { return $group['items']->sum(function($item) { return $item->getTotalOtherCost(); }); }), 2) }}</th>
                <th>{{ number_format($saleGroups->sum(function($group) { return $group['items']->sum(function($item) { return $item->getTotalCostAll(); }); }), 2) }}</th>
                <th>{{ number_format($saleGroups->sum('net_profit_sum'), 2) }}</th>
                <th>{{ $saleGroups->sum('pax_sum') > 0 ? number_format($saleGroups->sum('net_profit_sum') / $saleGroups->sum('pax_sum'), 2) : '0.00' }}</th>
                @endif
                <th>{{ number_format($saleGroups->sum(function($group) {
                    $commission = calculateCommission($group['net_profit_sum'], $group['sale_id'], 'total', $group['pax_sum']);
                    return $commission['calculated'] ?? 0;
                }), 2) }}</th>
                <th>CommissionGroup</th>
            </tr>
        </tfoot>
     </table>
 </div>

 <script>
    $('#saleTableTotal').DataTable({
        "paging":   false,
        "ordering": true,
        "info":     false,
        "searching": false,
        "columnDefs": [
            { "orderable": false, "targets": -1 } // ปิดการเรียงลำดับคอลัมน์สุดท้าย (CommissionGroup)
        ]
    });
 </script>
