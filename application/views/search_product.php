<?php
    require_once 'includes/header.php';
?>
<!-- Add jQuery library -->
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

<!-- Add mousewheel plugin (this is optional) -->
<script type="text/javascript" src="<?=base_url()?>assets/js/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?=base_url()?>assets/js/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?=base_url()?>assets/js/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

<!-- Optionally add helpers - button, thumbnail and/or media -->
<link rel="stylesheet" href="<?=base_url()?>assets/js/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="<?=base_url()?>assets/js/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>

<link rel="stylesheet" href="<?=base_url()?>assets/js/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="<?=base_url()?>assets/js/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
	function openReceipt(ele){
		var myWindow = window.open(ele, "", "width=380, height=550");
	}	
</script>




<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $lang_search_product; ?></h1>
		</div>
	</div><!--/.row-->
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					
					<?php
                        if ($user_role < 2) {
                            ?>
					<div class="row" style="border-bottom: 1px solid #e0dede; padding-bottom: 8px;">
						<div class="col-md-12">
							<a href="<?=base_url()?>products/addproduct" style="text-decoration: none">
								<button class="btn btn-primary" style="padding: 0px 12px;"><i class="icono-plus"></i>
									<?php echo $lang_add_product; ?>
								</button>
							</a>
						</div>
					</div>
					<?php

                        }
                    ?>
					
					<form action="<?=base_url()?>products/searchProduct" method="get">
						<div class="row" style="margin-top: 10px;">
							<div class="col-md-3">
								<div class="form-group">
									<label><?php echo $lang_product_code; ?></label>
									<input type="text" name="code" class="form-control" value="<?php echo $search_code; ?>" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label><?php echo $lang_product_name; ?></label>
									<input type="text" name="name" class="form-control" value="<?php echo $search_name; ?>" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label><?php echo $lang_product_category; ?></label>
									<select name="category" class="form-control">
										<option value="-" <?php if ($search_category == '-') {
                        echo 'selected="selected"';
                    } ?>>All</option>
									<?php
                                        $catData = $this->Constant_model->getDataOneColumn('category', 'status', '1');
                                        for ($ct = 0; $ct < count($catData); ++$ct) {
                                            $catDta_id = $catData[$ct]->id;
                                            $catDta_name = $catData[$ct]->name; ?>
											<option value="<?php echo $catDta_id; ?>" <?php if ($catDta_id == $search_category) {
                                                echo 'selected="selected"';
                                            } ?>>
												<?php echo $catDta_name; ?>
											</option>
									<?php
                                            unset($catDta_id);
                                            unset($catDta_name);
                                        }
                                    ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>&nbsp;</label><br />
									<button class="btn btn-primary" style="width: 100%;">&nbsp;&nbsp;<?php echo $lang_search_product; ?>&nbsp;&nbsp;</button>
								</div>
							</div>
						</div>
					</form>
					
					<div class="row" style="margin-top: 0px;">
						<div class="col-md-12">
							
						<div class="table-responsive">
							<table class="table">
							    <thead>
								    
							    	<tr>
								    	<th width="10%"><?php echo $lang_code; ?></th>
								    	<th width="20%"><?php echo $lang_name; ?></th>
								    	<th width="10%"><?php echo $lang_image; ?></th>
								    	<th width="15%"><?php echo $lang_category; ?></th>
								    	<th width="10%"><?php echo $lang_cost; ?></th>
								    	<th width="10%"><?php echo $lang_price; ?></th>
									    <th width="10%"><?php echo $lang_status; ?></th>
									    <th width="15%"><?php echo $lang_action; ?></th>
									</tr>
							    </thead>
								<tbody>
