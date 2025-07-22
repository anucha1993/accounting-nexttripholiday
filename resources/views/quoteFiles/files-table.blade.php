<div class="col-md-12">
    <div class="info-card">
        
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>
                ไฟล์หนังสือเดินทาง / Passport Photo 
                &nbsp; <a href="javascript:void(0)" class="text-muted float-end" onclick="toggleAccordion('table-files', 'toggle-arrow-files')">
                    <span class="fas fa-chevron-down" id="toggle-arrow-files"></span>
                </a>

               
            </h5>
            
        </div>
        
        <div class="card-body" id="table-files" style="display: block">
            <br>
                <button class="btn btn-danger btn-sm  mb-3" data-bs-toggle="modal" data-bs-target="#bs-example-modal-xlg">
                              <i class="fa fa-file me-1"></i>เพิ่มไฟล์เอกสาร
                            </button>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table">
                        <tr>
                            <th width="8%">ลำดับ</th>
                            <th width="25%">ตำแหน่งไฟล์</th>
                            <th width="20%">วันที่</th>
                            <th width="25%">ไฟล์</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quoteFiles as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $item->quote_file_path }}
                                </td>
                                <td>{{date('d-m-Y H:m:s',strtotime($item->created_at))}}</td>
                                <td>
                                    <a  onclick="openPdfPopup(this.href); return false;"
                                        href="{{ asset($item->quote_file_path) }}">{{ $item->quote_file_name }}</a>
                                </td>
                                <td>
                                    {{-- <a href="" class="btn btn-outline-primary btn-sm me-1"><i class="fa fa-edit me-1"></i>แก้ไข</a> --}}
                                    <a class="btn btn-outline-info btn-sm me-1 modal-mail-file" href="{{route('quotefile.modalMail',$item->quote_file_id)}}"><i class="fas fa-envelope me-1"></i>ส่งเมล</a>
                                    <a href="{{ route('quotefile.delete', $item->quote_file_id) }}" onclick="return confirm('ยืนยันการลบ');" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash me-1"></i>Delete</a>
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


  <!-- sample modal content -->
  <div class="modal fade" id="bs-example-modal-xlg" tabindex="-1" aria-labelledby="bs-example-modal-lg"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
      <div class="modal-content">
          <div class="modal-header d-flex align-items-center">
              <h4 class="modal-title" id="myLargeModalLabel">
                  แนบเอกสาร
              </h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
             <form action="{{route('quotefile.upload')}}" enctype="multipart/form-data" method="post" id="form-upload">
              @csrf
              @method('POST')
              <input type="hidden" name="quote_number" value="{{$quotationModel->quote_number}}">
              <div class="row">
                  <div class="col-md-3">
                      <input type="file" name="file" required>
                  </div>
                  <div class="col-md-3">
                      <input type="text" class="form-control" name="file_name" placeholder="ชื่อไฟล์เอกสาร" required>
                  </div>
              </div>
             </form>
          </div>
          <div class="modal-footer">
             <button class="btn btn-success" form="form-upload" type="submit"> <i class="fa fa-save"></i> Upload</button>
          </div>
      </div>
      <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

{{-- create form debit --}}
<div class="modal fade bd-example-modal-sm modal-xl" id="modal-mail-file" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            ...
        </div>
    </div>
</div>


<script>
    $(document).ready( function (){
          // modal add payment invoice
          $(".modal-mail-file").click("click", function(e) {
            e.preventDefault();
            $("#modal-mail-file")
                .modal("show")
                .addClass("modal-lg")
                .find(".modal-content")
                .load($(this).attr("href"));
        });
    })
</script>
