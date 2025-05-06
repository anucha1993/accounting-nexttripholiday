@extends('layouts.template')

@section('content')
<div class="container-fluid page-content py-4">
    <div class="container">

        {{-- ฟอร์มเพิ่มรายการ (Create) --}}
        <div class="card mb-4">
            <div class="card-header fw-bold">เพิ่มค่าคอมมิชชั่น</div>
            <div class="card-body">
                <form id="create-form" class="row g-2">
                    @csrf
                    <div class="col-md-3">
                        <input class="form-control" name="name" placeholder="ชื่อ (เช่น Step1)" required>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="type" required>
                            <option value="step">Step</option>
                            <option value="percent">Percent</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="number" step="0.01"
                               name="min_profit" placeholder="Min Profit" required>
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="number" step="0.01"
                               name="max_profit" placeholder="Max Profit (เว้นว่างคือ ∞)">
                    </div>
                    <div class="col-md-2">
                        <input class="form-control" type="number" step="0.01"
                               name="value" placeholder="Value" required>
                    </div>
                    <div class="col-md-1">
                        <select class="form-select" name="unit">
                            <option value="baht">บาท</option>
                            <option value="percent">%</option>
                        </select>
                    </div>
                    <input type="hidden" name="status" value="active">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ตารางรายการ (Read) --}}
        <div class="card">
            <div class="card-header fw-bold">รายการค่าคอมมิชชั่น</div>
            <div class="card-body table-responsive">
                <table class="table align-middle" id="commission-table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Min</th>
                            <th>Condition</th>
                            <th>Max</th>
                            <th>Value</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th style="width:120px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commissions as $item)
                            @include('commissions.partials.row', ['item' => $item])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- โมดัลแก้ไข (Update) --}}
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <form id="edit-form" class="modal-content">
        @csrf @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title">แก้ไขค่าคอมมิชชั่น</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body row g-2"><!-- ช่องฟิลด์จะเติมด้วย JS --></div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
            <button type="submit" class="btn btn-success">อัปเดต</button>
        </div>
    </form>
  </div>
</div>


<script>
$(function () {
    const tableBody = $('#commission-table tbody');
    const token = $('meta[name="csrf-token"]').attr('content');

    /* ---------- Create ---------- */
    $('#create-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('commissions.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            headers: {'X-CSRF-TOKEN': token},
            success(res) {
                tableBody.prepend(res.html);   // ใช้แถว HTML ที่ backend ส่งกลับ
                $('#create-form')[0].reset();
            },
            error(xhr){ alert(xhr.responseJSON.message); }
        });
    });

    /* ---------- Edit (open modal) ---------- */
    tableBody.on('click','.btn-edit',function () {
        const item = $(this).data('item');
        fillEditForm(item);
        $('#editModal').modal('show');
    });

    function fillEditForm(item){
        const body = $('#editModal .modal-body').empty();
        body.append(`
            <input type="hidden" name="id" value="${item.id}">
            <div class="col-md-4"><label class="form-label">Name</label>
              <input class="form-control" name="name" value="${item.name}" required></div>
            <div class="col-md-3"><label class="form-label">Type</label>
              <select class="form-select" name="type">
                <option value="step" ${item.type==='step'?'selected':''}>Step</option>
                <option value="percent" ${item.type==='percent'?'selected':''}>Percent</option>
              </select></div>
            <div class="col-md-2"><label class="form-label">Min Profit</label>
              <input class="form-control" type="number" step="0.01" name="min_profit" value="${item.min_profit}" required></div>
            <div class="col-md-2"><label class="form-label">Max Profit</label>
              <input class="form-control" type="number" step="0.01" name="max_profit" value="${item.max_profit ?? ''}"></div>
            <div class="col-md-2"><label class="form-label">Value</label>
              <input class="form-control" type="number" step="0.01" name="value" value="${item.value}" required></div>
            <div class="col-md-2"><label class="form-label">Unit</label>
              <select class="form-select" name="unit">
                <option value="baht" ${item.unit==='baht'?'selected':''}>บาท</option>
                <option value="percent" ${item.unit==='percent'?'selected':''}>%</option>
              </select></div>
            <div class="col-md-3"><label class="form-label">Status</label>
              <select class="form-select" name="status">
                <option value="active" ${item.status==='active'?'selected':''}>Active</option>
                <option value="inactive" ${item.status==='inactive'?'selected':''}>Inactive</option>
              </select></div>
        `);
    }

    /* ---------- Update ---------- */
    $('#edit-form').on('submit',function(e){
        e.preventDefault();
        const id = $(this).find('input[name=id]').val();
        $.ajax({
            url: `/commissions/${id}`,
            method:'POST',
            data: $(this).serialize(),
            headers: {'X-CSRF-TOKEN': token, 'X-HTTP-Method-Override':'PUT'},
            success(res){
                tableBody.find(`tr[data-id=${id}]`).replaceWith(res.html);
                $('#editModal').modal('hide');
            },
            error(xhr){ alert(xhr.responseJSON.message); }
        });
    });

    /* ---------- Delete ---------- */
    tableBody.on('click','.btn-delete',function(){
        if(!confirm('ลบรายการนี้?')) return;
        const id = $(this).data('id');
        $.ajax({
            url: `/commissions/${id}`,
            method:'POST',
            headers:{'X-CSRF-TOKEN':token,'X-HTTP-Method-Override':'DELETE'},
            success(){
                tableBody.find(`tr[data-id=${id}]`).remove();
            },
            error(xhr){ alert(xhr.responseJSON.message); }
        });
    });
});
</script>

@endsection

