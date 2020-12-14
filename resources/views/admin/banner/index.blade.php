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
                        <h2 class="title-1">Banner</h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <button type="button" class="btn btn-success add-banner mb-3" data-toggle="modal" data-target="#modal-create-banner">
                    <i class="fas fa-plus"></i> Add Banner
                </button>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-banner">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Thumbnail</th>
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
@include('layouts.admin.modals.banners.create')
@include('layouts.admin.modals.banners.edit')
@endsection
@push('js')
<script type="text/javascript">
    $(function () {
        // datatable gallery villa
        var table_banner = $('.table-banner').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('banner.index')}}",
                type: "get",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'thumbnail', name: 'thumbnail', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        //

        // event form submit add gallery
        $('form#create-banner').on('submit',function(e) {
            e.preventDefault()

            let data = new FormData($("form#create-banner")[0]);
            $.ajax({
                url: "{{ route('banner.store') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btn-submit-create-banner').prop('disabled', true)
                },
                success: function (res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        table_banner.ajax.reload()
                        $('form#create-banner')[0].reset()
                        $('#btn-submit-create-banner').prop('disabled', false)
                        $('#modal-create-banner').modal('hide')
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message.photo}`,
                        })
                        $('#btn-submit-create-banner').prop('disabled', false)
                    }
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-submit-create-banner').prop('disabled', false)
                }
            });
        })

        // event show edit gallery
        $(document).on('click','.edit-banner',function() {
            let id = $(this).data('id')
            $.ajax({
                url : "{{route('banner.show')}}",
                type: "post",
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#edit-banner-id').val(res.data.id)
                        $('.form-group-edit-photo-video').html(`
                            <img src="/uploads/banner/photo/${res.data.url}" class="img-fluid" id="preview-image">
                            <label for="edit-photo">Photo File</label>
                            <input type="file" class="form-control-file" id="edit-photo" name="photo">
                        `)
                        $('#modal-edit-banner').modal('show')
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
         $('form#edit-banner').on('submit',function(e) {
            e.preventDefault()

            let data = new FormData($("form#edit-banner")[0]);
            $.ajax({
                url: "{{ route('banner.edit') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btn-edit-banner').prop('disabled', true)
                },
                success: function (res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        table_banner.ajax.reload()
                        $('form#edit-banner')[0].reset()
                        $('#btn-edit-banner').prop('disabled', false)
                        $('#modal-edit-banner').modal('hide')
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message.photo}`,
                        })
                        $('#btn-edit-banner').prop('disabled', false)
                    }
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-edit-banner').prop('disabled', false)
                }
            });
        })
        //

        // event delete gallery
        $(document).on('click','.delete-banner', function() {
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
                        url: "{{route('banner.destroy')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_banner.ajax.reload()
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

        // function for reader preview image before upload
        function readAddURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('.preview-image').html(`
                        <img src="${e.target.result}" class="img-fluid">
                    `)
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }
        //

        // event change file photo add
        $(document).on('change','#photo', function() {
            readAddURL(this)
        })
        // event after change file photo edit
        $(document).on('change','#edit-photo', function() {
           readURL(this)
        })
        //
     });
  </script>
@endpush
