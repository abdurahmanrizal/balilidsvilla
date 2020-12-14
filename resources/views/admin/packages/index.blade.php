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
                        <h2 class="title-1">Packages</h2>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <button type="button" class="btn btn-success add-villa mb-3" data-toggle="modal" data-target="#modal-create-package">
                    <i class="fas fa-plus"></i> Add Package
                </button>
                <div class="table-responsive table-responsive-data2">
                    <table class="table table-package">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Attribute Package</th>
                                <th>Price</th>
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
@include('layouts.admin.modals.packages.create')
@include('layouts.admin.modals.packages.edit')
@endsection
@push('js')
<script type="text/javascript">
    $(function () {
        // datatable villa
        var table_package = $('.table-package').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('package.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'attribute_package', name: 'attribute_package'},
                {data: 'price', name: 'price'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        //

        // select2 package
        $('#attribute-package').select2()

        $("select").on("select2:select", function(evt) {
            var element = evt.params.data.element;
            var $element = $(element);
            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });
        //

        // event submit form add villa
        $('form#create-package').on('submit', function(e) {
            e.preventDefault()
            let data = $(this).serialize()
            $.ajax({
                url : "{{route('package.store')}}",
                type: "post",
                data: data,
                beforeSend: function() {
                    $('#btn-submit-package').prop('disabled', true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-create-package').modal('hide')
                        $('#btn-submit-package').prop('disabled', false)
                        $('form#create-package')[0].reset()
                        $('#attribute-package').select2({
                            allowClear: true
                        })
                        table_package.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-submit-package').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-submit-package').prop('disabled', false)
                }
            })
        })
        //

        // event show edit villa
        $(document).on('click', '.edit-package' ,function() {
            let id = $(this).data('id')
            $.ajax({
                url: "{{route('package.show')}}",
                type: 'post',
                data: {id:id, _token: "{{csrf_token()}}"},
                success: function(res) {
                    if(res.code === 200) {
                        $('#edit-name').val(res.data.name)
                        $('#edit-id').val(res.data.id)
                        tinymce.get('edit-description').setContent(`${res.data.description}`)
                        $('#edit-price').val(`${res.data.price}`)
                        $('#edit-location').val(`${res.data.location}`)
                        $('#edit-attribute-package').select2().val(res.item_resorts).trigger('change')
                        $('#modal-edit-package').modal('show')
                    }
                }
            })
        })
        //

        // event submit edit villa
        $('form#edit-package').on('submit', function(e) {
            e.preventDefault()
            let data = $(this).serialize()
            $.ajax({
                url  : "{{route('package.update')}}",
                type : "post",
                data : data,
                beforeSend: function() {
                    $('#btn-edit-package').prop('disabled',true)
                },
                success: function(res) {
                    if(res.code === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: `${res.message}`,
                        })
                        $('#modal-edit-package').modal('hide')
                        $('#btn-edit-package').prop('disabled', false)
                        $('form#edit-package')[0].reset()
                        table_package.ajax.reload()
                    }else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: `Something went wrong with our system with status code ${res.code} and message text ${res.message}`,
                        })
                        $('#btn-edit-package').prop('disabled', false)
                    }
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: `Something went wrong with our system with status code ${err.status} and message text ${err.statusText}`,
                    })
                    $('#btn-edit-package').prop('disabled', false)
                }
            })
        })
        //

        // event destory villa item
        $(document).on('click','.delete-package', function() {
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
                        url: "{{route('package.destroy')}}",
                        type: "post",
                        data: {id:id, _token: "{{csrf_token()}}"},
                        success: function(res) {
                            if(res.code === 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: `${res.message}`,
                                })
                                table_package.ajax.reload()
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
        $(document).on('click','.gallery-package', function() {
            let id = $(this).data('id')
            $.redirect("{{route('package.gallery.index')}}", {id: id}, "get", "_blank");
        })
        //

     });
  </script>
  <script>tinymce.init({selector:'textarea'});</script>
@endpush
