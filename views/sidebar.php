<div class="row-fluid">
  <?php if (Configuration::get_current_username() != 'Not Signed In') { ?>
  <a class="btn btn-large btn-block btn-primary btn-success" data-toggle="modal" href="#create" data-target="#create">
    <i class="icon-plus icon-white"></i> Create
  </a>
  <?php } ?>
</div>
<div class="row-fluid">
  <!-- History Here -->
</div>
<?php if (isset($tags)) : ?>
<hr/>
Current User: <?php echo Configuration::get_current_username(); ?>
<hr/>
<div class="row-fluid" id="tag_row">
	<b>Filter by Tags</b>
  <?php
    if (isset($selected_tags) && count($selected_tags) > 0) {
      echo " (<a href=\"javascript:clearSelectedTags()\">Clear</a>)";
    }
  ?>
	<div class="well well-small" id="tag_well">
	<?php
      if (isset($tags) && count($tags) > 0) {
      foreach ($tags as $tag) {
        $selected = isset($selected_tags) ? in_array($tag['id'], $selected_tags) : false;
      	$style =  $selected ? 'label label-info' : 'label';
        $id = $selected ? "tag-$tag[id]-selected" : "tag-$tag[id]";
      	echo "<span class=\"$style tag\" id=\"$id\">$tag[title]</span>";
      }
    }
    ?>
	</div>
</div>
<?php endif ?>
<?php 
if (Configuration::get_current_username() != "Not Signed In") {
    include __DIR__.'/modal/create.php'; 
}
?>
<script type="text/javascript" src="/assets/js/tags.js"></script>
