@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">
        <div class="row">
            <div class="col-md-9">

                <div class="card">
                    <div class="card-header bg-info text-white">
                        Quotation No. : {{ $quotationModel->quote_number }}
                        <span class="float-end">วันที่ออกใบเสนอราคา :
                            {{ thaidate('j F Y', $quotationModel->quote_date) }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <div class="card">
                            <div class="card-header ">
                                <i data-feather="user-check" class="feather-sm fill-white me-2 text-primary "></i>
                                รายละเอียดลูกค้า (Customer)
                            </div>
                            <div class="card-body">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td align="right" class="text-info">ชื่อลูกค้า :</td>
                                            <td>&nbsp; คุณ {{ $customer->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">ที่อยู่ :</td>
                                            <td>&nbsp; {{ $customer->customer_address ? $customer->customer_address : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">อีเมล์ :</td>
                                            <td>&nbsp; {{ $customer->customer_email ? $customer->customer_email : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">เบอร์มือถือ :</td>
                                            <td>&nbsp; {{ $customer->customer_tel ? $customer->customer_tel : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">เบอร์โทรสาร (Fax) :</td>
                                            <td>&nbsp; {{ $customer->customer_fax ? $customer->customer_fax : '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">ลูกค้าจาก :</td>
                                            <td>&nbsp;
                                                {{ $customer->campaign_source_name ? $customer->campaign_source_name : '-' }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header ">
                                <i data-feather="file-text" class="feather-sm fill-white me-2 text-primary "></i>
                                รายละเอียดใบจองทัวร์ (Booking Form)
                            </div>
                            <div class="card-body">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td align="right" class="text-info">Quotation No. :</td>
                                            <td>&nbsp; {{ $quotationModel->quote_number }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">Booking No. :</td>
                                            <td>&nbsp; {{ $quotationModel->quote_booking}}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">วันที่จองทัวร์ :</td>
                                            <td>&nbsp;
                                                {{ $customer->quotationModel ? thaidate('j F Y', $quotationModel->quote_booking_create)  : thaidate('j F Y', $quotationModel->quote_date) }}</td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">รหัสทัวร์ :</td>
                                            <td>&nbsp; {{ $quotationModel->quote_booking ? $quotationModel->quote_tour : $quotationModel->quote_tour_code }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right" class="text-info">พนักงานขาย :</td>
                                            <td>&nbsp; {{ $sale->name }}</td>
                                        </tr>

                                        <tr>
                                            <td align="right" class="text-info">Email :</td>
                                            <td>&nbsp; nexttripholiday@hotmail.com</td>
                                        </tr>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card-group">
                            <div class="card p-2 p-lg-3">
                                <div class="p-lg-3 p-2">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-group">
                            <div class="card p-2 p-lg-3">
                                <div class="p-lg-3 p-2">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="printer" class="feather-sm fill-white me-2 text-danger "></i>
                            พิมพ์ใบจองทัวร์
                        </button>

                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="folder-plus" class="feather-sm fill-white me-2 text-success"></i>
                            ออกใบแจ้งหนี้
                        </button>

                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="repeat" class="feather-sm fill-white me-2 text-info"></i>
                            คัดลอกใบจองทัวร์
                        </button>

                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="dollar-sign" class="feather-sm fill-white me-2 text-success"></i>
                            แจ้งชำระเงิน
                        </button>

                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="edit" class="feather-sm fill-white me-2 "></i>
                            แก้ไขใบจองทัวร์
                        </button>

                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="file" class="feather-sm fill-white me-2 text-info"></i>
                            ยกเลิกใบจองทัวร์
                        </button>

                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="dollar-sign" class="feather-sm fill-white me-2 "></i>
                            แจ้งชำระเงินโฮลเซลล์
                        </button>

                        <button type="button"
                            class="justify-content-left w-100 btn btn-rounded btn-outline-dark d-flex align-items-center mb-3">
                            <i data-feather="mail" class="feather-sm fill-white me-2 text-info"></i>
                            ส่งเมลล์ใบจองทัวร์
                        </button>

                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
