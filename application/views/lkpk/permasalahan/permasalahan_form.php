<div class='row'>
	<div class='col-xs-12'>
		<div class='box box-info'>
			<div class='box-header with-border bg-aqua'>
				<h3 class='box-title'>Form Tambah Data permasalahan</h3>
				<a href='<?php echo site_url('lkpk/Kegiatan/read/'.$id_kegiatan) ?>'>
					<span class='btn btn-danger btn-xs pull-right'><i class='fa fa-arrow-left'></i> Batal</span>
				</a>
			</div>
			<form class='form-horizontal' action="<?php echo $action; ?>" method="post">
        <div class='box-body'>

					<div class='form-group'>
						<label for="int" class='col-sm-2 control-label'>Kegiatan</label>
						<div class='col-sm-9'>
							<input  disabled type='text' class='form-control' name='nama_kegiatan' id='nama_kegiatan' value='<?php echo $kegiatan; ?>'>
							<?php echo form_error('kegiatan') ?>
						</div>
					</div>

          <div class='form-group'>
            <label for="text" class='col-sm-2 control-label'>Masalah</label>
            <div class='col-sm-9'>
							<textarea autofocus rows="3" class="form-control" name="masalah" id="masalah" placeholder="Permasalahan Kegiatan"><?php echo $masalah; ?></textarea>
							<?php echo form_error('masalah') ?>
            </div>
          </div>

          <div class='form-group'>
            <label for="text" class='col-sm-2 control-label'>Sejak</label>
            <div class='col-sm-9'>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" name="sejak" id="sejak" placeholder="Tanggal Permasalahan Muncul" value="<?php echo $sejak ?>" autocomplete="off">
							</div>
							<?php echo form_error('sejak') ?>
            </div>
          </div>

          <div class='form-group'>
            <label for="text" class='col-sm-2 control-label'>Upaya</label>
            <div class='col-sm-9'>
							<textarea rows="3" class="form-control" name="upaya" id="upaya" placeholder="Upaya yang sudah dilakukan untuk mengatasi masalah diatas"><?php echo $upaya; ?></textarea>
							<?php echo form_error('upaya') ?>
            </div>
          </div>

          <div class='form-group'>
            <label for="text" class='col-sm-2 control-label'>Pihak yg Dapat <br> Membantu</label>
            <div class='col-sm-9'>
							<textarea rows="3" class="form-control" name="pihak" id="pihak" placeholder="Pihak yang Diharapkan Dapat Membantu Penyelesaian Masalah"><?php echo $pihak; ?></textarea>
							<?php echo form_error('pihak') ?>
            </div>
          </div>

          <div class='form-group'>
            <label for="text" class='col-sm-2 control-label'>Arahan</label>
            <div class='col-sm-9'>
							<textarea rows="3" class="form-control" name="arahan" id="arahan" placeholder="Arahan yang diberikan pimpinan untuk mengatasi masalah diatas"><?php echo $arahan; ?></textarea>
							<?php echo form_error('arahan') ?>
            </div>
          </div>

          <!-- <div class='form-group'>
            <label for="text" class='col-sm-2 control-label'>Solusi</label>
            <div class='col-sm-9'>
							<textarea rows="3" class="form-control" name="solusi" id="solusi" placeholder="Solusi pemecahan masalah"><?php echo $solusi; ?></textarea>
							<?php// echo form_error('solusi') ?>
            </div>
          </div> -->

					<input type='hidden' name='kegiatan' value='<?php echo $id_kegiatan  ?>'>
					<input type='hidden' name='status' value=0>
					<input type='hidden' name='id_masalah' value='<?php echo $id_masalah ?>'>
	        <!-- CSRF TOKEN -->
	        <?php
	          $csrf = array(
	            'name' => $this->security->get_csrf_token_name(),
	            'hash' => $this->security->get_csrf_hash()
	          );
	        ?>
	        <input type='hidden' name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
					<div class='col-xs-12 text-center'>
						<input type='submit' name='simpan' value='Simpan' class='btn btn-info'>
					</div>
				</div>
    	</form>
		</div>
	</div>
</div>

<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$("#sejak").datepicker({
		autoclose:true,
		format:'yyyy-mm-dd',
		todayHighlight:true,
		todayBtn:'linked',
	});
});
</script>
