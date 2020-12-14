
<div class="modal fade" id="modal-create-description" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Description</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="create-description">
              @csrf
              <div class="form-group">
                <label for="content">Company Description</label>
                <textarea id="content" name="content" placeholder="Type company description..."></textarea>
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-submit-description">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
