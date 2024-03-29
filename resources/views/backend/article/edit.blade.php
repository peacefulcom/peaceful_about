@extends('backend.layouts.adminlte')
@section('content')
<section class="content">
<!-- container-fluid start -->
    <div class="container-fluid">

<!-- col-md-9 start -->
<div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item">Create</li>
                  
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">


<!-- form start -->
        <form method="POST" action="" accept-charset="UTF-8" class="form-horizontal col-sm-10" autocomplete="off" id="form1" enctype="multipart/form-data">
        @csrf

         <div class="col-sm-8 offset-sm-2 col-xs-12"> 
            <div class="form-group row">
              <label for="title" class="col-sm-3 col-xs-12 required">title</label>
              <input class="col-sm-9 col-xs-12 form-control" id="title" name="title" type="text" value="{{ $article->title }}">
            </div>
            @if ($category)
            <div class="form-group row">
              <label for="cid" class="col-sm-3 col-xs-12 required">category</label>
              <select class="col-sm-9 col-xs-12 form-control" id="cid" name="cid">
                @foreach ($category as $k => $v)
                  <option value="{{ $k }}" @if($article->cid == $k) selected @endif >{{ $v }}</option>
                @endforeach
              </select>
            </div>
            @endif
            <div class="form-group row">
              <label for="description" class="col-sm-3 col-xs-12 required">description</label>
              <textarea class="col-sm-9 col-xs-12 form-control" id="description" name="description" rows="4" cols="50">{{ $article->description }}</textarea>
            </div>
            <div class="form-group row">
              <label for="image" class="col-sm-3 col-xs-12 required">image</label>
              <img class="img-fluid" src="{{ $article->img }}" alt="Photo">
              <input class="col-sm-9 col-xs-12" id="image" name="image" type="file">
            </div>
            <div class="form-group row">
              <label for="content" class="col-sm-3 col-xs-12 required">content</label>
              <textarea class="col-sm-9 col-xs-12 form-control" id="content" name="content" rows="4" cols="50">{{ $article->content }}</textarea>
            </div>
            <div class="form-group row">
              <label for="is_publish" class="col-sm-3 col-xs-12 required">publish</label>
              <input type="checkbox" name="is_publish" data-bootstrap-switch data-off-color="danger" data-on-color="success" 
              @if($article->is_publish == 1) checked @endif 
              >
            </div>
            <div class="form-group row">
              <label for="sort" class="col-sm-3 col-xs-12 required">sort</label>
              <input class="col-sm-3 col-xs-3  form-control" id="sort" name="sort" type="text" value="{{ $article->sort }}">
            </div>
            
            <div class="form-group row">
              <div class="col-sm-12 text-center">
                <input name="submit" class="btn btn-info pull-center text-center" id="sbmtbtn" type="submit" value="Submit">
              </div>
            </div>    
        </div>    
        </form>
<!-- form end -->
            </div>
        </div>
    </div>

    </div>
</div>


<!-- col-md-9 end -->
    
    <div class="col-sm-12">
          @if ($errors->any())
            <div class="alert alert-danger text-center">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
    </div>
<!-- warningModal start -->
    <div class="modal fade" id="warningModal" tabindex="-1">
     <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-danger">Warning</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body text-danger" id='warningText'>
            
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div> 
    
  </div> 
<!-- warningModal end -->
  </div>
<!-- container-fluid end -->
</section>
@stop
@section('script')
<!-- Bootstrap Switch -->
<script src="/adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>
 $(function () {
    $("input[data-bootstrap-switch]").each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    })
  })

</script>
<script src="/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
  CKEDITOR.replace('content', {
        filebrowserUploadUrl: "{{route('image-upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
  });
</script>
@stop
