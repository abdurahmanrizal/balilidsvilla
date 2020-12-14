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
                        <h2 class="title-1">Description</h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                @if ($count_data <= 0)
                <button type="button" class="btn btn-success add-villa mb-3" data-toggle="modal" data-target="#modal-create-description">
                    <i class="fas fa-plus"></i> Add Description
                </button>
                @endif
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-description">
                        <thead>
                            <tr>
                                <th>
                                  No
                                </th>
                                <th>Description</th>
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
@include('layouts.admin.modals.descriptions.create')
@include('layouts.admin.modals.descriptions.edit')
@endsection
@push('js')
<script type="text/javascript">
    $(function () {
        // datatable description
        var table_description = $('.table-description').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('description.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'description', name: 'description'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        //

        // event submit form add villa
        $('form#create-description').on('submit', function(e) {
            e.preventDefault()
            let data = $(this).serialize();
            $.ajax({
                url : "{{route('description.store')}}",
                type: "post",
                data: data,
                beforeSend: function() {
                    $('#btn-submit-description').prop('disabled', true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-create-description').modal('hide')
                        $('#btn-submit-description').prop('disabled', false)
                        $('form#create-description')[0].reset()
                       location.reload(true)
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-submit-description').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-submit-description').prop('disabled', false)
                }
            })
        })
        //

        // event show edit villa
        $(document).on('click', '.edit-description' ,function() {
            let id = $(this).data('id')
            $.ajax({
                url: "{{route('description.show')}}",
                type: 'post',
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#edit-description-id').val(res.data.id)
                        tinymce.get('edit-content').setContent(`${res.data.description_company}`)
                        $('#modal-edit-description').modal('show')
                    }
                }
            })
        })
        //

        // event submit edit description
        $('form#edit-description').on('submit', function(e) {
            e.preventDefault()
            let data = $(this).serialize();
            $.ajax({
                url  : "{{route('description.edit')}}",
                type : "post",
                data : data,
                beforeSend: function() {
                    $('#btn-edit-description').prop('disabled',true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-edit-description').modal('hide')
                        $('#btn-edit-description').prop('disabled', false)
                        $('form#edit-description')[0].reset()
                        table_description.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-edit-description').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-edit-description').prop('disabled', false)
                }
            })
        })

        // event destory villa item
        $(document).on('click','.delete-description', function() {
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
                        url: "{{route('description.destroy')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                               location.reload(true)
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
  </script>
  <script>tinymce.init({selector:'textarea'});</script>
@endpush
