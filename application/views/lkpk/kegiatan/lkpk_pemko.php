<div class="row">
  <div class='col-xs-12'>
    <div class='box box-info'>
      <div class='box-header with-border bg-aqua'>
        <h3 class='box-title'>Rekap Pemerintah Kota</h3>
      </div>
      <div class="box-body">
        <form  class='form-horizontal' action="<?php echo $action; ?>" method="post">
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
						<label for="varchar" class='col-sm-2 control-label'>Pilih Bulan Laporan</label>
						<div class='col-sm-9'>
							<select class="form-control" name='bulan_laporan' id='bulan_laporan'>
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
							<?php echo form_error('bulan_laporan') ?>
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
            <input type='submit' name='tampilkan' id="tampilkan" value='Tampilkan' class='btn btn-success'>
          </div>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url();?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-chained/1.0.1/jquery.chained.min.js"></script>
<script>
    $("#periode").chained("#tahun_anggaran"); // disini kita hubungkan kota dengan provinsi
</script>
