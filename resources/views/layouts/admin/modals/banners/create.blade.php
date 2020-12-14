
<div class="modal fade" id="modal-create-banner" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Banner</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="create-banner">
              @csrf
              <div class="preview-image"></div>
              <div class="form-group form-group-photo-video">
                <label for="photo">Photo File</label>
                <input type="file" class="form-control-file" id="photo" name="photo">
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-submit-create-banner">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
