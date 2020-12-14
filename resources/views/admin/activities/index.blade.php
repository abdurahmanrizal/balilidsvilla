@extends('layouts.admin.app')
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
                        <h2 class="title-1">Activities</h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <button type="button" class="btn btn-success add-villa mb-3" data-toggle="modal" data-target="#modal-create-activity">
                    <i class="fas fa-plus"></i> Add Activity
                </button>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-activity">
                        <thead>
                            <tr>
                                <th>
                                  No
                                </th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Location</th>
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
@include('layouts.admin.modals.activities.create')
@include('layouts.admin.modals.activities.show')
@include('layouts.admin.modals.activities.edit')
@endsection
@push('js')
<script type="text/javascript">
    $(function () {
        // datatable villa
        var table_activity = $('.table-activity').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('activity.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'description', name: 'description'},
                {data: 'price', name: 'price'},
                {data: 'location', name: 'location'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        //

        // event submit form add villa
        $('form#create-activity').on('submit', function(e) {
            e.preventDefault()
            let data = $(this).serialize()
            $.ajax({
                url : "{{route('activity.store')}}",
                type: "post",
                data: data,
                beforeSend: function() {
                    $('#btn-submit-activity').prop('disabled', true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-create-activity').modal('hide')
                        $('#btn-submit-activity').prop('disabled', false)
                        $('form#create-activity')[0].reset()
                        table_activity.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-submit-activity').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-submit-activity').prop('disabled', false)
                }
            })
        })
        //

        // event show detail villa
        $(document).on('click', '.show-activity' ,function() {
            let id = $(this).data('id')
            $.ajax({
                url: "{{route('activity.show')}}",
                type: 'post',
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#title-activity').html(`${res.data.name}`)
                        $('#show-name').val(res.data.name)
                        tinymce.get('show-description').setContent(`${res.data.description}`)
                        tinymce.get('show-description').mode.set("readonly");
                        $('#show-price').val(`${res.data.price}`)
                        $('#show-location').val(`${res.data.location}`)
                        $('#modal-show-activity').modal('show')
                    }
                }
            })
        })
        //

        // event show edit villa
        $(document).on('click', '.edit-activity' ,function() {
            let id = $(this).data('id')
            $.ajax({
                url: "{{route('activity.show')}}",
                type: 'post',
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#edit-name').val(res.data.name)
                        $('#edit-id').val(res.data.id)
                        tinymce.get('edit-description').setContent(`${res.data.description}`)
                        $('#edit-price').val(`${res.data.price}`)
                        $('#edit-location').val(`${res.data.location}`)
                        $('#modal-edit-activity').modal('show')
                    }
                }
            })
        })
        //

        // event submit edit villa
        $('form#edit-activity').on('submit', function(e) {
            e.preventDefault()
            let data = $(this).serialize()
            $.ajax({
                url  : "{{route('activity.update')}}",
                type : "post",
                data : data,
                beforeSend: function() {
                    $('#btn-edit-activity').prop('disabled',true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-edit-activity').modal('hide')
                        $('#btn-edit-activity').prop('disabled', false)
                        $('form#edit-activity')[0].reset()
                        table_activity.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-edit-activity').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-edit-activity').prop('disabled', false)
                }
            })
        })

        // event destory villa item
        $(document).on('click','.delete-activity', function() {
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
                        url: "{{route('activity.destroy')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_activity.ajax.reload()
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

        // event click gallery villa
        $(document).on('click','.gallery-activity', function() {
            let id = $(this).data('id')
            $.redirect("{{route('activity.gallery.index')}}", {id: id}, "get", "_blank");
        })
        //

     });
  </script>
  <script>tinymce.init({selector:'textarea'});</script>
@endpush
