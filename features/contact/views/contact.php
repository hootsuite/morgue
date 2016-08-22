<!-- gcal-->
<div class="row-fluid">
<!-- Editable Controls -->
  <form class="form-horizontal">
  <div class="span6">
    <div class="control-group">
      <label class="control-label" id="event-start-time">People Involved: </label>
      <div class="controls controls-row">
          <input type="text" placeholder="Enter contact username" readonly="readonly" id="contact" name="contact" class="input-xxlarge editable" value="<?php echo $contact; ?>"/>
		 <input type="button" onclick="edit_people_involved()" class="btn editable_hidden" style="display:none;" value="Edit" />
         <input type="button" onclick="save_people_involved()" class="btn editable_hidden" style="display:none;" value="Save" />
      </div>
    </div>
    </div>
    </form>

</div>

