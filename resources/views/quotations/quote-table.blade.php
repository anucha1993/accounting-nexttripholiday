<style>
    .quote-table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        font-size: 15px;
        padding: 14px 20px;
    }

    .quote-table th {
        background: #f3f6fb;
        color: #495057;
        font-weight: 500;
        font-size: 13px;
        border-bottom: 2px solid #dee2e6;
    }

    .quote-table td {
        font-size: 13px;
        vertical-align: middle;
    }

    .quote-table .badge {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 12px;
    }

    .quote-table .fa-user {
        color: #007bff;
    }

    .quote-table .fa-file-text {
        color: #764ba2;
    }

    .quote-table .fa-eye {
        color: #17a2b8;
    }

    .quote-table .fa-edit {
        color: #17a2b8;
    }

    .quote-table .fa-minus-circle {
        color: #e74c3c;
    }

    .quote-table .fa-print {
        color: #e74c3c;
    }

    .quote-table .fa-envelope {
        color: #17a2b8;
    }

    .quote-table-summary {
        background: #f8f9fa;
        font-weight: 600;
        color: #007bff;
        border-top: 2px solid #007bff;
    }

    .invoice-table-header {
        background: linear-gradient(135deg, #28a745 0%, #007bff 100%);
        color: white;
        border-radius: 8px 8px 0 0;
        font-weight: 600;
        font-size: 15px;
        padding: 14px 20px;
    }

    .invoice-table th {
        background: #f3f6fb;
        color: #495057;
        font-weight: 500;
        font-size: 13px;
        border-bottom: 2px solid #dee2e6;
        padding: 6px 12px;
    }

    .invoice-table td {
        font-size: 13px;
        vertical-align: middle;
    }

    .invoice-table .badge {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 12px;
    }

    .invoice-table .fa-file {
        color: #007bff;
    }

    .invoice-table .fa-eye {
        color: #17a2b8;
    }

    .invoice-table .fa-edit {
        color: #17a2b8;
    }

    .invoice-table .fa-minus-circle {
        color: #e74c3c;
    }

    .invoice-table .fa-print {
        color: #e74c3c;
    }

    .invoice-table .fa-envelope {
        color: #17a2b8;
    }

    .invoice-table-summary {
        background: #f8f9fa;
        font-weight: 600;
        color: #28a745;
        border-top: 2px solid #28a745;
    }
</style>

<div class="col-md-12">
    <div class="card info-card shadow-sm">
        <div class="quote-table-header d-flex justify-content-between align-items-center">
            <span><i class="fa fa-file-text me-2"></i>รายละเอียดใบจองทัวร์</span>
            <div>
                <small class="me-3">Booking No.: {{ $quotationModel->quote_booking }}</small>
                <a href="javascript:void(0)" class="text-white" onclick="toggleAccordion('table-quote', 'toggle-arrow')">
                    <i class="fas fa-chevron-down" id="toggle-arrow"></i>
                </a>
            </div>
        </div>

        <div class="card-body" id="table-quote" style="display: block;">
            <div class="table-responsive">
                <table class="table quote-table table-hover table-bordered mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 48px;">#</th>
                            <th>รายการ</th>
                            <th class="text-center">จำนวน</th>
                            <th class="text-end">ราคาต่อหน่วย</th>
                            <th class="text-center">รวม 3%</th>
                            <th class="text-end">ราคารวม</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($quoteProducts as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    @if ($item->product_pax === 'Y')
                                        {{ $item->product_name }} <i class="fa fa-user text-secondary"></i> <span
                                            class="text-secondary">(PAX)</span>
                                    @else
                                        {{ $item->product_name }}
                                    @endif

                                </td>
                                <td class="text-center">{{ $item->product_qty }}</td>
                                <td class="text-end">
                                    @if ($item->withholding_tax === 'N')
                                        {{ number_format($item->product_price, 2, '.', ',') }}
                                    @else
                                        {{ number_format($item->product_price * 0.03 + $item->product_price, 2, '.', ',') }}
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->withholding_tax === 'N')
                                        <input type="checkbox" disabled>
                                    @else
                                        <input type="checkbox" checked disabled>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($item->product_sum, 2, '.', ',') }}</td>

                            </tr>
                        @empty
                        @endforelse

                        @if ($quoteProductsDiscount->isNotEmpty())
                            <tr class="text-danger">
                                <td colspan="6">ส่วนลด</td>
                            </tr>
                        @else
                        @endif

                        @forelse ($quoteProductsDiscount as $item)
                            <tr class="text-danger">
                                <td>{{ ++$key }}</td>
                                <td>
                                    @if ($item->product_pax === 'Y')
                                        {{ $item->product_name }} <i class="fa fa-user text-secondary"></i> <span
                                            class="text-secondary">(PAX)</span>
                                    @else
                                        {{ $item->product_name }}
                                    @endif

                                </td>
                                <td class="text-center">{{ $item->product_qty }}</td>
                                <td class="text-end">
                                    @if ($item->withholding_tax === 'N')
                                        {{ number_format($item->product_price, 2, '.', ',') }}
                                    @else
                                        {{ number_format($item->product_price * 0.03 + $item->product_price, 2, '.', ',') }}
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->withholding_tax === 'N')
                                        <input type="checkbox" disabled>
                                    @else
                                        {{ number_format($item->product_price * 0.03, 2, '.', ',') }}
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($item->product_sum, 2, '.', ',') }}</td>

                            </tr>
                        @empty
                        @endforelse


                        <tr class="quote-table-summary">
                            <td align="right" colspan="5"><b>(@bathText($quotationModel->quote_grand_total))</b></td>
                            <td class="text-end">
                                <b><u>{{ number_format($quotationModel->quote_grand_total, 2, '.', ',') }}</u></b>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="col-md-12">
        <div class="info-card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fa fa-file me-2"></i>
                    รายละเอียดใบแจ้งหนี้
                    <span class="float-end">
                        invoice
                        &nbsp; <a href="javascript:void(0)" class="text-muted"
                            onclick="toggleAccordion('table-invoices', 'toggle-arrow-invoices')">
                            <span class="fas fa-chevron-down" id="toggle-arrow-invoices"></span>
                        </a>
                    </span>
                </h5>
            </div>



            <div class="card-body" id="table-invoices" style="display: block;">

                <div class="table-responsive">
                    <table class="table invoice-table table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width: 100px">ประเภท</th>
                                <th>วันที่</th>
                                <th>เลขที่เอกสาร</th>
                                <th class="text-center">ยอดรวมสิทธิ์</th>
                                <th class="text-center">ยอดชำระแล้ว</th>
                                <th class="text-center">ยอดคงค้าง</th>
                                <th class="text-center">หัก ณ. ที่จ่าย</th>
                                <th class="text-left">Actions Report</th>
                                <th class="text-left">Actions</th>
                                <th class="text-left">Cancel</th>
                            </tr>
                        </thead>

                        @php
                            $incomeTotal = 0;
                            $CreditNoteTotal = 0;
                        @endphp

                        <tbody>

                            @forelse ($quotations as $item)
                                <tr {!! $quotationModel->quote_status === 'cancel' ? 'style="background-color: rgb(167, 167, 167)"' : '' !!}>
                                    <td>ใบเสนอราคา</td>
                                    <td>{{ date('d/m/Y', strtotime($quotationModel->created_at)) }}</td>
                                    <td><span class="badge bg-dark">{{ $quotationModel->quote_number }} </span>

                                    </td>
                                    {{-- <td>{{ $item->customer_name }}</td> --}}
                                    <td align="center">
                                        @php
                                            $incomeTotal += $quotationModel->quote_grand_total;
                                        @endphp
                                        {{ number_format($quotationModel->quote_grand_total, 2, '.', ',') }}</td>
                                    <td align="center">
                                        {{ number_format($quotationModel->GetDeposit() - $quotationModel->Refund(), 2, '.', ',') }}
                                    </td>
                                    <td align="center">
                                        {{ number_format($quotationModel->quote_grand_total - $quotationModel->GetDeposit() + $quotationModel->Refund(), 2, '.', ',') }}
                                    </td>
                                    <td align="center">
                                        @if ($item->quote_withholding_tax_status === 'Y')
                                            {{ number_format($item->quote_withholding_tax, 2, '.', ',') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    <td>
                                        <a class="dropdown-item" target="_blank"
                                            href="{{ route('mpdf.quote', $quotationModel->quote_id) }}"
                                            onclick="openPdfPopup(this.href); return false;">
                                            <i class="fa fa-print text-danger "></i>
                                            พิมพ์ใบเสนอราคา
                                        </a>
                                        <a class="dropdown-item mail-quote"
                                            href="{{ route('mail.quote.formMail', $quotationModel->quote_id) }}">
                                            <i class="fas fa-envelope text-info"></i>
                                            ส่งเมล
                                        </a>
                                    </td>


                                    <td align="left">

                                        @if ($quotationModel->quote_status != 'cancel')
                                            @can('edit-quote')
                                                {{-- <a class="dropdown-item modal-quote-edit"
                                                href="{{ route('quote.modalEdit', $quotationModel->quote_id) }}"><i
                                                    class="fa fa-edit text-info"></i> แก้ไข</a> --}}
                                                <a class="dropdown-item modal-quote-edit"
                                                    href="{{ route('quote.modalEdit', ['quotationModel' => $quotationModel->quote_id, 'mode' => 'edit']) }}">
                                                    <i class="fa fa-edit text-info"></i> แก้ไข
                                                </a>
                                            @endcan
                                            @can('create-invoice')
                                                @if (empty($invoiceModel))
                                                    <a class="dropdown-item modal-invoice"
                                                        href="{{ route('invoice.create', $quotationModel->quote_id) }}"><i
                                                            class="fas fa-file-alt"></i> ออกใบแจ้งหนี้</a>
                                                @endif
                                            @endcan

                                            <a class="dropdown-item modal-quote-edit"
                                                href="{{ route('quote.modalView', $quotationModel->quote_id) }}">
                                                <i class="fa fa-eye text-info"></i> ดูรายละเอียด
                                            </a>
                                        @else
                                            <span class="dot-danger"></span>ใบงานถูกยกเลิก
                                        @endif

                                    </td>

                                    <td>

                                        @can('edit-quote')
                                            @if ($quotationModel->quote_status === 'cancel')
                                                <a class="modal-quote-cancel"
                                                    href="{{ route('quote.modalCancel', $quotationModel->quote_id) }}"><i
                                                        class="fas fa-minus-circle text-danger"></i>
                                                    เหตุผลยกเลิกใบงานsss</a>
                                                <br>
                                                <a href="{{ route('quote.recancel', $quotationModel->quote_id) }}"
                                                    class="text-black"
                                                    onclick="return confirm('คุณต้องการนำใบเสนอราคากลับมาใช้ใหม่ใช่ไหม!');">
                                                    <i class=" far fa-share-square"></i> นำกลับมาใช้ใหม่</a>
                                            @else
                                                <a class="modal-quote-cancel"
                                                    href="{{ route('quote.modalCancel', $quotationModel->quote_id) }}"><i
                                                        class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                            @endif
                                        @endcan
                                    </td>
                                </tr>
                            @empty

                            @endforelse


                            {{-- Invoice table --}}

                            @forelse ($invoices as $itemInvoice)
                                <tr {!! $quotationModel->quote_status === 'cancel' ? 'style="background-color: rgb(167, 167, 167)"' : '' !!}>
                                    <td class="text-success">ใบแจ้งหนี้</td>
                                    <td>{{ date('d/m/Y', strtotime($itemInvoice->invoice_date)) }}</td>
                                    <td><span class="badge bg-dark">{{ $itemInvoice->invoice_number }}</span>
                                    </td>
                                    <td align="center">


                                        {{ number_format($itemInvoice->invoice_grand_total, 2, '.', ',') }}</td>
                                    <td align="center">
                                        N/A
                                    </td>
                                    <td align="center">
                                        N/A
                                    </td>
                                    <td align="center">
                                        @if ($itemInvoice->invoice_withholding_tax_status === 'Y')
                                            {{ number_format($itemInvoice->invoice_withholding_tax, 2, '.', ',') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <a class="dropdown-item" onclick="openPdfPopup(this.href); return false;"
                                            href="{{ route('mpdf.invoice', $itemInvoice->invoice_id) }}"><i
                                                class="fa fa-print text-danger"></i>
                                            พิมพ์ใบแจ้งหนี้</a>

                                        <a class="dropdown-item mail-quote"
                                            href="{{ route('mail.invoice.formMail', $itemInvoice->invoice_id) }}"><i
                                                class="fas fa-envelope text-info"></i>
                                            ส่งเมล</a>
                                    </td>

                                    <td>
                                        @can('edit-invoice')
                                            @if ($itemInvoice->invoice_status !== 'cancel')
                                                <a class="dropdown-item modal-invoice-edit"
                                                    href="{{ route('invoice.edit', ['invoiceModel' => $itemInvoice->invoice_id, 'mode' => 'edit']) }}">
                                                    <i class="fa fa-edit text-info"></i> แก้ไข</a>

                                                @if ($itemInvoice->invoice_status === 'wait' && $quotationModel->quote_payment_status === 'success')
                                                    <a class="dropdown-item"
                                                        href="{{ route('invoice.taxinvoice', $itemInvoice->invoice_id) }}"
                                                        onclick="return confirm('ระบบจะอ้างอิงรายการสินค้าจากใบแจ้งหนี้');"><i
                                                            class="fas fa-file-alt"></i> ออกใบกำกับภาษี</a>
                                                @endif
                                            @else
                                                <span class="dot-danger"></span>ใบงานถูกยกเลิก
                                            @endif
                                        @endcan
                                        <a class="dropdown-item modal-invoice-edit"
                                            href="{{ route('invoice.edit', ['invoiceModel' => $itemInvoice->invoice_id, 'mode' => 'view']) }}">
                                            <i class="fa fa-eye text-info"></i> ดูรายละเอียด
                                        </a>
                                        <script>
                                            $(document).on('submit', '.form-invoice-delete', function(e) {
                                                if (!confirm('คุณต้องการลบใบงานนี้ใช่ไหม!')) {
                                                    e.preventDefault();
                                                }
                                            });
                                        </script>

                                    </td>
                                    <td>
                                        @can('cancel-invoice')
                                            @if ($itemInvoice->invoice_status === 'cancel')
                                                <a class="modal-invoice-cancel"
                                                    href="{{ route('invoice.modalCancel', $itemInvoice->invoice_id) }}"><i
                                                        class="fas fa-minus-circle text-danger"></i>เหตุผลยกเลิกใบงาน</a>
                                                <br>
                                                <a href="{{ route('quote.recancel', $quotationModel->quote_id) }}"
                                                    class="text-white"
                                                    onclick="return confirm('คุณต้องการนำใบเสนอราคากลับมาใช้ใหม่ใช่ไหม!');">
                                                    <i class=" far fa-share-square"></i> นำกลับมาใช้ใหม่</a>
                                            @else
                                                {{-- <a class="modal-invoice-cancel" href="{{ route('invoice.modalCancel', $itemInvoice->invoice_id) }}"><i
                                        class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a> --}}
                                                <a class="modal-quote-cancel"
                                                    href="{{ route('quote.modalCancel', $quotationModel->quote_id) }}"><i
                                                        class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                            @endif
                                        @endcan

                                        <form action="{{ route('invoice.delete', $itemInvoice->invoice_id) }}"
                                            method="POST" class="d-inline form-invoice-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn text-danger">
                                                <i class="fas fa-trash-alt"></i> ลบใบแจ้งหนี้
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                            @endforelse

                            {{-- taxinvoices table --}}

                            @forelse ($taxinvoices as $item)
                                <tr {!! $quotationModel->quote_status === 'cancel' ? 'style="background-color: rgb(167, 167, 167)"' : '' !!}>
                                    <td class="text-primary">ใบกำกับภาษี</td>
                                    <td>{{ date('d/m/Y', strtotime($item->taxinvoice_date)) }}</td>
                                    <td><span class="badge bg-dark">{{ $item->taxinvoice_number }}</span>
                                    </td>
                                    <td align="center">
                                        {{ number_format($item->invoice_grand_total, 2, '.', ',') }}</td>
                                    <td align="center">
                                        N/A
                                    </td>
                                    <td align="center">
                                        N/A
                                    </td>

                                    <td align="center">
                                        @if ($item->invoice_withholding_tax_status === 'Y')
                                            {{ number_format($item->invoice_withholding_tax, 2, '.', ',') }} <br>
                                            {{-- <a href="{{ route('withholding.edit', $document->id) }}" > <i class="fa fa-file text-danger"></i> แก้ไขใบหัก ณ ที่จ่าย</a> --}}
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    <td>
                                        <a class="dropdown-item" onclick="openPdfPopup(this.href); return false;"
                                            href="{{ route('mpdf.taxreceipt', $item->invoice_id) }}"><i
                                                class="fa fa-print text-danger"></i>
                                            พิมพ์ใบกำกับภาษี</a>



                                        <a class="dropdown-item mail-quote"
                                            href="{{ route('mail.taxreceipt.formMail', $item->invoice_id) }}"><i
                                                class="fas fa-envelope text-info"></i>
                                            ส่งเมล</a>
                                    </td>

                                    {{-- <td>
                                 <a class="dropdown-item"
                                 href="{{ route('invoice.taxinvoice', $item->invoice_id) }}"
                                 onclick="return confirm('ระบบจะอ้างอิงรายการสินค้าจากใบแจ้งหนี้');"><i
                                     class="fas fa-file-alt"></i> ออกใบกำกับภาษี</a>
                             </td> --}}
                                    <td>
                                        @can('edit-invoice')
                                            @if ($item->taxinvoice_status !== 'cancel')
                                                <a class="dropdown-item modal-invoice-edit"
                                                    href="{{ route('invoice.edit', ['invoiceModel' => $itemInvoice->invoice_id, 'mode' => 'edit']) }}">
                                                    <i class="fa fa-edit text-info"></i> แก้ไข</a>
                                            @else
                                                <span class="dot-danger"></span>ใบงานถูกยกเลิก
                                            @endif
                                        @endcan
                                        <a class="dropdown-item modal-invoice-edit"
                                            href="{{ route('invoice.edit', ['invoiceModel' => $itemInvoice->invoice_id, 'mode' => 'view']) }}">
                                            <i class="fa fa-eye text-info"></i> ดูรายละเอียด
                                        </a>

                                    </td>
                                    <td>
                                        @can('cancel-invoice')

                                            @if ($item->taxinvoice_status === 'cancel')
                                                <a class="modal-taxinvoice-cancel"
                                                    href="{{ route('taxinvoice.modalCancel', $item->taxinvoice_id) }}"><i
                                                        class="fas fa-minus-circle text-danger"></i> เหตุผลยกเลิกใบงาน</a>
                                                <br>
                                                
                                                <a href="{{ route('quote.recancel', $quotationModel->quote_id) }}"
                                                    class="text-white"
                                                    onclick="return confirm('คุณต้องการนำใบเสนอราคากลับมาใช้ใหม่ใช่ไหม!');">
                                                    <i class=" far fa-share-square"></i> นำกลับมาใช้ใหม่</a>
                                            @else
                                            
                                                <a class="modal-quote-cancel"
                                                    href="{{ route('quote.modalCancel', $quotationModel->quote_id) }}"><i
                                                        class="fas fa-minus-circle text-danger"></i> ยกเลิกใบงาน</a>
                                            @endif

                                        @endcan

                                         <form action="{{ route('taxinvoice.delete', $item->taxinvoice_id) }}"
                                            method="POST" class="d-inline form-taxinvoice-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn text-danger">
                                                <i class="fas fa-trash-alt"></i> ลบใบกำกับภาษี
                                            </button>
                                        </form>


                                    </td>
                                </tr>
                            @empty
                            @endforelse


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    {{-- invoice payment Modal --}}
    <div class="modal fade bd-example-modal-sm modal-lg" id="invoice-payment" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    {{-- debit payment Modal --}}
    <div class="modal fade bd-example-modal-sm modal-lg" id="debit-payment" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    {{-- credit payment Modal --}}
    <div class="modal fade bd-example-modal-sm modal-lg" id="credit-payment" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    {{-- credit payment WholeSale  Quote --}}
    <div class="modal fade bd-example-modal-sm modal-lg" id="quote-payment-wholesale" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    {{-- mail form quote --}}
    <div class="modal fade bd-example-modal-sm modal-lg" id="modal-mail-quote" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>


    {{-- create form invoice --}}
    <div class="modal fade bd-example-modal-sm modal-xl" id="modal-invoice-create" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    {{-- Edit form invoice --}}
    <div class="modal fade bd-example-modal-sm modal-xl" id="modal-invoice-edit" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    {{-- Edit form quote --}}
    <div class="modal fade bd-example-modal-sm modal-xl" id="modal-quote-edit" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>


    {{-- create form debit --}}
    <div class="modal fade bd-example-modal-sm modal-xl" id="modal-debit-create" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>

    {{-- quote cancel --}}
    <div class="modal fade bd-example-modal-sm modal-xl" id="modal-quote-cancel" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>
    {{-- invoice cancel --}}
    <div class="modal fade bd-example-modal-sm modal-xl" id="modal-invoice-cancel" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>
    {{-- taxinvoice cancel --}}
    <div class="modal fade bd-example-modal-sm modal-xl" id="modal-taxinvoice-cancel" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>








    <script>
        function openPdfPopup(url) {
            var width = 800; // กำหนดความกว้างของหน้าต่าง
            var height = 600; // กำหนดความสูงของหน้าต่าง
            var left = (window.innerWidth - width) / 2; // คำนวณตำแหน่งจากด้านซ้ายของหน้าจอ
            var top = (window.innerHeight - height) / 2; // คำนวณตำแหน่งจากด้านบนของหน้าจอ

            // เปิดหน้าต่างใหม่ด้วยการคำนวณตำแหน่งและขนาด
            window.open(url, 'PDFPopup', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left);
        }
        // เปิด modal แก้ไขใบเสนอราคา
        $(".modal-quote-edit").off("click").on("click", function(e) {
            e.preventDefault();
            var modal = $("#modal-quote-edit");

            // ล้างข้อมูลเก่าก่อนเปิด modal
            modal.find(".modal-content").html('');

            // โหลดเนื้อหาใหม่
            modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

            // เมื่อปิด modal, ล้างข้อมูล
            modal.on('hidden.bs.modal', function() {
                $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
            });
        });

        // เปิด modal แก้ไขใบแจ้งหนี้
        $(".modal-invoice").off("click").on("click", function(e) {
            e.preventDefault();
            var modal = $("#modal-invoice-create");

            // ล้างข้อมูลเก่าก่อนเปิด modal
            modal.find(".modal-content").html('');

            // โหลดเนื้อหาใหม่
            modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

            // เมื่อปิด modal, ล้างข้อมูล
            modal.on('hidden.bs.modal', function() {
                $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
            });
        });

        // เปิด modal แก้ไขใบแจ้งหนี้
        $(".modal-invoice-edit").off("click").on("click", function(e) {
            e.preventDefault();
            var modal = $("#modal-invoice-edit");

            // ล้างข้อมูลเก่าก่อนเปิด modal
            modal.find(".modal-content").html('');

            // โหลดเนื้อหาใหม่
            modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

            // เมื่อปิด modal, ล้างข้อมูล
            modal.on('hidden.bs.modal', function() {
                $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
            });
        });

        // เปิด modal เพิ่มใบเพิ่มหนี้
        $(".debit-create").off("click").on("click", function(e) {
            e.preventDefault();
            var modal = $("#modal-debit-create");

            // ล้างข้อมูลเก่าก่อนเปิด modal
            modal.find(".modal-content").html('');

            // โหลดเนื้อหาใหม่
            modal.modal("show").addClass("modal-lg").find(".modal-content").load($(this).attr("href"));

            // เมื่อปิด modal, ล้างข้อมูล
            modal.on('hidden.bs.modal', function() {
                $(this).find(".modal-content").html(''); // รีเซ็ตเนื้อหา
            });
        });





        $(document).ready(function() {
            // modal add payment wholesale quote
            $(".mail-quote").click("click", function(e) {
                e.preventDefault();
                $("#modal-mail-quote")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });

            // modal add payment wholesale quote
            $(".payment-quote-wholesale").click("click", function(e) {
                e.preventDefault();
                $("#quote-payment-wholesale")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });



            // modal add payment invoice
            $(".invoice-modal").click("click", function(e) {
                e.preventDefault();
                $("#invoice-payment")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });
            // modal add payment debit
            $(".debit-modal").click("click", function(e) {
                e.preventDefault();
                $("#debit-payment")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });
            // modal add payment credit
            $(".credit-modal").click("click", function(e) {
                e.preventDefault();
                $("#credit-payment")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });

            $(".modal-quote-cancel").click("click", function(e) {
                e.preventDefault();
                $("#modal-quote-cancel")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });
            $(".modal-invoice-cancel").click("click", function(e) {
                e.preventDefault();
                $("#modal-invoice-cancel")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });
            $(".modal-taxinvoice-cancel").click("click", function(e) {
                e.preventDefault();
                $("#modal-taxinvoice-cancel")
                    .modal("show")
                    .addClass("modal-lg")
                    .find(".modal-content")
                    .load($(this).attr("href"));
            });



        })
    </script>
