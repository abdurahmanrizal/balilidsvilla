
<div class="modal fade" id="modal-create-social-media" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Social Media</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="create-social-media">
              @csrf
              <div class="form-group">
                <label for="item_social_media">Social Media</label>
                <select class="form-control" id="item_social_media" name="item_social_media">
                  <option value="facebook">Facebook</option>
                  <option value="whatsapp">Whatsapp</option>
                  <option value="instagram">Instagram</option>
                </select>
              </div>
              <div class="form-group">
                <label for="name_social_media">Name Social Media</label>
                <input type="text" class="form-control" id="name_social_media" name="name_social_media" placeholder="Enter your social media accout">
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-submit-create-social-media">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
