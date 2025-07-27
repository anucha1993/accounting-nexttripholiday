@extends('layouts.template')

@section('content')
    <div class="container-fluid page-content">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Success - </strong>{{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Error - </strong>{{ session('error') }}
            </div>
        @endif





        <div class="card">
            <div class="card-header">
                <label>
                    <h4>รายการสินค้า และค่าบริการ</h4>
                </label>
                @canany(['product.create'])
                <button type="button" class="btn btn-primary btn-sm float-end font-weight-medium" data-bs-toggle="modal"
                    data-bs-target="#productModal"> เพิ่มข้อมูล</button>
                @endcanany
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered display" id="products">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อตัวเลือกราคา</th>
                                <th>ราคา</th>
                                <th>สิทธิ์การใช้งาน</th>
                                <th>ประเภทการคำนวณ</th>
                                <th>ใช้รวมผล PAX</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="productsTable">
                            <!-- Product rows will be added here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Modela Add Product --}}
        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            เพิ่มรายการค่าบริการ
                        </h4>
                        <hr>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="productForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <label>ชื่อรายการสินค้า/บริการ</label>
                                    <input type="text" class="form-control" name="product_name" id="product_name"
                                        placeholder="ชื่อรายการสินค้า" required>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label>ราคาสินค้า/บริการ</label>
                                    <input type="number" class="form-control" name="product_price" placeholder="0.00"
                                        id="product_price" value="0" required>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label>ผู้กลุ่มมีสิทธิ์ใช้งาน</label>
                                    <select class="roles form-control" name="product_roles" multiple="multiple"
                                        id="product_roles" required style="height: 36px; width: 100%">
                                        @forelse ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @empty
                                            <!-- Handle case where there are no roles -->
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label>ประเภทการคำนวณ</label>
                                    <select name="product_type" class="form-select" id="product_type" required>
                                        <option>เลือกหนึ่งรายการ</option>
                                        <option value="income">รายได้</option>
                                        <option value="discount">ส่วนลด</option>
                                        <option value="free">ฟรี</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label>ตัวเลือกนี้รวมผล PAX</label>
                                    <select name="product_pax" class="form-select" id="product_pax" required>
                                        <option value="Y">ใช่</option>
                                        <option value="N">ไม่ใช่</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="productForm" class="btn btn-primary float-end"> บันทึก</button>
                    </div>
                </div>
            </div>
        </div>


        {{-- Modela Edit Product --}}
        <div class="modal fade" id="productModal-edit" tabindex="-1" aria-labelledby="productModal-edit"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            แก้ไขรายการค่าบริการ
                        </h4>
                        <hr>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="productFormUpdate">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <input type="hidden" name="id" id="id">
                                    <label>ชื่อรายการสินค้า/บริการ</label>
                                    <input type="text" class="form-control" name="product_name" id="product_name_show"
                                        placeholder="ชื่อรายการสินค้า" required>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label>ราคาสินค้า/บริการ</label>
                                    <input type="number" class="form-control" name="product_price" placeholder="0.00"
                                        id="product_price_show" value="0" required>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label>ผู้กลุ่มมีสิทธิ์ใช้งาน</label>
                                    <select class="roles-edit form-control" name="product_roles" multiple="multiple"
                                        id="product_roles_show" required style="height: 36px; width: 100%">
                                        @forelse ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @empty
                                            <!-- Handle case where there are no roles -->
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label>ประเภทการคำนวณ</label>
                                    <select name="product_type" class="form-select" id="product_type_show" required>
                                        <option>เลือกหนึ่งรายการ</option>
                                        <option value="income">รายได้</option>
                                        <option value="discount">ส่วนลด</option>
                                        <option value="free">ฟรี</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <label>ตัวเลือกนี้รวมผล PAX</label>
                                    <select name="product_pax" class="form-select" id="product_pax_show" required>
                                        <option value="Y">ใช่</option>
                                        <option value="N">ไม่ใช่</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="productFormUpdate" class="btn btn-primary float-end">
                            อัพเดทข้อมูล</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            var canEdit = @json($canEdit);
            var canDelete = @json($canDelete);

            $(document).ready(function() {
                $(".roles").select2({
                    dropdownParent: $("#productModal"),
                });
                $(".roles-edit").select2({
                    dropdownParent: $("#productModal-edit"),
                });

                fetchProducts();

                function fetchProducts() {
                    $.ajax({
                        url: "{{ route('product.products') }}",
                        method: 'GET',
                        success: function(response) {
                            let rows = '';
                            var type = '';
                            $.each(response.products, function(index, product) {
                                if (product.product_type === 'income') {
                                    type = 'รายได้';
                                }
                                if (product.product_type === 'discount') {
                                    type = 'ส่วนลด';
                                }
                                if (product.product_type === 'free') {
                                    type = 'ฟรี';
                                }
                                let roleNames = response.roles.filter(role => product.product_roles
                                        .includes(role.id))
                                    .map(role => role.name)
                                    .join(', ');
                                let actionButtons = '';

                                if (canEdit) {
                                    actionButtons +=
                                        `<button class="btn btn-sm btn-info editProduct" data-id="${product.id}"><i class="fas fa-edit"></i> แก้ไขข้อมูล</button> &nbsp;`;
                                }

                                if (canDelete) {
                                    actionButtons +=
                                        `<button class="btn btn-sm btn-danger deleteProduct" data-id="${product.id}"><i class="fas fa-trash"></i> ลบข้อมูล</button>`;
                                }

                                rows += `
                                          <tr>
                                              <td>${index+1}</td>
                                              <td>${product.product_name}</td>
                                              <td>${product.product_price}</td>
                                              <td>${roleNames}</td>
                                              <td>${type}</td>
                                              <td>${(product.product_pax==='Y'? 'ใช่': 'ไม่ใช่')}</td>
                                              <td>
                                                  ${actionButtons}
                                              </td>
                                          </tr>
                                      `;
                            });
                            $('#productsTable').html(rows);

                            if ($.fn.DataTable.isDataTable('#products')) {
                                $('#products').DataTable().destroy();
                            }
                            $('#products').DataTable();
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ' + status + error);
                        }
                    });
                }


                // Add products
                $('#productForm').submit(function(e) {
                    e.preventDefault();
                    var product_name = $('#product_name').val();
                    var product_roles = $('#product_roles').val();
                    var product_price = $('#product_price').val();
                    var product_pax = $('#product_pax').val();
                    var product_type = $('#product_type').val();
                    var _token = '{{ csrf_token() }}';

                    let url = "{{ route('product.store') }}";
                    let method = 'POST';
                    $.ajax({
                        url: url,
                        method: method,
                        data: {
                            _token: _token,
                            product_name: product_name,
                            product_roles: product_roles, // ส่งค่าเป็น array
                            product_price: product_price,
                            product_pax: product_pax,
                            product_type: product_type // อย่าลืมส่งค่าของ product_type ด้วย
                        },
                        success: function(response) {
                            $('#productModal').modal('hide');
                            fetchProducts(); // Fetch updated product list
                        },
                        error: function(response) {
                            alert('Error: ' + response.responseJSON.errors);
                        }
                    });
                });

                //edit Products

                $(document).on('click', '.editProduct', function() {
                    let id = $(this).data('id');

                    $.ajax({
                        url: `product/edit/${id}`,
                        method: 'GET',
                        success: function(response) {

                            $('#productModalLabel').text('Edit Product');
                            $('#id').val(response.id);
                            $('#product_name_show').val(response.product_name);
                            $('#product_price_show').val(response.product_price);
                            $('#product_roles_show').val(response.product_roles);
                            $('#product_pax_show').val(response.product_pax);
                            $('#product_type_show').val(response.product_type);
                            // Set selected values in multiple select
                            let roles = response.product_roles.split(
                                ','); // Split the string into an array
                            $('#product_roles_show').val(roles).trigger('change');
                            $('#productModal-edit').modal('show');
                        }
                    });
                });

                //update
                $('#productFormUpdate').submit(function(e) {
                    e.preventDefault();
                    let id = $('#id').val();
                    var product_name = $('#product_name_show').val();
                    var product_roles = $('#product_roles_show').val();
                    var product_price = $('#product_price_show').val();
                    var product_pax = $('#product_pax_show').val();
                    var product_type = $('#product_type_show').val(); // ตรวจสอบค่าที่ได้รับ
                    var _token = '{{ csrf_token() }}';

                    let url = `product/update/${id}`;
                    let method = 'PUT';

                    $.ajax({
                        url: url,
                        method: method,
                        data: {
                            _token: _token,
                            product_name: product_name,
                            product_roles: product_roles, // ส่งค่าเป็น array
                            product_price: product_price,
                            product_pax: product_pax,
                            product_type: product_type // อย่าลืมส่งค่าของ product_type ด้วย
                        },
                        success: function(response) {
                            $('#productModal-edit').modal('hide');
                            fetchProducts(); // Fetch updated product list
                        },
                        error: function(response) {
                            alert('Error: ' + response.responseJSON.errors);
                        }
                    });
                    window.location.reload();
                });

                // Delete product
                $(document).on('click', '.deleteProduct', function() {
                    let id = $(this).data('id');

                    if (confirm('Are you sure you want to delete this product?')) {
                        $.ajax({
                            url: `product/delete/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                fetchProducts(); // Fetch updated product list
                                alert('Delete Product Successfully');
                            }
                        });
                    }
                });





            });
        </script>
    @endsection
