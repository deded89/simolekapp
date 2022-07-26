<div class='row'>
	<div class='col-xs-12'>
		<div class='box box-info'>
			<div class='box-header with-border bg-aqua'>
				<h3 class='box-title'>Download Format Realisasi Kegiatan</h3>
				<a href='<?php echo site_url('lkpk/Kegiatan') ?>'>
					<span class='btn btn-danger btn-xs pull-right'><i class='fa fa-arrow-left'></i> Batal</span>
				</a>
			</div>
			<form class='form-horizontal' action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class='box-body'>
					<div class="alert alert-info" style="margin-bottom:20px;font-size:12px">
            <h4><i class="icon fa fa-info"></i> Info :</h4>
						Untuk menambahkan Realisasi kegiatan silakan :
            <ol>
							<li>Pilih Tahun Anggaran, Periode dan Jenis Realisasi yang ingin diinput (keuangan / fisik) kemudian klik Tombol Download Format</li>
							<li>Download format import data dalam bentuk Ms.Excel dengan klik Tombol Download Format</li>
							<li>Isi sesuai dengan format yang telah di download, nilai realisasi yang diisi adalah nilai "PER BULAN" bukan "KUMULATIF"</li>
							<li>Klik tombol Import untuk masuk ke halaman Import dan ikuti petunjuk pada halaman tersebut</li>
            </ul>
          </div>
          <div class='form-group'>
            <label for="varchar" class='col-sm-2 control-label'>Pilih SKPD</label>
            <div class='col-sm-9'>
							<?php $id = $skpd; ?>
							<?php echo cmb_dinamiss2('skpd','skpd','nama_skpd','id_skpd',$id) ?>
							<?php echo form_error('skpd') ?>
            </div>
          </div>
					<div class='form-group'>
						<label for="varchar" class='col-sm-2 control-label'>Pilih Tahun Anggaran</label>
						<div class='col-sm-9'>
							<select class="form-control" name="tahun_anggaran" id="tahun_anggaran">
								 <option value="">Please Select</option>
								 <?php
								 foreach ($tahun_anggaran as $ta) {
										 ?>
										 <option
												 value="<?php echo $ta->id_ta ?>"><?php echo $ta->tahun ?>
										 </option>
										 <?php
								 }
								 ?>
							</select>
						</div>
					</div>
					<div class='form-group'>
						<label for="varchar" class='col-sm-2 control-label'>Pilih Periode</label>
						<div class='col-sm-9'>
							<select class="form-control" name="periode" id="periode">
									<option value="">Please Select</option>
									<?php
									foreach ($periode_pagu as $per) {
											?>
											<option
													class="<?php echo $per->tahun_anggaran ?>" value="<?php echo $per->id_per_pagu ?>"><?php echo $per->keterangan?>
											</option>
											<?php
									}
									?>
							</select>
						</div>
					</div>
					<div class='form-group'>
						<label for="varchar" class='col-sm-2 control-label'>Pilih Jenis Realisasi</label>
						<div class='col-sm-9'>
							<select class="form-control" name='jenis_realisasi' id='jenis_realisasi'>
								<option value="keuangan">Realisasi Keuangan</option>
								<option value="fisik">Realisasi Fisik</option>
							</select>
							<?php echo form_error('jenis_rencana') ?>
						</div>
					</div>
					<div class='form-group'>
						<label for="varchar" class='col-sm-2 control-label'>Pilih Bulan Realisasi</label>
						<div class='col-sm-9'>
							<select class="form-control" name='bulan_realisasi' id='bulan_realisasi'>
								<option value="0">Januari</option>
								<option value="1">Pebruari</option>
								<option value="2">Maret</option>
								<option value="3">April</option>
								<option value="4">Mei</option>
								<option value="5">Juni</option>
								<option value="6">Juli</option>
								<option value="7">Agustus</option>
								<option value="8">September</option>
								<option value="9">Oktober</option>
								<option value="10">Nopember</option>
								<option value="11">Desember</option>
							</select>
							<?php echo form_error('jenis_rencana') ?>
						</div>
					</div>
	        <!-- CSRF TOKEN -->
	        <?php
	          $csrf = array(
	            'name' => $this->security->get_csrf_token_name(),
	            'hash' => $this->security->get_csrf_hash()
	          );
	        ?>
	        <input type='hidden' name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
					<div class='col-xs-12 text-center'>
						<input type='submit' name='download' id="download" value='Download Format' class='btn btn-success'>
						<a href="<?php echo site_url('lkpk/kegiatan/hal_import_realisasi') ?>"><input type='button' name='hal_import' id="hal_import" value='Import' class='btn btn-info'></a>
					</div>
				</div>
    	</form>
		</div>
	</div>
</div>

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-chained/1.0.1/jquery.chained.min.js"></script>
<script type="text/javascript">
$("#periode").chained("#tahun_anggaran"); // disini kita hubungkan kota dengan provinsi
$(document).ready(function () {
	$('#skpd').select2({
		placeholder: "Pilih SKPD",
		allowClear:	true,
	});
	
});
</script>
