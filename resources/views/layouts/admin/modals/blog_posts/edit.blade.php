
<div class="modal fade" id="modal-edit-blog-post" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-villa">Edit Blog Post</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="edit-blog-post">
              @csrf
              <input type="hidden" name="id" id="edit-blog-id">
              <div class="form-group">
                <label for="edit-title">Title</label>
                <input type="text" name="title" id="edit-title" class="form-control">
              </div>
              <div class="form-group">
                <label for="edit-content">Content</label>
                <textarea id="edit-content" name="content"></textarea>
              </div>
              <div class="form-group form-group-edit-photo-video">
                <label for="edit-photo">Photo File</label>
                <input type="file" class="form-control-file" id="edit-photo" name="photo">
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-edit-blog-post">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
