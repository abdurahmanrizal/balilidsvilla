
<div class="modal fade" id="modal-create-gallery" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Gallery</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="gallery-create">
              @csrf
              <div class="form-group">
                <label for="type">Type</label>
                <select class="form-control" id="type" name="type">
                    <option value="photo">Photo</option>
                    <option value="video">Video</option>
                </select>
              </div>
              <div class="form-group form-group-photo-video">
                <label for="photo">Photo File</label>
                <input type="file" class="form-control-file" id="photo" name="photo">
              </div>

        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-submit-gallery">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
