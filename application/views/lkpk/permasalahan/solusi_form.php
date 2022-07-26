<div class='row'>
	<div class='col-xs-12'>
		<div class='box box-info'>
			<div class='box-header with-border bg-aqua'>
				<h3 class='box-title'>Form Input Solusi permasalahan</h3>
				<a href='<?php echo site_url('lkpk/Kegiatan/read/'.$id_kegiatan) ?>'>
					<span class='btn btn-danger btn-xs pull-right'><i class='fa fa-arrow-left'></i> Batal</span>
				</a>
			</div>
			<form class='form-horizontal' action="<?php echo $action; ?>" method="post">
        <div class='box-body'>

          <div class='form-group'>
            <label for="text" class='col-sm-2 control-label'>Solusi</label>
            <div class='col-sm-9'>
							<textarea rows="3" class="form-control" name="solusi" id="solusi" placeholder="Solusi pemecahan masalah"><?php echo $solusi; ?></textarea>
							<?php echo form_error('solusi') ?>
            </div>
          </div>

          <!-- <div class='form-group'>
            <label for="text" class='col-sm-2 control-label'>Status</label>
            <div class='col-sm-9'>
							<select class="form-control" name='status' id='status'>
									<option value="0" <?php// echo $solusi == '0' ? selected : ''?>  >Open</option>
									<option value="1" <?php// echo $solusi == '1'   ?  selected : ''?> >Closed</option>
							</select>
							<?php //echo form_error('solusi') ?>
            </div>
          </div> -->

					<input type='hidden' name='status' value=1>
					<input type='hidden' name='selesai' value=<?php echo date("Y-m-d") ?>>
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
</script>
