
<div class="modal fade" id="modal-create-package" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Data Package</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form id="create-package">
              @csrf
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="ex: Ala Carte" required>
              </div>
              <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Type here for description..."></textarea>

              </div>
              <div class="form-group">
                <label for="price">Price</label>
                <input type="text" class="form-control" name="price" id="price" placeholder="ex: 300000" pattern="\d*" required>
              </div>
              <div class="form-group">
                <label for="attribute-package">Attribute Package</label>
                <select class="form-control" id="attribute-package" name="attribute_package[]" multiple="multiple">
                    @foreach ($resorts as $resort)
                        <option value="{{$resort->id}}">{{$resort->name}}</option>
                    @endforeach
                </select>
              </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btn-submit-package">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
