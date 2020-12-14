
<div class="modal fade" id="modal-edit-gallery-package" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-villa">Edit Gallery Package</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="edit-gallery-package">
              @csrf
              <input type="hidden" name="id" id="edit-gallery-id">
              <div class="form-group">
                <label for="edit-name-package">Package Name</label>
                <select class="form-control" id="edit-name-package" name="name_package" readonly>
                   <option value="{{$data_package->id}}">{{$data_package->name}}</option>
                </select>
              </div>
              <div class="form-group">
                <label for="edit-type">Type</label>
                <select class="form-control" id="edit-type" name="type" readonly>
                    <option value="photo">Photo</option>
                    <option value="video">Video</option>
                </select>
              </div>
              <div class="form-group form-group-edit-photo-video">
                <label for="edit-photo">Photo File</label>
                <input type="file" class="form-control-file" id="edit-photo" name="photo">
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-edit-gallery-package">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
