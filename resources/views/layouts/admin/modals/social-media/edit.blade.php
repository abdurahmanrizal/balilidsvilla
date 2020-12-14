
<div class="modal fade" id="modal-edit-social-media" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-villa">Edit Social Media</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="edit-social-media">
              @csrf
              <input type="hidden" name="id" id="edit-social-media-id">
              <div class="form-group">
                <label for="edit_item_social_media">Social Media</label>
                <select class="form-control" id="edit_item_social_media" name="edit_item_social_media">
                  <option value="facebook">Facebook</option>
                  <option value="whatsapp">Whatsapp</option>
                  <option value="instagram">Instagram</option>
                </select>
              </div>
              <div class="form-group">
                <label for="edit_name_social_media">Name Social Media</label>
                <input type="text" class="form-control" id="edit_name_social_media" name="edit_name_social_media">
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-edit-social-media">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
