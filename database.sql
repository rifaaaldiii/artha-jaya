1. Produksi Barang
id		
no_produksi	
nama_produksi 	
jumlah 			
status 	['produksi baru', 'siap produksi', 'dalam pengerjaan', 'selesai dikerjakan', 'lolos qc', 'produksi siap diambil', 'selesai']
catatan
team_id -> join tabel team
createdAt
updateAt

2. Petugas
id
nama
status ['ready', 'busy']
kontak
createdAt
updateAt

3. jasa dan layanan
id
no.ref
jenis_layanan
jadwal
catatn
petugas_id -> join petugas
pelanggan_id -> join pelanggan
status ['jasa baru', 'terjadwal','selesai dikerjakan', 'selesai']
createdAt
updateAt

4. Pelanggan
id
nama
kontak
alamat
team_id -> join team
createdAt
UpdateAt

5.Users
id
name
email
password
role ['administrator', 'admin_toko', 'admin_gudang', 'kepala_teknisi_lapangan', 'kepala_teknisi_gudang']
createdAt
UpdateAt

6. petukang
id
nama
status ['ready', 'busy']
kontak
team_id
createdAt
updateAt

7. team
id
nama
status ['ready', 'busy']
createdAt
updatedAt