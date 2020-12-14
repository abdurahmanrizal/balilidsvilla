
<div class="modal fade" id="modal-edit-activity" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-activity">Edit Data Activity</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="edit-activity">
              @csrf
              <input type="hidden" name="edit_id" id="edit-id">
              <div class="form-group">
                <label for="edit-name">Name</label>
                <input type="text" class="form-control" id="edit-name" name="name">
              </div>
              <div class="form-group">
                <label for="edit-description">Description</label>
                <textarea id="edit-description" name="description"></textarea>

              </div>
              <div class="form-group">
                <label for="edit-price">Price</label>
                <input type="text" class="form-control" name="price" id="edit-price"  pattern="\d*">
              </div>
              <div class="form-group">
                <label for="edit-location">Location</label>
                <input type="text" class="form-control" name="location" id="edit-location">
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-edit-activity">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
