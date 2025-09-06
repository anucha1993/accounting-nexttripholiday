<div class="table-responsive">
    <table class="table mb-0" id="saleTableAll">
        <thead>
            <tr>
                <th>No</th>
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
                <th>CommissionGroup</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($quotationSuccess as $item)
                <tr>
                    <td>
                        {{ $quotationSuccess->count() - $loop->index }}
                    </td>
                    <td>
                        @canany(['quote.view', 'quote.edit'])
                            <a href="{{ route('quote.editNew', $item->quote_id) }}">{{ $item->quote_number }}</a>
                        @endcanany
                    </td>
                    <td>{{ date('d/m/Y', strtotime($item->quote_date_start)) . '-' . date('d/m/Y', strtotime($item->quote_date_end)) }}
                    </td>
                    <td>{{ $item->quoteWholesale->code }}</td>
                    <td>{{ $item->customer->customer_name }}</td>
                    <td>{{ $item->quoteCountry->iso2 }}</td>
                    <td><span data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ $item->quote_tour_name ?? $item->quote_tour_name1 }}">{{ Str::limit($item->quote_tour_name ?? $item->quote_tour_name1, 20) }}</span>
                    </td>
                    <td>
                        @php
                            $sourceName = '';
                            if (
                                isset($item->customer->customer_campaign_source) &&
                                !empty($item->customer->customer_campaign_source) &&
                                isset($campaignSource)
                            ) {
                                $source = $campaignSource->firstWhere(
                                    'campaign_source_id',
                                    $item->customer->customer_campaign_source,
                                );
                                $sourceName = $source ? $source->campaign_source_name : '';
                            }
                        @endphp
                        {{ $sourceName ?: 'none' }}
                    </td>
                    <td>{{ $item->Salename->name }}</td>
                    <td>{{ $item->quote_pax_total }}</td>

                     @if(!Auth::user()->getRoleNames()->contains('sale'))

                    <td>{{ number_format($item->quote_grand_total + $item->quote_discount, 2) }}</td>
                    <td>{{ number_format($item->quote_discount, 2) }}</td>
                    <td>{{ number_format($item->quote_grand_total, 2) }}</td>
                    <td>{{ number_format($item->getWholesalePaidNet(), 2) }}</td>
                    <td>{{ number_format($item->getTotalOtherCost(), 2) }}</td>
                    <td>{{ number_format($item->getTotalCostAll(), 2) }}</td>
                    <td>{{ number_format($item->getNetProfit(), 2) }}</td>
                    <td>{{ number_format($item->getNetProfitPerPax(), 2) }}</td>
                    @endif

                    @php
                        $commission = calculateCommission(
                            $item->getNetProfit(),
                            (string) $item->quote_sale, // สำคัญ! ต้องแปลงเป็น string
                            'all',
                            $item->quote_pax_total,
                            $item->quote_commission,
                        );
                    @endphp

                    {{-- @php dd($commission); @endphp --}}
                    <td>
                        @if ($item->quote_commission === 'N')
                            <small><b>ไม่จ่ายค่าคอมมิชชั่น :</b> {{ $item->quote_note_commission ?? '' }}</small>
                            
                        @else
                            {{-- <small>{{ $commission['amount'] ?? '' }}/</small> --}}
                            <small>{{ $commission['group_name'] ?? 'ไม่ได้กำหนด' }}</small>
                            <br>
                            {{-- <span style="color: #888; font-size: 11px;">
                                [debug]
                                sale_id: {{ (string)$item->quote_sale }} |
                                profit: {{ $item->getNetProfit() }} |
                                group_name: {{ $commission['group_name'] ?? 'null' }} |
                                type: {{ $commission['type'] ?? 'null' }}
                            </span> --}}
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                 
                <th colspan="9">รวม</th>
               
                <th>{{ $quotationSuccess->sum('quote_pax_total') }}</th>
                 @if(!Auth::user()->getRoleNames()->contains('sale'))
                <th>{{ number_format($quotationSuccess->sum(function ($item) {return $item->quote_grand_total + $item->quote_discount;}),2) }}
                </th>
                <th>{{ number_format($quotationSuccess->sum('quote_discount'), 2) }}</th>
                <th>{{ number_format($quotationSuccess->sum('quote_grand_total'), 2) }}</th>
                <th>{{ number_format($quotationSuccess->sum(function ($item) {return $item->getWholesalePaidNet();}),2) }}
                </th>
                <th>{{ number_format($quotationSuccess->sum(function ($item) {return $item->getTotalOtherCost();}),2) }}
                </th>
                <th>{{ number_format($quotationSuccess->sum(function ($item) {return $item->getTotalCostAll();}),2) }}
                </th>
                <th>{{ number_format($quotationSuccess->sum(function ($item) {return $item->getNetProfit();}),2) }}
                </th>
                <th>{{ number_format($quotationSuccess->sum(function ($item) {return $item->getNetProfitPerPax();}),2) }}
                </th>
                @endif
                <th>CommissionGroup</th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#saleTableAll').DataTable({
            // "paging":   false,
            "ordering": true,
            "info":     false,
            "searching": false,
            "columnDefs": [
                { "orderable": false, "targets": -1 } // ปิดการเรียงลำดับคอลัมน์สุดท้าย (CommissionGroup)
            ]
        });
    });
</script>