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

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $lang_inventory; ?></h1>
		</div>
	</div><!--/.row-->
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					
					<div class="row" style="border-bottom: 1px solid #e0dede; padding-bottom: 8px; margin-top: -5px;">
						<div class="col-md-6">
							
						</div>
						<div class="col-md-6" style="text-align: right;">
							<a href="<?=base_url()?>inventory/exportInventory" style="text-decoration: none;">
								<button type="button" class="btn btn-success" style="background-color: #5cb85c; border-color: #4cae4c;">
									<?php echo $lang_export_inventory; ?>
								</button>
							</a>
						</div>
					</div>
					
					<form action="<?=base_url()?>inventory/searchInventory" method="get">
						<div class="row" style="margin-top: 10px;">
							<div class="col-md-3">
								<div class="form-group">
									<label><?php echo $lang_product_code; ?></label>
									<input type="text" name="code" class="form-control" />
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label><?php echo $lang_product_name; ?></label>
									<input type="text" name="name" class="form-control" />
								</div>
							</div>
							<div class="col-md-3"></div>
							<div class="col-md-3">
								<div class="form-group">
									<label>&nbsp;</label><br />
									<button class="btn btn-primary" style="width: 100%;">&nbsp;&nbsp;<?php echo $lang_search; ?>&nbsp;&nbsp;</button>
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
								    	<th width="15%"><?php echo $lang_code; ?></th>
								    	<th width="15%"><?php echo $lang_name; ?></th>
								    	<th width="15%"><?php echo $lang_total_quantity; ?></th>
								    	<!-- <th width="15%">Total Cost</th> -->
									    <th width="10%"><?php echo $lang_action; ?></th>
									</tr>
							    </thead>
								<tbody>
								<?php
                                    if (count($results) > 0) {
                                        foreach ($results as $data) {
                                            $id = $data->id;
                                            $code = $data->code;
                                            $name = $data->name;

                                            $inv_qty = 0;

                                            $ckInvResult = $this->db->query("SELECT qty, outlet_id FROM inventory WHERE product_code = '$code' ");
                                            $ckInvData = $ckInvResult->result();
                                            for ($k = 0; $k < count($ckInvData); ++$k) {
                                                $ckInv_qty = $ckInvData[$k]->qty;
                                                $ckOutlet_id = $ckInvData[$k]->outlet_id;

                                                // Check Outlet;
                                                $ckOutletResult = $this->db->query("SELECT id FROM outlets WHERE id = '$ckOutlet_id' ");
                                                $ckOutletRows = $ckOutletResult->num_rows();
                                                if ($ckOutletRows == 1) {
                                                    $inv_qty += $ckInv_qty;
                                                }
                                                unset($ckOutletResult);
                                                unset($ckOutletRows);

                                                unset($ckInv_qty);
                                                unset($ckOutlet_id);
                                            }
                                            unset($ckInvResult);
                                            unset($ckInvData);

                                            $each_cost = 0;
                                            $getCostResult = $this->db->query("SELECT purchase_price FROM products WHERE code = '$code' ");
                                            $getCostData = $getCostResult->result();

                                            $each_cost = $getCostData[0]->purchase_price;

                                            unset($getCostResult);
                                            unset($getCostData);

                                            $each_row_cost = 0;
                                            $each_row_cost = $inv_qty * $each_cost; ?>
                                			<tr>
	                                			<td><?php echo $code; ?></td>
	                                			<td><?php echo $name; ?></td>
	                                			<td><?php echo $inv_qty; ?></td>
	                                			<!-- <td><?php echo number_format($each_row_cost, 2, '.', ''); ?></td> -->
	                                			<td>
		                                			<a href="<?=base_url()?>inventory/view_detail?pcode=<?php echo $code; ?>" style="text-decoration: none;">
			                                			<button class="btn btn-primary" style="padding: 5px 12px;">
			                                				&nbsp;&nbsp;<?php echo $lang_view; ?>&nbsp;&nbsp;
			                                			</button>
		                                			</a>
	                                			</td>
                                			</tr>
                                <?php

                                        }
                                    } else {
                                        ?>
										<tr class="no-records-found">
											<td colspan="3"><?php echo $lang_no_match_found; ?></td>
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
							<?php echo $displayshowingentries; ?>
						</div>
						<div class="col-md-6" style="text-align: right;">
							<?php echo $links; ?>
						</div>
					</div>
					
					<!--
					<?php
                        $total_cost_amt = 0;
                        $total_stock_qty = 0;

                        $getAllInvResult = $this->db->query('SELECT * FROM inventory ');
                        $getAllInvData = $getAllInvResult->result();

                        for ($g = 0; $g < count($getAllInvData); ++$g) {
                            $each_row_code = $getAllInvData[$g]->product_code;
                            $each_row_qty = $getAllInvData[$g]->qty;

                            $total_stock_qty += $each_row_qty;

                            $each_cost = 0;
                            $getCostResult = $this->db->query("SELECT purchase_price FROM products WHERE code = '$each_row_code' ");
                            $getCostData = $getCostResult->result();

                            $each_cost = $getCostData[0]->purchase_price;

                            unset($getCostResult);
                            unset($getCostData);

                            $total_cost_amt += ($each_row_qty * $each_cost);
                        }
                    ?>
					<div class="row" style="padding-top: 10px; padding-bottom: 10px; margin-top: 50px; font-size: 18px; letter-spacing: 0.5px; border-top: 1px solid #ddd;">
						<div class="col-md-3" style="font-weight: bold;">Total Stock Qty.</div>
						<div class="col-md-9" style="font-weight: bold;">: 
							<?php echo $total_stock_qty; ?>
						</div>
					</div>
					
					<div class="row" style="padding-top: 10px; padding-bottom: 10px; font-size: 18px; letter-spacing: 0.5px; ">
						<div class="col-md-3" style="font-weight: bold;">Total Stock Value (<?php echo $site_currency; ?>)</div>
						<div class="col-md-9" style="font-weight: bold;">: 
							<?php echo number_format($total_cost_amt, 2, '.', ''); ?>
						</div>
					</div>
					-->
					
				</div><!-- Panel Body // END -->
			</div><!-- Panel Default // END -->
		</div><!-- Col md 12 // END -->
	</div><!-- Row // END -->
	
	<br /><br /><br />
	
</div><!-- Right Colmn // END -->
	
	
	
<?php
    require_once 'includes/footer.php';
?>