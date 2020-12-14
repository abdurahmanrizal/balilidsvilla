
<div class="modal fade" id="modal-edit-description" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-villa">Edit Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="edit-description">
              @csrf
              <input type="hidden" name="id" id="edit-description-id">
              <div class="form-group">
                <label for="edit-content">Company Description</label>
                <textarea id="edit-content" name="content"></textarea>
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-edit-description">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
