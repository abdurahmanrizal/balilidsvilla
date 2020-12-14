
<div class="modal fade" id="modal-edit-banner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-villa">Edit Banner</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="edit-banner">
              @csrf
              <input type="hidden" name="id" id="edit-banner-id">

              <div class="form-group form-group-edit-photo-video">
                <label for="edit-photo">Photo File</label>
                <input type="file" class="form-control-file" id="edit-photo" name="photo">
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-edit-banner">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
