
<div class="modal fade" id="modal-show-villa" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="title-villa"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="show-villa">
              <div class="form-group">
                <label for="show-name">Name</label>
                <input type="text" class="form-control" id="show-name" readonly>
              </div>
              <div class="form-group">
                <label for="show-description">Description</label>
                <textarea id="show-description" readonly></textarea>

              </div>
              <div class="form-group">
                <label for="show-price">Price</label>
                <input type="text" class="form-control" id="show-price" readonly>
              </div>
              <div class="form-group">
                <label for="showlocation">Location</label>
                <input type="text" class="form-control" id="show-location" readonly>
              </div>
        </div>
        <div class="modal-footer">
            {{-- <button type="submit" class="btn btn-success" id="btn-submit-villa">Submit</button> --}}
          </form>
        </div>
      </div>
    </div>
  </div>
