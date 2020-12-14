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
                        <h2 class="title-1">Gallery</h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <button type="button" class="btn btn-success add-villa mb-3" data-toggle="modal" data-target="#modal-create-gallery">
                    <i class="fas fa-plus"></i> Add Gallery
                </button>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-gallery">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Thumbnail</th>
                                <th>Type</th>
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
@include('layouts.admin.modals.galleries.create')
@include('layouts.admin.modals.galleries.edit')
@endsection
@push('js')
<script type="text/javascript">
    $(function () {
        // datatable gallery villa
        var table_gallery = $('.table-gallery').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('gallery.index')}}",
                type: "get",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false},
                {data: 'type', name: 'type'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        //

        // event change photo file or video URL
        $('#type').on('change', function(){
            let str = $(this).val()
            if(str === 'photo') {
                $('.form-group-photo-video').html(`
                    <label for="photo">Photo File</label>
                    <input type="file" class="form-control-file" id="photo" name="photo">
                `)
            }else {
                $('.form-group-photo-video').html(`
                    <label for="video">Video URL</label>
                    <input type="text" class="form-control" id="video" name="video" placeholder="Type Link/URL video">
                `)
            }
        })
        //

        // event form submit add gallery
        $('form#gallery-create').on('submit',function(e) {
            e.preventDefault()
            let photo = $('#photo').val()
            if(photo == undefined) {
                let data = $(this).serialize()
                $.ajax({
                    url: "{{route('gallery.store')}}",
                    type: "post",
                    data: data,
                    beforeSend: function() {
                        $('#btn-submit-gallery').prop('disabled', true)
                    },
                    success: function(res) {
                        if(res.code === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: `${res.message}`,
                            })
                            table_gallery.ajax.reload()
                            $('form#gallery-create')[0].reset()
                            $('#modal-create-gallery').modal('hide')
                        }else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: `Something went wrong with our system with status code ${res.code} and message text ${res.message.photo}`,
                            })
                            $('#btn-submit-gallery').prop('disabled', false)
                        }
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                        })
                        $('#btn-submit-gallery').prop('disabled', false)
                    }
                })
            }else {
                let data = new FormData($("form#gallery-create")[0]);
                $.ajax({
                    url: "{{ route('gallery.store') }}",
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#btn-submit-gallery').prop('disabled', true)
                    },
                    success: function (res) {
                        if(res.code === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: `${res.message}`,
                            })
                            table_gallery.ajax.reload()
                            $('form#gallery-create')[0].reset()
                            $('.form-group-photo-video').html(`
                                <label for="photo">Photo File</label>
                                <input type="file" class="form-control-file" id="photo" name="photo">
                            `)
                            $('#btn-submit-gallery').prop('disabled', false)
                            $('#modal-create-gallery').modal('hide')
                        }else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: `Something went wrong with our system with status code ${res.code} and message text ${res.message.photo}`,
                            })
                            $('#btn-submit-gallery').prop('disabled', false)
                        }
                    },
                    error: function(e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                        })
                        $('#btn-submit-gallery').prop('disabled', false)
                    }
                });
            }
        })

        // event show edit gallery
        $(document).on('click','.edit-gallery',function() {
            let id = $(this).data('id')
            $.ajax({
                url : "{{route('gallery.show')}}",
                type: "post",
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#edit-gallery-id').val(res.data.id)
                        $('#edit-type').val(res.data.type)
                        if(res.data.type === 'photo') {
                            $('.form-group-edit-photo-video').html(`
                                <img src="/uploads/gallery/photo/${res.data.url}" class="img-fluid" id="preview-image">
                                <label for="edit-photo">Photo File</label>
                                <input type="file" class="form-control-file" id="edit-photo" name="photo">
                            `)
                        }else {
                            $('.form-group-edit-photo-video').html(`
                                <label for="edit-photo">Video URL</label>
                                <input type="text" class="form-control" id="edit-video" name="video" value="${res.data.url}">
                            `)
                        }
                        $('#modal-edit-gallery').modal('show')
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
            });
        })

         // event update gallery
         $('form#edit-gallery').on('submit',function(e) {
            e.preventDefault()
            let photo = $('#edit-photo').val()
            if(photo == undefined) {
                let data = $(this).serialize()
                $.ajax({
                    url: "{{route('gallery.edit')}}",
                    type: "post",
                    data: data,
                    beforeSend: function() {
                        $('#btn-edit-gallery').prop('disabled', true)
                    },
                    success: function(res) {
                        if(res.code === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: `${res.message}`,
                            })
                            table_gallery.ajax.reload()
                            $('form#edit-gallery')[0].reset()
                            $('#modal-edit-gallery').modal('hide')
                        }else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: `Something went wrong with our system with status code ${res.code} and message text ${res.message.photo}`,
                            })
                            $('#btn-edit-gallery').prop('disabled', false)
                        }
                    },
                    error: function(err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                        })
                        $('#btn-edit-gallery').prop('disabled', false)
                    }
                })
            }else {
                let data = new FormData($("form#edit-gallery")[0]);
                $.ajax({
                    url: "{{ route('gallery.edit') }}",
                    type: 'POST',
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#btn-edit-gallery').prop('disabled', true)
                    },
                    success: function (res) {
                        if(res.code === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: `${res.message}`,
                            })
                            table_gallery.ajax.reload()
                            $('form#edit-gallery')[0].reset()
                            $('.form-group-edit-photo-video').html(`
                                <label for="edit-photo">Photo File</label>
                                <input type="file" class="form-control-file" id="edit-photo" name="photo">
                            `)
                            $('#btn-edit-gallery').prop('disabled', false)
                            $('#modal-edit-gallery').modal('hide')
                        }else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: `Something went wrong with our system with status code ${res.code} and message text ${res.message.photo}`,
                            })
                            $('#btn-edit-gallery').prop('disabled', false)
                        }
                    },
                    error: function(e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                        })
                        $('#btn-edit-gallery').prop('disabled', false)
                    }
                });
            }
        })
        //

        // event delete gallery
        $(document).on('click','.delete-gallery', function() {
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
                        url: "{{route('gallery.destroy')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_gallery.ajax.reload()
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
     });
  </script>
@endpush
