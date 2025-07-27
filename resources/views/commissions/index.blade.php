@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">
        <div class="card">
            <div class="card-header">
                <h3>Commission Groups</h3>
            </div>
            <div class="card-body">
                @canany(['commission.manage'])
                <button class="btn btn-success mb-3" id="newCommission">+ เพิ่มกลุ่มค่าคอมมิชชั่น</button>
                @endcanany
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อกลุ่ม</th>
                            <th>Sale IDs</th>
                            <th>ประเภท</th>
                            <th>ช่วง Commission</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($commissions as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @php
                                        $saleNames = \App\Models\sales\saleModel::whereIn('id', $item->sale_ids ?? [])
                                            ->pluck('name')
                                            ->toArray();
                                    @endphp
                                    {{ implode(', ', $saleNames) }}
                                </td>
                                <td>{{ $item->type }}</td>
                                <td>
                                    @foreach ($item->commissionLists as $list)
                                        <div>{{ $list->min_amount }} - {{ $list->max_amount }} =
                                            {{ $list->commission_calculate }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    @canany(['commission.manage'])
                                    <button class="btn btn-warning edit-btn" data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}" data-type="{{ $item->type }}"
                                        data-sale_ids='@json($item->sale_ids)'
                                        data-lists='@json($item->commissionLists)'>
                                        แก้ไข
                                    </button>
                                    @endcanany
                                    @canany(['commission.manage'])
                                    <form action="{{ route('commissions.destroy', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('ยืนยันการลบ?')">ลบ</button>
                                    </form>
                                    @endcanany
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- MODAL --}}
                <div class="modal fade" id="commissionModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="commissionForm" method="POST" action="{{ route('commissions.store') }}">
                                @csrf
                                <input type="hidden" name="commission_id" id="commission_id">

                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">เพิ่ม/แก้ไข Commission</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <label>ชื่อกลุ่ม</label>
                                    <input type="text" name="name" class="form-control" required>

                                    <label>เลือกพนักงานขาย</label>
                                    <select name="sale_ids[]" class="form-select" multiple id="sale_ids">
                                        @foreach ($sales as $sale)
                                            @php $usedGroup = $usedSalesMap[$sale->id] ?? null; @endphp
                                            <option value="{{ $sale->id }}" data-used-group="{{ $usedGroup }}"
                                                @if ($usedGroup && $usedGroup != old('commission_id')) data-disabled="true" @endif
                                                style="{{ $usedGroup ? 'color: #999;' : '' }}">
                                                {{ $sale->name }}{{ $usedGroup ? ' (ใช้แล้ว)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label>ประเภท</label>
                                    <select name="type" class="form-control" required>
                                        <option value="step-QT">Step-Qt</option>
                                        <option value="percent-QT">Percent-QT</option>
                                        <option value="step-Total">Step-Total</option>
                                        <option value="percent-Total">Percent-Total</option>
                                    </select>

                                    <hr>
                                    <table class="table" id="commissionTable">
                                        <thead>
                                            <tr>
                                                <th>Min</th>
                                                <th>Max</th>
                                                <th>จำนวนเงิน</th>
                                                <th><button type="button" class="btn btn-success" id="addRow">+</button>
                                                </th>
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

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#sale_ids').select2({
                dropdownParent: $("#commissionModal"),
                placeholder: "เลือกพนักงานขาย",
                width: '100%'
            });

            $('#sale_ids').on('select2:selecting', function(e) {
                const option = $(e.params.args.data.element);
                if (option.data('disabled')) {
                    alert('พนักงานขายคนนี้ถูกใช้ในกลุ่มอื่นแล้ว');
                    e.preventDefault();
                }
            });

            $('#newCommission').click(function() {
                $('#commissionForm').trigger('reset');
                $('#commission_id').val('');
                $('#commissionForm').attr('action', '{{ route('commissions.store') }}');
                $('#commissionTable tbody').html('');
                $('#sale_ids').val([]).trigger('change');
                $('#commissionModal').modal('show');
            });

            $('.edit-btn').click(function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let sale_ids = $(this).data('sale_ids');
                let type = $(this).data('type');
                let lists = $(this).data('lists');

                $('#commission_id').val(id);
                $('input[name="name"]').val(name);
                $('select[name="type"]').val(type);
                $('#commissionForm').attr('action', '{{ route('commissions.update') }}');
                $('#sale_ids').val(sale_ids).trigger('change');

                let html = '';
                lists.forEach((item, index) => {
                    html += `<tr>
                <td><input type="number" name="commission_lists[${index}][min_amount]" class="form-control" value="${item.min_amount}"></td>
                <td><input type="number" name="commission_lists[${index}][max_amount]" class="form-control" value="${item.max_amount}"></td>
                <td><input type="number" name="commission_lists[${index}][commission_calculate]" class="form-control" value="${item.commission_calculate}"></td>
                <td><button type="button" class="btn btn-danger removeRow">-</button></td>
            </tr>`;
                });
                $('#commissionTable tbody').html(html);
                $('#commissionModal').modal('show');
            });

            $('#addRow').click(function(e) {
                e.preventDefault();
                let idx = $('#commissionTable tbody tr').length;
                $('#commissionTable tbody').append(`
            <tr>
                <td><input type="number" name="commission_lists[${idx}][min_amount]" class="form-control" required></td>
                <td><input type="number" name="commission_lists[${idx}][max_amount]" class="form-control" required></td>
                <td><input type="number" name="commission_lists[${idx}][commission_calculate]" class="form-control" required></td>
                <td><button type="button" class="btn btn-danger removeRow">-</button></td>
            </tr>
        `);
            });

            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection
