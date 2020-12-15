
<div class="modal fade" id="modal-testimonial-edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Testimonial</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="testimonial-edit">
              @csrf
              <input type="hidden" name="id" id="edit-id">
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="edit-name" class="form-control" placeholder="Enter name...">
              </div>
              <div class="form-group">
                <label for="position">Position</label>
                <input type="text" name="position" id="edit-position" class="form-control" placeholder="Enter position...">
              </div>
              <div class="form-group">
                <label for="testimonial">Testimonial</label>
                <textarea class="form-control" id="edit-testimonial" name="testimonial" rows="3" placeholder="Enter Testimonial..."></textarea>
              </div>
              <div class="form-group form-group-edit-photo">
                <label for="photo">Photo File</label>
                <input type="file" class="form-control-file" id="edit-photo" name="photo">
              </div>

        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-testimonial-edit">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
