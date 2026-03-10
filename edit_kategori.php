<?php while($d = mysqli_fetch_array($query)){ ?>
<tr>
    <td><?= $d['id_kategori']; ?></td>
    <td><strong><?= $d['nama_kategori']; ?></strong></td>
    <td>
        <span class="badge <?= ($d['jenis_aset'] == 'Lahan') ? 'badge-success' : 'badge-info'; ?>">
            <?= $d['jenis_aset']; ?>
        </span>
    </td>
    <td><?= $d['keterangan'] ? $d['keterangan'] : '-'; ?></td>
    <td class="text-center">
        <button class="btn btn-sm btn-warning text-white shadow-sm" data-toggle="modal" data-target="#modal-edit<?= $d['id_kategori']; ?>">
            <i class="fas fa-edit"></i>
        </button>
        
        <a href="hapus_kategori.php?id=<?= $d['id_kategori']; ?>" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Yakin ingin menghapus kategori <?= $d['nama_kategori']; ?>?')">
            <i class="fas fa-trash"></i>
        </a>

        <div class="modal fade" id="modal-edit<?= $d['id_kategori']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="proses_kategori.php" method="POST">
                        <div class="modal-header bg-warning">
                            <h4 class="modal-title text-white"><i class="fas fa-edit mr-2"></i>Edit Kategori</h4>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body text-left">
                            <input type="hidden" name="id_kategori" value="<?= $d['id_kategori']; ?>">
                            
                            <div class="form-group">
                                <label>Nama Kategori</label>
                                <input type="text" name="nama_kategori" class="form-control" value="<?= $d['nama_kategori']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Jenis Aset</label>
                                <select name="jenis_aset" class="form-control">
                                    <option value="Lahan" <?= ($d['jenis_aset'] == 'Lahan') ? 'selected' : ''; ?>>Lahan</option>
                                    <option value="Bangunan" <?= ($d['jenis_aset'] == 'Bangunan') ? 'selected' : ''; ?>>Bangunan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan..."><?= $d['keterangan']; ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" name="update" class="btn btn-warning text-white px-4 shadow-sm">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </td>
</tr>
<?php } ?>