<?php
    $sort = '';

    if (!empty($search_code)) {
        $sort .= " AND code LIKE '$search_code%' ";
    }

    if (!empty($search_name)) {
        $sort .= " AND name LIKE '%$search_name%' ";
    }

    if (!empty($search_category)) {
        if ($search_category == '-') {
            $sort .= ' AND category > 0 ';
        } else {
            $sort .= " AND category = '$search_category' ";
        }
    }

    $prodResult = $this->db->query("SELECT * FROM products WHERE created_datetime != '0000-00-00 00:00:00' $sort ");
    $prodRows = $prodResult->num_rows();

    $result_count = $prodRows;

    if ($prodRows > 0) {
        $prodData = $prodResult->result();
        for ($p = 0; $p < count($prodData); ++$p) {
            $id = $prodData[$p]->id;
            $code = $prodData[$p]->code;
            $name = $prodData[$p]->name;
            $cat_id = $prodData[$p]->category;
            $cost = $prodData[$p]->purchase_price;
            $price = $prodData[$p]->retail_price;
            $thumbnail = $prodData[$p]->thumbnail;
            $status = $prodData[$p]->status;

            $category_name = '-';
            $categoryData = $this->Constant_model->getDataOneColumn('category', 'id', $cat_id);
            if (count($categoryData) > 0) {
                $category_name = $categoryData[0]->name;
            }

            $large_file_path = ''; ?>
			<tr>
				<td><?php echo $code; ?></td>
				<td><?php echo $name; ?></td>
				<td>
					<?php
                        if ($thumbnail == 'no_image.jpg') {
                            $large_file_path = base_url().'assets/upload/products/small/no_image.jpg'; ?>
							<img src="<?=base_url()?>assets/upload/products/xsmall/no_image.jpg" height="50px" style="border: 1px solid #ccc" />
					<?php

                        } else {
                            $large_file_path = base_url().'assets/upload/products/small/'.$code.'/'.$thumbnail; ?>
								<img src="<?=base_url()?>assets/upload/products/xsmall/<?php echo $code; ?>/<?php echo $thumbnail; ?>" height="50px" style="border: 1px solid #ccc" />
					<?php

                        } ?>
				</td>
				<td><?php echo $category_name; ?></td>
				<td><?php echo number_format($cost, 0,'.','.'); ?></td>
				<td><?php echo number_format($price, 0,'.','.'); ?></td>
				<td style="font-weight: bold;">
				<?php
                    if ($status == '1') {
                        echo '<span style="color: #38786a;">'.$lang_active.'</span>';
                    }
            if ($status == '0') {
                echo '<span style="color: #f9243f;">'.$lang_inactive.'</span>';
            } ?>
				</td>
				<td>
		<a class="fancybox" rel="group" href="<?php echo $large_file_path; ?>" style="text-decoration: none;" title="<?php echo $code; ?>">
			<i class="icono-image" style="color: #005b8a; height: 30px;"></i>
		</a>
					
		<a href="<?=base_url()?>products/editproduct?id=<?php echo $id; ?>" style="text-decoration: none; margin-left: 10px;" title="Edit">
			<img src="<?=base_url()?>assets/img/edit_icon.png" height="30px" />
		</a>
					
<a onclick="openReceipt('<?=base_url()?>products/printBarcode?pcode=<?php echo $code; ?>')" style="text-decoration: none; cursor: pointer;" title="Print Barcode">
<img src="<?=base_url()?>assets/img/barcode_icon.png" height="20px" />
</a>
				</td>
			</tr>
<?php

            unset($id);
            unset($code);
            unset($name);
            unset($cat_id);
            unset($cost);
            unset($price);
            unset($thumbnail);
            unset($status);
        }
        unset($prodData);
    } else {
        ?>
		<tr class="no-records-found">
			<td colspan="8"><?php echo $lang_no_match_found; ?></td>
		</tr>
<?php

    }

?>
								</tbody>
							</table>
						</div>
							
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6" style="float: left; padding-top: 10px;">
							<?php
                                if ($result_count > 0) {
                                    ?>
							Showing 1 to <?php echo $result_count; ?> of <?php echo $result_count; ?> 
							<?php
                                if ($result_count == 1) {
                                    echo 'entry';
                                } else {
                                    echo 'entries';
                                } ?>
							<?php

                                }
                            ?>
						</div>
						<div class="col-md-6" style="text-align: right;">
							<?php //echo $links;?>
						</div>
					</div>
					
				</div><!-- Panel Body // END -->
			</div><!-- Panel Default // END -->
		</div><!-- Col md 12 // END -->
	</div><!-- Row // END -->
	
	<br /><br /><br />
	
</div><!-- Right Colmn // END -->
	
	
	
<?php
    require_once 'includes/footer.php';
?>