<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<div class="page-header">
<div class="container-fluid">
  <div class="pull-right">
	<a href="<?php echo $setting; ?>" data-toggle="tooltip" title="<?php echo $button_setting; ?>" class="btn btn-default"><i class="fa fa-cog"></i></a>
	<button type="submit" form="form-featured" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
	<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
  </div>

  <h1><?php echo $heading_title; ?></h1>
  <ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
	<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
  </ul>
</div>
</div>
<div class="container-fluid">
<?php  if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
  </div>
  
  <div class="panel-body">
	  <div class="form-horizontal">
	   <div class="form-group">
		<label class="col-sm-2 control-label" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
		<div class="col-sm-10">
		  <input type="text" name="product_name" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
		</div>
	  </div>
	  </div>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">			
	 
	  <!-- special -->
	  <div class="table-responsive">
		<table id="special" class="table table-striped table-bordered table-hover">
		  <thead>
			<tr>

			  <td class="text-left"><?php echo $entry_product_id; ?></td>
			  <td class="text-left"><?php echo $entry_product; ?></td>
			  <td class="text-left"><?php echo $entry_customer_group; ?></td>
			  <td class="text-right"><?php echo $entry_priority; ?></td>
			  <td class="text-right"><?php echo $entry_price; ?></td>
			  <td class="text-left"><?php echo $entry_date_start; ?></td>
			  <td class="text-left"><?php echo $entry_date_end; ?></td>

			  <td></td>
			</tr>
		  </thead>
		  <tbody>
			<?php $special_row = 0; ?>
			<?php foreach ($product_specials as $product_special) { ?>
			<tr id="special-row<?php echo $special_row; ?>">
			<td class="text-right" style="width:0px;"><input type="text" name="product_special[<?php echo $special_row; ?>][product_id]" value="<?php echo $product_special['product_id']; ?>" class="form-control" readonly /></td>
			<td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][product_name]" value="<?php echo $product_special['product_name']; ?>" class="form-control" readonly /></td>
			  <td class="text-left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]" class="form-control" >
				  <?php foreach ($customer_groups as $customer_group) { ?>
				  <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
				  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
				  <?php } else { ?>
				  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
				  <?php } ?>
				  <?php } ?>
				</select></td>
			  <td class="text-right" style="width:0px;"><input type="text" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control"  /></td>
			  <td class="text-right"><input type="text" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control"  /></td>
			  <td class="text-left" style="width: 20%;"><div class="input-group date">
				  <input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
				  <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
			  <td class="text-left" style="width: 20%;"><div class="input-group date">
				  <input type="text" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
				  <span class="input-group-btn">
                          <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                          </span></div></td>
			  <td class="text-left">
		
			  <button type="button" onclick="deleteOffer('<?php echo $special_row; ?>');"   class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>
		  
				  </td>
			</tr>
			<?php $special_row++; ?>
			<?php } ?>
		  </tbody>
		  <tfoot>
			<tr>
			  <td colspan="5"></td>
			</tr>
		  </tfoot>
		</table>
	  </div>
	  <!-- special -->

	</form>
  </div>
</div>
</div>
  <script type="text/javascript">
var special_row = <?php echo $special_row; ?>;
	
function deleteOffer(rowId){
	var productId =$('input[name=\'product_special[' + rowId + '][product_id]\']').val();
	$.ajax({
		url: '/admin/index.php?route=extension/module/offertheday&token=<?php echo $token; ?>',
		type: "POST",
		dataType: 'text',
		data: "product_offer_delete=" + productId,
		success: function(answ) {
			$('#special-row' + rowId).remove();
		}
	});
}	
	
$('input[name=\'product_name\']').autocomplete({
	
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=extension/module/offertheday&token=<?php echo $token; ?>&type=autoload&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id'],
						price: item['price']
					}
				}));
			}
		});
	},
	select: function(item) {
		$('input[name=\'product_name\']').val();
		$('#featured-product' + item['value']).remove();
		addSpecial(item['value'], item['label'], item['price']);
	}
});
	


function addSpecial(value, label, price) {
	html  = '<tr id="special-row' + special_row + '">';
	html += '  <td class="text-right" style="width:0px;"><input type="text" name="product_offer[' + special_row + '][product_id]" value="' + value +'" class="form-control" readonly /></td>';
	html += '  <td class="text-right"><input type="text" name="product_offer[' + special_row + '][product_name]" value="' + label +'" class="form-control" readonly /></td>';
    html += '  <td class="text-left"><select name="product_offer[' + special_row + '][customer_group_id]" class="form-control">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
    <?php } ?>
    html += '  </select></td>';
    html += '  <td class="text-right" style="width:0px;"><input type="text" name="product_offer[' + special_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_offer[' + special_row + '][price]" value="" placeholder="' + price + '" class="form-control" /></td>';
    html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_offer[' + special_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_offer[' + special_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#special tbody').append(html);

	$('.date').datetimepicker({
		pickTime: false
	});

	special_row++;
}
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>
