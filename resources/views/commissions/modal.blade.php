<!-- Modal -->
<div class="modal fade" id="commissionModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="commissionForm" method="POST" action="{{ route('commissions.store') }}">
                @csrf
                <input type="hidden" name="commission_id" id="commission_id">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">เพิ่ม/แก้ไข Commission</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>

                <div class="modal-body">
                    <label>ชื่อกลุ่ม</label>
                    <input type="text" name="name" class="form-control">

                    <label>เลือกพนักงานขาย</label>
                    <select name="sale_ids[]" class="form-control" multiple>
                        @foreach ($sales as $sale)
                            <option  value="{{ $sale->id }}">{{ $sale->name }}</option>
                        @endforeach
                    </select>

                    <label>ประเภท (step หรือ percentage)</label>
                    <select name="type" class="form-control">
                        <option value="step">Step</option>
                        <option value="percentage">Percentage</option>
                    </select>

                    <hr>
                    <table class="table" id="commissionTable">
                        <thead>
                            <tr>
                                <th>Min</th>
                                <th>Max</th>
                                <th><button type="button" class="btn btn-success" id="addRow">+</button></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>
