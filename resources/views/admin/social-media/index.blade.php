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
                        <h2 class="title-1">Social Media</h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <button type="button" class="btn btn-success add-banner mb-3" data-toggle="modal" data-target="#modal-create-social-media">
                    <i class="fas fa-plus"></i> Add Social Media
                </button>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-social-media">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Social Media</th>
                                <th>Name</th>
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
@include('layouts.admin.modals.social-media.create')
@include('layouts.admin.modals.social-media.edit')
@endsection
@push('js')
<script type="text/javascript">
    $(function () {
        // datatable gallery villa
        var table_social_media = $('.table-social-media').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{route('social-media.index')}}",
                type: "get",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'item_social_media', name: 'item_social_media'},
                {data: 'name_social_media', name: 'name_social_media'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        //

        // event form submit add gallery
        $('form#create-social-media').on('submit',function(e) {
            e.preventDefault()

            let data = $(this).serialize()
            $.ajax({
                url: "{{ route('social-media.store') }}",
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $('#btn-submit-create-social-media').prop('disabled', true)
                },
                success: function (res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        table_social_media.ajax.reload()
                        $('form#create-social-media')[0].reset()
                        $('#btn-submit-create-social-media').prop('disabled', false)
                        $('#modal-create-social-media').modal('hide')
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-submit-create-social-media').prop('disabled', false)
                    }
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-submit-create-social-media').prop('disabled', false)
                }
            });
        })

        // event show edit gallery
        $(document).on('click','.edit-social-media',function() {
            let id = $(this).data('id')
            $.ajax({
                url : "{{route('social-media.show')}}",
                type: "post",
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#edit-social-media-id').val(res.data.id)
                        $('#edit_item_social_media').val(res.data.item_social_media)
                        $('#edit_name_social_media').val(res.data.name_social_media)
                        $('#modal-edit-social-media').modal('show')
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
         $('form#edit-social-media').on('submit',function(e) {
            e.preventDefault()

            let data =$(this).serialize();
            $.ajax({
                url: "{{ route('social-media.edit') }}",
                type: 'POST',
                data: data,
                beforeSend: function() {
                    $('#btn-edit-social-media').prop('disabled', true)
                },
                success: function (res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        table_social_media.ajax.reload()
                        $('form#edit-social-media')[0].reset()
                        $('#btn-edit-social-media').prop('disabled', false)
                        $('#modal-edit-social-media').modal('hide')
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message.photo}`,
                        })
                        $('#btn-edit-social-media').prop('disabled', false)
                    }
                },
                error: function(e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-edit-social-media').prop('disabled', false)
                }
            });
        })
        //

        // event delete gallery
        $(document).on('click','.delete-social-media', function() {
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
                        url: "{{route('social-media.destroy')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_social_media.ajax.reload()
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
