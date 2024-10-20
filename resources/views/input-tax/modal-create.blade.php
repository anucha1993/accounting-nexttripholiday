<div class="modal-body">
    <div class="header">
        <h5>บันทึกภาษีซื้อ</h5>
    </div>
    <form action="#" method="POST">
        @csrf
        <div class="row">
            <div class="col">
                <label>ประเภท</label>
                <select name="input_tax_type" id="" class="form-select">
                    <option value="0">ภาษีซื้อ</option>
                    <option value="1">ต้นทุนอื่นๆ</option>
                </select>
                
            </div>
        </div>
    </form>
</div>