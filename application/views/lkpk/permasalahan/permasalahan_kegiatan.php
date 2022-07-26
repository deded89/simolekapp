<!-- isi halaman -->

<div class="row">
    <div class="col-xs-12">
			<div class="box box-primary box-solid">
				<div class="box-header with-border bg-aqua">
					<h4 class="box-title">
							Permasalahan
					</h4>
					<a href="<?php echo site_url('lkpk/permasalahan/create/'.$id_kegiatan) ?>">
	          <span class="btn btn-success btn-xs pull-right"><i class="fa fa-plus"></i> Tambah Baru</span>
	        </a>
				</div>
			<div class="box-body table-responsive">
				<table class="table table-bordered table-striped" id="mytable">
				   <thead>
						<tr>
							<th width="30px">No</th>
							<th>Masalah</th>
							<th>Sejak</th>
							<th>Upaya</th>
							<th>Pihak yg Dapat Bantu</th>
							<th>Arahan</th>
							<th>Solusi</th>
							<th>Status</th>
							<th style="text-align:center">Aksi</th>
						</tr>
					</thead>
					<tbody>
					<?php
						$start = 0;
						foreach ($permasalahan_data as $permasalahan){
					?>
						<tr>
							<td><?php echo ++$start ?></td>
							<td><?php echo $permasalahan->masalah ?></td>
							<td nowrap><?php echo $permasalahan->sejak ?></td>
							<td><?php echo $permasalahan->upaya ?></td>
							<td><?php echo $permasalahan->bantuan_pihak ?></td>
							<td><?php echo $permasalahan->arahan ?></td>
							<td><?php echo $permasalahan->solusi ?></td>
							<?php
								if ($permasalahan->status == 1){
							?>
								<td> <small class="label label-danger">Closed</small></td>
							<?php
								}else{
							?>
								<td><small class="label label-success">Open</small></td>
							<?php
								}
							?>
							 <td style="text-align:center" width="120px">
							<?php
								echo anchor(site_url('lkpk/permasalahan/input_solusi/'.$permasalahan->id_masalah.'/'.$id_kegiatan),'<i class="fa fa-lock"></i>', 'title="Tutup Permasalahan" class="btn btn-success btn-sm"');
								echo '  ';
								echo anchor(site_url('lkpk/permasalahan/update/'.$permasalahan->id_masalah.'/'.$id_kegiatan),'<i class="fa fa-pencil-square-o"></i>', 'title="Update" class="btn btn-warning btn-sm"');
								echo '  ';
								echo anchor(site_url('lkpk/permasalahan/delete/'.$permasalahan->id_masalah.'/'.$id_kegiatan),'<i class="fa fa-trash-o"></i>', 'title="Hapus" class="btn btn-danger btn-sm" onclick="javasciprt: return confirm(\'Are You Sure ?\')"');
							?>
							</td>
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
		$("#mytable").dataTable();
	});
</script>
