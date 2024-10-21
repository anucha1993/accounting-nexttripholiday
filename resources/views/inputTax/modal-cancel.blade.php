
   <div class="modal-body">
               <div class="header">
                   <h5>ยกเลิกต้นทุน</h5>
               </div>
               <form action="{{route('inputtax.updateCancel',$inputTaxModel->input_tax_id)}}" method="POST" enctype="multipart/form-data">
                   @csrf
                   @method('PUT')
                   <div class="row">
                       <div class="col-md-12">
                           <label for="">ระบุเหตุผล <span class="text-danger"> *</span></label>
                           <textarea name="input_tax_cancel" id="" cols="30" rows="3" class="form-control" required></textarea>
                       </div>
                   </div>
                   <br>
                  
           
                   <br>
                   <button type="submit" class="btn btn-success">ยืนยัน</button>
               </form>
           </div>