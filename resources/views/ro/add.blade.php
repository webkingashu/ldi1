@extends('layouts.app')
@section('title', 'Add Release Order')
@section('css')
<link rel="stylesheet" href="{!! asset('css/plugins/select2/select2.min.css') !!}" />
<link rel="stylesheet" href="{!! asset('css/plugins/iCheck/custom.css') !!}" />
@endsection
@section('content')
<div class="row">
    <div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-title">
        <h5>Release Order</h5>
        </div>                   
    <div class="ibox-content" style="margin-bottom: 50px;">
        <form method="get" class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label">Select the EAS Number</label>
                <div class="col-sm-4">
                    <select class="form-control" placeholder="Select the EAS Number">
                            <option disabled="disabled" selected="selected">Select the EAS Number</option>
                            <option>F.No. 11014/49/2015-Tech.(Vol-IV) M/s Wipro Limited</option>
                            <option>F.No. 11014/49/2015-Tech.(Vol-IV) M/s Wipro Limited</option>
                            <option>F.No. 11014/49/2015-Tech.(Vol-IV) M/s Wipro Limited</option>
                    </select>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
                <div class="form-group">
                        <h4 class="text-center padding-bottom-20">Depending upon the EAS Number, the Amount released till date</h4>
                        <div class="col-lg-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>File Name</th>
                                    <th>Amount in Rs.</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Tech/ADG(SA)/112/2016-17</td>
                                <td>3,98,78,440</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Tech/ADG(SA)/113/2016-17</td>
                                <td>2,12,78,998</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="text-right">Total</td>
                                <td>5,45,57,558</td>
                            </tr>
                            </tbody>
                        </table>
                       </div>
                    </div> 
            <div class="hr-line-dashed"></div>
                
                    <h4 class="text-center padding-bottom-20">Vendor Details</h4>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Vendor Name</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="M/s Wipro Limited" class="form-control">
                        </div>
                        <label class="col-lg-2 control-label">Vendor Contact Number</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="+91 9869801679" class="form-control">
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">Bank Name & Branch</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="HDFC Bank" class="form-control">
                        </div>
                        <label class="col-lg-2 control-label">IFSC Code</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="HDFC45DA65461049" class="form-control">
                        </div>
                        
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label">MICR No</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="HDFC Bank, New Delhi, NCR" class="form-control">
                        </div>
                        <label class="col-lg-2 control-label">Bank & Branch Code</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="HDFC45DA65461049" class="form-control">
                        </div>
                        
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Title of the Account</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="HDFC Bank, New Delhi, NCR" class="form-control">
                        </div>
                        <label class="col-lg-2 control-label">Current/ Cash Credit Account No.</label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="HDFC45DA65461049" class="form-control">
                        </div>
                        
                    </div>
                
                <div class="hr-line-dashed"></div>
                 <div class="form-group">
                    <label class="col-lg-2 control-label">Total Sanctioned amount</label>
                    <div class="col-lg-4">
                        <input type="text"  placeholder="Total Sanctioned amount" class="form-control">
                    </div>
                    <label class="col-lg-2 control-label">Release Order amount</label>
                    <div class="col-lg-4">
                        <input type="text"  placeholder="Release Order amount" class="form-control">
                    </div>
                </div>

                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">Budget Code </label>
                        <div class="col-lg-4">
                            <input type="text"  placeholder="Budget Code " class="form-control">
                        </div>
                    </div>
                    
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">                    
                     <label class="col-lg-2 control-label">Upload Annexures</label>
                        <div class="col-lg-10">
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2" style="margin-bottom: 20px;"><i class="fa fa-upload"></i>&nbsp;&nbsp;Upload File
                          </button>
                        </div>
                    </div>
                    
                    <div class="modal inmodal" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content animated flipInY">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                    <h4 class="modal-title">Annexture</h4>

                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text"  placeholder="Title" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control message-input" name="Vendor Details" maxlength="255" placeholder="Description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn btn-success btn-file"><i class="fa fa-upload"></i>&nbsp;&nbsp;<span class="fileinput-new">Upload file</span><span class="fileinput-exists">Change</span><input type="file" name="..."></span>
                                            <span class="fileinput-filename"></span>
                                            <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                        </div>
                                         <small class="text-muted">(Upload a file with .pdf, .doc, .docx, .csv, .xlsx , .jpg, .png , .txt format)</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <h4 class="text-center">List of Annexures </h4>
                        <div class="col-lg-6 col-md-offset-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>File Name</th>
                                    <th>View File</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>Invoice of Vendor</td>
                                <td><a href="#"><i class="fa fa-file-pdf-o"></i></a></td>
                                <td><a href="#" class="btn btn-white btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a></td>
                            </tr>
                             <tr>
                                <td>2</td>
                                <td>List of Items</td>
                                <td><a href="#"><i class="fa fa-file-pdf-o"></i></a></td>
                                <td><a href="#" class="btn btn-white btn-sm"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a></td>
                            </tr>
                            </tbody>
                        </table>
                       </div>
                    </div> 
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-lg-10 col-md-offset-3">
                           <label class="checkbox-inline"><input type="checkbox" value="option1" id="inlineCheckbox1">Copy to</label>
                        </div>
                    </div>
                   <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-lg-5 col-md-offset-3">
                          <select class="select2_demo_2 form-control">
                            <option></option>
                            <option value="Benin">Benin</option>
                          </select>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-lg-6 col-md-offset-3">
                            <div class="i-checks"><label><input type="checkbox" value=""> Mr. ABC XYZ (Designation)</label></div>
                            <div class="i-checks"><label><input type="checkbox" value=""> Mr. ABC XYZ (Designation)</label></div>
                            <div class="i-checks"><label><input type="checkbox" value=""> Mr. ABC XYZ (Designation)</label></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-5">
                            <button class="btn btn-primary" type="submit">Print</button>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>    
                </form>
              </div>
        </div>
    </div>    
</div>

@endsection
@section('scripts')
<script src="{!! asset('js/plugins/select2/select2.full.min.js') !!}" type="text/javascript"></script>
<script src="{!! asset('js/plugins/iCheck/icheck.min.js') !!}" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});
$(".select2_demo_2").select2({
            placeholder: "Select a Name",
            allowClear: true
            });
</script>       
@endsection            