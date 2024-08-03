<div class="row">
    <div class="col-md-8 border" style="padding: 10px">
        <div class="row">
            <div class="col-md-12">
                <b>Customer ID :</b> <span style="margin: 20px;">15215498748</span> <a
                    href="#">แก้ไขข้อมูลลูกค้า</a></br>
                <b>Name : </b> <span style="margin: 60px;"> Anucha Yothanan</span></br>
                <b>Address : </b> <span style="margin: 45px;"> เลขที่ 1525 ซอยลาดพร้าว 94(ปัญจมิตร) แขวงพลับพลา
                    เขตวังทองหลาง กรุงเทพมหานคร 10310</span></br>
                <b>Moblie : </b> <span style="margin: 55px;"> 066-095-2919</span></br>
                <b>Fax : </b> <span style="margin: 75px;"> -</span></br>
                <b>Email : </b> <span style="margin: 60px;"> ap.anucha@hotmail.com</span></br>
            </div>

        </div>


    </div>

    <div class="col-md-4 border" style="padding-top: 10px">
        <div class="row">
            <div class="col-md-12">
                <b>Date :</b> <span style="margin: 50px;"> 31-July-24</span></br>
                <b>Booking No :</b> <span style="margin: 5px;"> BO20242969</span></br>
                <b>Sale :</b> <span style="margin: 53px;"> อนุชา โยธานันท์</span></br>
                <b>Email :</b> <span style="margin: 45px;"> ap.anucha@hotmail.com</span></br>
                <b>Tour Code :</b> <span style="margin: 15px;">21541247</span></br>
                <b>Airline :</b> <span style="margin: 40px;"> AirThai</span></br>
            </div>
        </div>
    </div>

    <br>
    <div class="col-md-12">
        <table class="table table">
            <thead>
                <tr class="bg-info text-white">
                    <td style="width: 10px">ลำดับ</td>
                    <td style="width: 800px">รายละเอียด/รายการ</td>
                    <td>ประเภทค่าใช้จ่าย</td>
                    <td style="width: 100px">จำนวน</td>
                    <td style="width: 200px">ราคา/หน่วย</td>
                    <td style="width: 200px">ราคารวม</td>
                </tr>
            </thead>
            <tbody>
            
                @forelse ($invoiceProduct as $key => $item)
                    <tr>
                        <th>{{$key+1}}</th>
                        <th>
                              <select name="product_id" class="form-select">
                              <option selected value="{{$item->product_id}}">{{$item->product_name}}</option>
                              @foreach ($productIncome as $product)
                              <option value="{{$product->product_id}}">{{$product->product_name}}</option>
                              @endforeach
                               
                              </select>
                        </th>
                        <th>
                              <select name="expense" class="form-select">
                                             <option value="expense">รายรับ</option>
                                             <option value="discount">ส่วนลด</option>
                              </select>
                        </th>
                        <th><input type="number" value="{{$item->invoice_qty}}" class="form-control text-end"></th>
                        <th><input type="number" value="{{$item->invoice_price}}" class="form-control text-end"></th>
                        <th><input type="number" value="{{$item->invoice_sum}}" class="form-control text-end"></th>
                    </tr>
                @empty
                    No data
                @endforelse

            </tbody>
        </table>
    </div>
</div>
