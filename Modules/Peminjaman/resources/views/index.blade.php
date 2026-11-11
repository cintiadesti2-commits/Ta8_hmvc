@extends('layouts.app')
@section('title', 'Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Peminjaman Buku</h4>
        <p class="text-muted small mb-0">Kelola data peminjaman tanpa memuat ulang halaman.</p>
    </div>
    <button type="button" class="btn btn-primary" id="btnTambah">
        <i class="bi bi-plus-lg"></i> Tambah Peminjaman
    </button>
</div>

<div id="ajaxAlert"></div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody id="tabelPeminjaman">
                <tr><td colspan="8" class="text-center text-muted py-4">Memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah / Edit --}}
<div class="modal fade" id="modalPeminjaman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formPeminjaman" enctype="multipart/form-data" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="peminjamanId">

                    <div class="mb-3">
                        <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                        <input type="text" class="form-control" id="nama_peminjam" name="nama_peminjam">
                        <div class="invalid-feedback" id="error-nama_peminjam"></div>
                    </div>
                    <div class="mb-3">
                        <label for="judul_buku" class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" id="judul_buku" name="judul_buku">
                        <div class="invalid-feedback" id="error-judul_buku"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam">
                            <div class="invalid-feedback" id="error-tanggal_pinjam"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali">
                            <div class="invalid-feedback" id="error-tanggal_kembali"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="Dipinjam">Dipinjam</option>
                            <option value="Dikembalikan">Dikembalikan</option>
                        </select>
                        <div class="invalid-feedback" id="error-status"></div>
                    </div>
                    <div class="mb-1">
                        <label for="berkas" class="form-label">Bukti Peminjaman</label>
                        <input type="file" class="form-control" id="berkas" name="berkas" accept=".jpg,.jpeg,.png,.pdf">
                        <div class="invalid-feedback" id="error-berkas"></div>
                        <div class="form-text" id="berkasHint">Format jpg, jpeg, png, atau pdf. Maksimal 2 MB.</div>
                        <div class="small mt-2 d-none" id="berkasLama"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        }
    });

    const urls = {
        data: "{{ route('peminjaman.data') }}",
        store: "{{ route('peminjaman.store') }}",
        base: "{{ url('peminjaman') }}"
    };

    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPeminjaman'));
    const $form = $('#formPeminjaman');

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showAlert(type, message) {
        const $alert = $('<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert"></div>')
            .text(message)
            .append('<button type="button" class="btn-close" data-bs-dismiss="alert"></button>');
        $('#ajaxAlert').html($alert);
        setTimeout(function () {
            bootstrap.Alert.getOrCreateInstance($alert[0]).close();
        }, 4000);
    }

    function clearErrors() {
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');
    }

    function resetForm() {
        $form[0].reset();
        $('#peminjamanId').val('');
        $('#berkasLama').addClass('d-none').empty();
        $('#berkasHint').text('Format jpg, jpeg, png, atau pdf. Maksimal 2 MB.');
        clearErrors();
    }

    function statusBadge(status) {
        return status === 'Dikembalikan'
            ? '<span class="badge text-bg-success">Dikembalikan</span>'
            : '<span class="badge text-bg-warning">Dipinjam</span>';
    }

    function berkasCell(row) {
        if (!row.berkas_url) {
            return '<span class="text-muted">-</span>';
        }
        return '<a href="' + escapeHtml(row.berkas_url) + '" target="_blank" rel="noopener">' +
            '<i class="bi bi-paperclip"></i> ' + escapeHtml(row.nama_berkas || 'Lihat berkas') + '</a>';
    }

    function loadData() {
        $.get(urls.data).done(function (rows) {
            const $tbody = $('#tabelPeminjaman');

            if (rows.length === 0) {
                $tbody.html(
                    '<tr><td colspan="8"><div class="empty-state">' +
                    '<i class="bi bi-inbox fs-1"></i>' +
                    '<p class="mb-0 mt-2">Belum ada data peminjaman.</p></div></td></tr>'
                );
                return;
            }

            let html = '';
            rows.forEach(function (row, index) {
                html += '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td>' + escapeHtml(row.nama_peminjam) + '</td>' +
                    '<td>' + escapeHtml(row.judul_buku) + '</td>' +
                    '<td>' + escapeHtml(row.tanggal_pinjam) + '</td>' +
                    '<td>' + escapeHtml(row.tanggal_kembali) + '</td>' +
                    '<td>' + statusBadge(row.status) + '</td>' +
                    '<td>' + berkasCell(row) + '</td>' +
                    '<td class="text-end text-nowrap">' +
                        '<button type="button" class="btn btn-sm btn-outline-primary btn-edit me-1" data-id="' + row.id + '">' +
                            '<i class="bi bi-pencil"></i></button>' +
                        '<button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="' + row.id +
                            '" data-nama="' + escapeHtml(row.nama_peminjam) + '">' +
                            '<i class="bi bi-trash"></i></button>' +
                    '</td>' +
                    '</tr>';
            });
            $tbody.html(html);
        }).fail(function () {
            showAlert('danger', 'Gagal memuat data peminjaman.');
        });
    }

    // Tambah
    $('#btnTambah').on('click', function () {
        resetForm();
        $('#modalTitle').text('Tambah Peminjaman');
        modal.show();
    });

    // Edit
    $('#tabelPeminjaman').on('click', '.btn-edit', function () {
        const id = $(this).data('id');

        $.get(urls.base + '/' + id + '/edit').done(function (data) {
            resetForm();
            $('#modalTitle').text('Edit Peminjaman');
            $('#peminjamanId').val(data.id);
            $('#nama_peminjam').val(data.nama_peminjam);
            $('#judul_buku').val(data.judul_buku);
            $('#tanggal_pinjam').val(data.tanggal_pinjam);
            $('#tanggal_kembali').val(data.tanggal_kembali);
            $('#status').val(data.status);

            if (data.berkas_url) {
                $('#berkasLama').removeClass('d-none').text('Berkas saat ini: ').append(
                    $('<a>', {
                        href: data.berkas_url,
                        target: '_blank',
                        rel: 'noopener',
                        text: data.nama_berkas || 'Lihat berkas'
                    })
                );
                $('#berkasHint').text('Pilih berkas baru hanya jika ingin mengganti berkas lama.');
            }

            modal.show();
        }).fail(function () {
            showAlert('danger', 'Gagal mengambil data peminjaman.');
        });
    });

    // Simpan (Create / Update) dengan FormData
    $form.on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        const id = $('#peminjamanId').val();
        const formData = new FormData(this);
        let url = urls.store;

        if (id) {
            url = urls.base + '/' + id;
            formData.append('_method', 'PUT');
        }

        const $button = $('#btnSimpan').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false
        }).done(function (response) {
            modal.hide();
            loadData();
            showAlert('success', response.success);
        }).fail(function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                $.each(xhr.responseJSON.errors, function (field, messages) {
                    $('#' + field).addClass('is-invalid');
                    $('#error-' + field).text(messages[0]);
                });
            } else {
                showAlert('danger', 'Terjadi kesalahan pada server. Silakan coba lagi.');
            }
        }).always(function () {
            $button.prop('disabled', false);
        });
    });

    // Hapus
    $('#tabelPeminjaman').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const nama = $(this).data('nama');

        confirmDelete('Data peminjaman atas nama "' + nama + '" beserta berkasnya akan dihapus permanen.')
            .then(function (confirmed) {
                if (!confirmed) {
                    return;
                }

                $.ajax({
                    url: urls.base + '/' + id,
                    type: 'DELETE'
                }).done(function (response) {
                    loadData();
                    showAlert('success', response.success);
                }).fail(function () {
                    showAlert('danger', 'Gagal menghapus data peminjaman.');
                });
            });
    });

    loadData();
});
</script>
@endpush