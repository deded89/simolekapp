<div class="row">
  <div class="col-md-3">
    <form action="<?php echo site_url('pengadaan/setting/update') ?>" method="post">
      <div class="form-group">

        <label>Pilih Tahun Anggaran</label>
        <?php
        $ta = $this->session->userdata('tahun_anggaran');
        ?>
        <select name="tahun_anggaran" class="form-control">
          <option value="2019" <?php echo $ta == '2019' ? 'selected' : '' ?>>2019</option>
          <option value="2020" <?php echo $ta == '2020' ? 'selected' : '' ?>>2020</option>
          <option value="2021" <?php echo $ta == '2021' ? 'selected' : '' ?>>2021</option>
        </select>

        <!-- CSRF TOKEN -->
        <?php
        $csrf = array(
          'name' => $this->security->get_csrf_token_name(),
          'hash' => $this->security->get_csrf_hash()
        );
        ?>
        <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
      </div>
      <button type="submit" class="btn btn-primary" name="button">Simpan</button>
    </form>
  </div>
</div>