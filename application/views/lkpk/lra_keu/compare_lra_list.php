<!-- isi halaman -->
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
			<div class="box-body table-responsive">
				<table class="table table-bordered table-striped" id="mytable">
				   <thead>
						<tr>
							<th>SKPD</th>
							<th>Laporan SKPD</th>
							<th>Nilai LRA BAkeuda</th>
							<th>Selisih</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$start = 0;
						foreach ($compare_data as $compare){
					?>
						<tr>
							<td><?php echo $compare['nama_skpd'] ?></td>
							<td><?php echo "Rp " . number_format($compare['lap_skpd'],2,',','.');  ?></td>
							<td><?php echo "Rp " . number_format($compare['lra'],2,',','.');  ?></td>
							<td><?php echo "Rp " . number_format($compare['lap_skpd']-$compare['lra'],2,',','.');  ?></td>
							<?php
							$x = $compare['lap_skpd']-$compare['lra'];
							if ($x <> 0 ){
							?>
								<td><small class="label label-danger"> Perlu Perbaikan</small></td>
							<?php
						} else {
							?>
							<td><small class="label label-success"> OK</small></td>
							<?php
						}
							?>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		 </div>
	</div>
</div>

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$("#mytable").dataTable({
			 "aaSorting": [],
		});
	});
</script>
