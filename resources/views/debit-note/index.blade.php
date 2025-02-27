@extends('layouts.template')

@section('content')
    <style>
        .table-custom input,
        .table-custom select {
            width: 100%;
            padding: 3px;
            margin-bottom: 10px;
        }

        .add-row {
            margin: 10px 0;
            text-align: left;
        }

        .select2-selection {
            height: 30px !important;
            text-align: left;
            z-index: 9999;
        }

        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .readonly {
            background-color: #e0e0e0;
        }
    </style>

    </style>
    <div class="container-fluid page-content email-app">
        <!-- Todo list-->
        {{-- <div class="email-app todo-box-container container-fluid"> --}}
        <div class="todo-listing ">
            <div class=" border bg-white">
                <h4 class="text-center my-4">ใบลดหนี้ Debit Note
                </h4>
            </div>
        </div>
        <br>

        <div class="todo-listing ">
            <div class=" border bg-white">
                <table class="table table">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>เลขที่ใบลดหนี้</th>
                            <th>Ref.Quote</th>
                            <th>Ref.Tax</th>
                            <th>สาเหตุ</th>
                            <th>มูลค่าเดิม</th>
                            <th>ผลต่าง</th>
                            <th>มูลค่าที่ถูกต้อง</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($debitNote as $key => $item)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $item->debitnote_number }}</td>
                                <td><a href="{{ route('quote.editNew', $item->quote->quote_id) }}" target="_blank">
                                        {{ $item->quote->quote_number }}</a></td>
                                <td><a href="{{ route('mpdf.taxreceipt', $item->invoice->invoice_id) }}" target="_blank">
                                        {{ $item->taxinvoice->taxinvoice_number }}</a></td>
                                <td>{{ $item->debitnote_cause }}</td>
                                <td>{{ number_format($item->debitnote_total_old, 2) }}</td>
                                <td>{{ number_format($item->debitnote_difference, 2) }}</td>
                                <td>{{ number_format($item->debitnote_total_new, 2) }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">

                                        <div class="btn-group btn-group-sm" role="group">
                                            <button id="btnGroupDrop1" type="button"
                                                class="btn btn-light-success text-secondary font-weight-medium dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                <a href="{{route('debit-note.edit',$item->debitnote_id)}}" class=" dropdown-item text-info"> <i
                                                        class="fa fa-edit"></i> แก้ไข</a>
                                                <a class="dropdown-item" href="#" target="_blink"><i
                                                        class="fa fa-print text-danger"></i> พิมพ์</a>
                                                <a class="dropdown-item" href="#"><i
                                                        class="fas fa-envelope text-info"></i> ส่งเมล</a>
                                                <a class="dropdown-item" href="#"><i class="fas fa-share-square text-info"></i> สร้างซ้ำ</a>
                                                <a class="dropdown-item" href="#"><i class="fas fa-trash text-danger"></i> ลบ</a>

                                            </div>
                                        </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
