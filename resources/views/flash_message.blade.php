@if ($message = Session::get('success'))
<div class="alert alert-success alert-block m-3 flash-alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>    
    <strong>{{ $message }}</strong>
</div>
@endif
  
@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block m-3 flash-alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>    
    <strong>{{ $message }}</strong>
</div>
@endif
   
@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>    
    <strong>{{ $message }}</strong>
</div>
@endif
   
@if ($message = Session::get('info'))
<div class="alert alert-info alert-block m-3 flash-alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>    
    <strong>{{ $message }}</strong>
</div>
@endif
  
@if ($errors->any())
<div class="alert alert-danger m-3 flash-alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>    
    Please check the form below for errors
</div>
@endif