@extends('layouts.app')

@section('title')
Add New Post
@endsection

@section('content')

<!-- tiny mce -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
  tinymce.init({
    selector: "textarea",
    plugins: ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
  });
</script>
<!-- tiny mce -->

<form action="/new-post" method="post">

  <!-- csrf_token() is for cross-site security. -->
  <input type="hidden" name="_token" value="{{ csrf_token() }}">

  <div class="form-group">
    <input required="required" value="{{ old('title') }}" placeholder="Enter title here" type="text" name="title" class="form-control" />
  </div>

  <div class="form-group">
    <textarea name='body' class="form-control">{{ old('body') }}</textarea>
  </div>

  <input type="submit" name='publish' class="btn btn-success" value="Publish" />
  <input type="submit" name='save' class="btn btn-default" value="Save Draft" />

</form>

@endsection