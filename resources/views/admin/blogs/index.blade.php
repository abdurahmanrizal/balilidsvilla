@extends('layouts/admin/app')
@section('content')
<style>
    .btn-outline-warning:hover {
        color: white !important;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1">Blog Post</h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <button type="button" class="btn btn-success add-villa mb-3" data-toggle="modal" data-target="#modal-create-blog-post">
                    <i class="fas fa-plus"></i> Add Blog Post
                </button>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-blog-post">
                        <thead>
                            <tr>
                                <th>
                                  No
                                </th>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Author</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- END DATA TABLE -->
            </div>
        </div>
    </div>
</div>
@include('layouts.admin.modals.blog_posts.create')
@include('layouts.admin.modals.blog_posts.edit')
@endsection
@push('js')
<script type="text/javascript">
    $(function () {
        // datatable villa
        var table_blog_post = $('.table-blog-post').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('blog.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'thumbnail',name:'thumbnail',orderable:false, searchable: false},
                {data: 'title',name:'title'},
                {data: 'description',name:'description'},
                {data: 'author',name:'author'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        //

        // event submit form add villa
        $('form#create-blog-post').on('submit', function(e) {
            e.preventDefault()
            let data = new FormData($("form#create-blog-post")[0]);
            $.ajax({
                url : "{{route('blog.store')}}",
                type: "post",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btn-submit-create-blog').prop('disabled', true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-create-blog-post').modal('hide')
                        $('#btn-submit-create-blog').prop('disabled', false)
                        $('form#create-blog-post')[0].reset()
                        table_blog_post.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-submit-create-blog').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-submit-create-blog').prop('disabled', false)
                }
            })
        })
        //

        // event show edit villa
        $(document).on('click', '.edit-blog-post' ,function() {
            let id = $(this).data('id')
            $.ajax({
                url: "{{route('blog.show')}}",
                type: 'post',
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#edit-blog-id').val(res.data.id)
                        $('#edit-title').val(res.data.title)
                        tinymce.get('edit-content').setContent(`${res.data.description}`)
                        if(res.data.url === '-') {
                            $('.form-group-edit-photo-video').html(`
                                <label for="edit-photo">Photo File</label>
                                <input type="file" class="form-control-file" id="edit-photo" name="photo">
                            `)
                        }else {
                            $('.form-group-edit-photo-video').html(`
                                <img src="/uploads/blog-post/photo/${res.data.url}" class="img-fluid" id="preview-image">
                                <label for="edit-photo">Photo File</label>
                                <input type="file" class="form-control-file" id="edit-photo" name="photo">
                            `)
                        }

                        $('#modal-edit-blog-post').modal('show')
                    }
                }
            })
        })
        //

        // event submit edit villa
        $('form#edit-blog-post').on('submit', function(e) {
            e.preventDefault()
            let data = new FormData($("form#edit-blog-post")[0]);
            $.ajax({
                url  : "{{route('blog.update')}}",
                type : "post",
                data : data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btn-edit-blog-post').prop('disabled',true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-edit-blog-post').modal('hide')
                        $('#btn-edit-blog-post').prop('disabled', false)
                        $('form#edit-blog-post')[0].reset()
                        table_blog_post.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-edit-blog-post').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-edit-blog-post').prop('disabled', false)
                }
            })
        })

        // event destory villa item
        $(document).on('click','.delete-blog-post', function() {
            let id = $(this).data('id')
            Swal.fire({
                title: 'Are you sure?',
                text: "You will delete this item",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('blog.destroy')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_blog_post.ajax.reload()
                            }else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                                })
                            }
                        },
                        error: function(err) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                            })
                        }
                    })
                }else {
                    return false
                }
            })
        })
     });

     // function for reader preview image before upload
     function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview-image').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }
        //

    // event after change file photo
    $(document).on('change','#edit-photo', function() {
        readURL(this)
    })
    //
  </script>
  <script>tinymce.init({selector:'textarea'});</script>
@endpush
