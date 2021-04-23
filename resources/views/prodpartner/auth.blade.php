<!--Auth Basic-->
<div class="modal fade" id="showModal3" tabindex="-1" role="dialog" aria-labelledby="showModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="showModalLabel">Basic Auth</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
        <div class="modal-body">
            <input type="hidden" id="cache_expire3" name="cache_expire3" value="300"> <!--Expired in 5 minutes-->
            <div class="form-group">
              <label for="uname" class="col-form-label">Username :</label>
              <input type="text" class="form-control" id="uname">
            </div>
            <div class="form-group">
              <label for="passwd" class="col-form-label">Password :</label>
              <input type="text" class="form-control" id="passwd">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveform3" type="button" class="btn btn-primary">Save</button>
        </div>
    </div>
  </div>
</div>

<!--Auth API Key-->
<div class="modal fade" id="showModal4" tabindex="-1" role="dialog" aria-labelledby="showModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="showModalLabel">API Key</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
        <div class="modal-body">
            <input type="hidden" id="cache_expire4" name="cache_expire4" value="300"> <!--Expired in 5 minutes-->
            <div class="form-group">
              <label for="apikey" class="col-form-label">Key :</label>
              <input type="text" class="form-control" id="apikey">
            </div>
            <div class="form-group">
              <label for="apivalue" class="col-form-label">Value :</label>
              <input type="text" class="form-control" id="apivalue">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveform4" type="button" class="btn btn-primary">Save</button>
        </div>
    </div>
  </div>
</div>

<!--Auth Token-->
<div class="modal fade" id="showModal5" tabindex="-1" role="dialog" aria-labelledby="showModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="showModalLabel">Token</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
        <div class="modal-body">
            <input type="hidden" id="cache_expire5" name="cache_expire5" value="300"> <!--Expired in 5 minutes-->
            <div class="form-group">
              <label for="token" class="col-form-label">Token :</label>
              <input type="text" class="form-control" id="token">
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveform5" type="button" class="btn btn-primary">Save</button>
        </div>
    </div>
  </div>
</div>
