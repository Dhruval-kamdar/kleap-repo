<div class="uap-stuffbox">
	<h3 class="uap-h3"><?php echo __('Display MLM Matrix');?></h3>
	<div class="inside">

	<?php if (!empty($data['items']) || !empty($data['parent'])):?>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {packages:["orgchart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Name');
        data.addColumn('string', 'Manager');
        data.addColumn('string', 'ToolTip');

        // For each orgchart box, provide the name, manager, and tooltip to show.
				var theParent = '<?php echo $data['parent'];?>';
		data.addRows([
				<?php if (!empty($data['parent'])):?>
					[{v:'<?php echo $data['parent'];?>', f:'<?php echo $data['parent'];?>'}, '', ''],
				<?php endif;?>
          [{v:'<?php echo $affiliate_name; ?>', f:''}, theParent, 'Main Affiliate'],
		<?php
			if (!empty($data['items'])):
				foreach ($data['items'] as $item):
				echo "[{v:'".$item['username']."',f:'".$item['username']."<div>".$item['amount_value']."</div>' }, '".$item['parent']."', ' Level ".$item['level']."'],";
				endforeach;
			endif;
		?>
        ]);
        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById('uap_mlm_chart'));
        // Draw the chart, setting the allowHtml option to true for the tooltips.
				<?php if (!empty($data['parent'])):?>
				data.setRowProperty(0, 'style', 'background-color: #2a81ae; color: #fff;');
				data.setRowProperty(1, 'style', 'background-color: #f25a68; color: #fff;');
				<?php endif;?>
        chart.draw(data, {allowHtml:true, size:"medium", allowCollapse:true});
      }
   </script>
   <div id="uap_mlm_chart"></div>

			<table class="uap-dashboard-inside-table">
				<tbody>
					<tr>
						<th><?php _e('Subaffiliate', 'uap');?></th>
						<th><?php _e('Level', 'uap');?></th>
						<th><?php _e('Amount', 'uap');?></th>
					</tr>
					<?php foreach ($data['items'] as $item):?>
					<tr>
						<td><?php echo $item['username'];?></td>
						<td><?php echo $item['level'];?></td>
						<td><?php echo $item['amount_value'];?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php else : ?>
			<?php _e('Current Affiliate user has no other sub-affiliates into his MLM Matrix on this moment', 'uap');?>
		<?php endif;?>

	</div>
</div>
