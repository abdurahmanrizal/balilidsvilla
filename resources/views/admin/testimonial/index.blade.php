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
                        <h2 class="title-1">Testimonial</h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <button type="button" class="btn btn-success add-villa mb-3" data-toggle="modal" data-target="#modal-create-testimonial">
                    <i class="fas fa-plus"></i> Add Testimoni
                </button>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-testimonial">
                        <thead>
                            <tr>
                                <th>
                                  No
                                </th>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Testimonial</th>
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
@include('layouts.admin.modals.testimonials.create')
@include('layouts.admin.modals.testimonials.edit')
@endsection
@push('js')
<script type="text/javascript">
    $(function () {
        // datatable villa
        var table_testimonial = $('.table-testimonial').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('testimonial.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'photo',name:'photo'},
                {data: 'name', name: 'name'},
                {data: 'position', name: 'position'},
                {data: 'testimonial', name: 'testimonial'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        //

        // event submit form add testimonial
        $('form#testimonial-create').on('submit', function(e) {
            e.preventDefault()
            let data = new FormData($("form#testimonial-create")[0]);
            $.ajax({
                url : "{{route('testimonial.store')}}",
                type: "post",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btn-submit-testimonial').prop('disabled', true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-create-testimonial').modal('hide')
                        $('#btn-submit-testimonial').prop('disabled', false)
                        $('form#testimonial-create')[0].reset()
                        table_testimonial.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-submit-testimonial').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-submit-testimonial').prop('disabled', false)
                }
            })
        })
        //

        // event show edit detail villa
        $(document).on('click', '.edit-testimoni' ,function() {
            let id = $(this).data('id')
            $.ajax({
                url: "{{route('testimonial.show')}}",
                type: 'post',
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#edit-id').val(res.data.id)
                        $('#edit-name').val(res.data.name)
                        $('#edit-position').val(res.data.position)
                        $('#edit-testimonial').text(res.data.testimonial)
                        $('.form-group-edit-photo').html(`
                                <img src="/uploads/testimonial/photo/${res.data.url}" class="img-fluid" id="preview-image">
                                <label for="edit-photo">Photo File</label>
                                <input type="file" class="form-control-file" id="edit-photo" name="photo">
                            `)
                        $('#modal-testimonial-edit').modal('show')
                    }
                }
            })
        })
        //


        // event submit edit villa
        $('form#testimonial-edit').on('submit', function(e) {
            e.preventDefault()
            let data = new FormData($("form#testimonial-edit")[0]);
            $.ajax({
                url  : "{{route('testimonial.edit')}}",
                type : "post",
                data : data,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#btn-testimonial-edit').prop('disabled',true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-testimonial-edit').modal('hide')
                        $('#btn-testimonial-edit').prop('disabled', false)
                        $('form#testimonial-edit')[0].reset()
                        table_testimonial.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-testimonial-edit').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-testimonial-edit').prop('disabled', false)
                }
            })
        })

        // event destory villa item
        $(document).on('click','.delete-testimoni', function() {
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
                        url: "{{route('testimonial.destroy')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_testimonial.ajax.reload()
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

        // event priority
        $(document).on('click', '.priority-testimoni', function() {
            let id = $(this).data('id')
            Swal.fire({
                title: 'Are you sure?',
                text: "You will priority this item",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('testimonial.priority')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_testimonial.ajax.reload()
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

        // event unpriority
        $(document).on('click', '.unpriority-testimoni', function() {
            let id = $(this).data('id')
            Swal.fire({
                title: 'Are you sure?',
                text: "You will unpriority this item",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{route('testimonial.unpriority')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_testimonial.ajax.reload()
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
