<template>
    <div class="container">
        <div class="col-12 mt-5 mb-4">
            <div class="row">
                <h1>Item</h1>
                <button class="btn btn-success ml-4 btn-sm" @click="tambahItem">
                    Tambah item
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">nama item</th>
                            <th scope="col">unit</th>
                            <th scope="col">stok</th>
                            <th scope="col">harga satuan</th>
                            <th scope="col">foto barang</th>
                            <th scope="col">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-show="modeTambah == 'item'">
                            <th></th>
                            <td>
                                <div class="form-group">
                                    <input v-model="form.item.nama" class="form-control" type="text" required />
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select class="custom-select" v-model="form.item.unit" required>
                                        <option value="pcs">pcs</option>
                                        <option value="kg">kg</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input v-model="form.item.stok" class="form-control" type="number"  required/>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input v-model="form.item.harga" class="form-control" type="number" required />
                                </div>
                            </td>
                            <td>
                                <div class="custom-file">
                                    <input
                                        type="file"
                                        class="custom-file-input"
                                        @change="previewFiles"
                                        ref="fotoBarang"
                                    />
                                    <label
                                        class="custom-file-label"
                                        for="customFile"
                                        >Choose file</label
                                    >
                                </div>
                            </td>
                            <td>
                                <button @click="createItem"  class="btn btn-success btn-sm">Simpan</button>
                                <button class="btn btn-danger btn-sm">Batal</button>
                            </td>
                        </tr>
                        <tr v-for="(row, index) in tabelItem" :key="index">
                            <th>{{ index }}</th>
                            <td>{{ row.nama }}</td>
                            <td>{{ row.unit }}</td>
                            <td>{{ row.stok }}</td>
                            <td>{{ row.harga }}</td>
                            <td>
                                <Button @click.prevent="lihatGambar(row.foto_barang)" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalLihat" >
                                    Lihat
                                </Button>
                            </td>
                            <td>
                                <Button
                                    class="btn btn-danger btn-sm"
                                    @click.prevent="
                                        deleteItem(row.id, index)
                                    "
                                    >Hapus</Button
                                >
                                <Button
                                    class="btn btn-warning btn-sm"
                                    @click.prevent="switchMode('edit', index)"
                                    >Edit</Button
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="modalLihat">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Foto barang</h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img :src="'../storage/'+lihat.substring(7,)" alt="foto_barang">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>

<script>
export default {
    mounted() {
        console.log("Component mounted.");
    },
    created() {
        axios.get("/item").then(response => (this.tabelItem = response.data));
    },
    data() {
        return {

            tabelItem: [],
            modeTambah: "",
            lihat:"",
            form:{
                item:{
                    nama:'',
                    unit:'',
                    stok:'',
                    harga:'',
                    foto_barang:'',
                }
            },
        };
    },
    methods: {
        tambahItem: function() {
            this.modeTambah = "item";
        },
        lihatGambar: function(gambar) {
            console.log(gambar);
            this.lihat = gambar;            
        },
        previewFiles: function(){
            this.form.item.foto_barang=this.$refs.fotoBarang.files[0]
        },
        clearItem(){
            this.modeTambah='';
            this.form.item.nama='';
            this.form.item.stok='';
            this.form.item.unit='';
            this.form.item.harga='';
            this.form.item.foto_barang='';
        },
        createItem: function(){
            let formdata = new FormData()
            formdata.append('nama', this.form.item.nama)
            formdata.append('unit', this.form.item.unit)
            formdata.append('stok', this.form.item.stok)
            formdata.append('harga', this.form.item.harga)
            formdata.append('foto_barang', this.form.item.foto_barang)
            console.log(formdata)
            axios.post("/item", formdata).then(response => {
                this.clearItem();
                this.tabelItem.push(response.data);

            })
        },
        deleteItem:function(id, index){
            axios.delete(`/item/${id}`).then(response => {
                if(response.status == 200){
                    this.tabelItem.splice(index,1);
                }
            })
        }
        
    }
};
// tambahPendidikan: function() {
//             this.pendidikan.push({
//                 id: "",
//                 nama_sekolah: "",
//                 jurusan: "",
//                 tahun_masuk: "",
//                 tahun_lulus: "",
//                 nomor_ktp: this.nomor_ktp
//             });
//         },
//         tambahExperience: function() {
//             this.experience.push({
//                 id: "",
//                 perusahaan: "",
//                 jabatan: "",
//                 tahun: "",
//                 keterangan: "",
//                 nomor_ktp: this.nomor_ktp
//             });
//         },
//         submitForm: function() {
//             let karyawan = {
//                 name: this.name,
//                 alamat: this.alamat,
//                 nomor_ktp: this.nomor_ktp
//             };
//             let pendidikan = {
//                 pendidikan: this.pendidikan
//             };
//             let experience = {
//                 experience: this.experience
//             };
//             if (this.modeEdit === false) {
//                 axios.post("/karyawan", karyawan).then(response => {
//                     if (response.status == 201 && this.pendidikan.length != 0) {
//                         axios
//                             .post("/pendidikan", pendidikan)
//                             .then(response => console.log());
//                     }
//                     if (response.status == 201 && this.experience != 0) {
//                         axios
//                             .post("/experience", experience)
//                             .then(response => console.log());
//                     }
//                     this.tabelKaryawan.push(response.data);
//                     this.clear();
//                 });
//             } else {
//                 axios
//                     .patch(`/karyawan/${this.nomor_ktp}`, karyawan)
//                     .then(response => {
//                         if (
//                             response.status == 200 &&
//                             this.pendidikan.length != 0
//                         ) {
//                             axios
//                                 .patch(
//                                     `/pendidikan/${this.editIndex}`,
//                                     pendidikan
//                                 )
//                                 .then(response => console.log());
//                         }
//                         if (response.status == 200 && this.experience != 0) {
//                             axios
//                                 .patch(
//                                     `/experience/${this.editIndex}`,
//                                     experience
//                                 )
//                                 .then(response => console.log());
//                         }
//                         this.tabelKaryawan.splice(this.editIndex, 1);
//                         this.tabelKaryawan.push(response.data);
//                         this.clear();
//                     });
//             }
//         },
//         hapus: function(type, index) {
//             let obj = type === "pendidikan" ? this.pendidikan : this.experience;
//             if (this.modeEdit && type === "pendidikan") {
//                 axios
//                     .delete(`/pendidikan/${this.pendidikan[index].id}`)
//                     .then(() => obj.splice(index, 1));
//             } else if (this.modeEdit && type === "experience") {
//                 axios
//                     .delete(`/experience/${this.experience[index].id}`)
//                     .then(() => obj.splice(index, 1));
//             }
//         },
//         deleteKaryawan: function(nomor_ktp, index) {
//             axios.delete(`/karyawan/${nomor_ktp}`).then(response => {
//                 if (response.status == 200) {
//                     this.tabelKaryawan.splice(index, 1);
//                 }
//             });
//         },
//         switchMode: function(mode, index) {
//             if (mode === "edit") {
//                 this.modeEdit = true;
//                 this.editIndex = index;
//             } else {
//                 this.modeDetail = true;
//             }
//             this.name = this.tabelKaryawan[index].name;
//             this.alamat = this.tabelKaryawan[index].alamat;
//             this.nomor_ktp = this.tabelKaryawan[index].nomor_ktp;
//             axios.get(`/karyawan/${this.nomor_ktp}`).then(response => {
//                 this.pendidikan = response.data.pendidikan;
//                 this.experience = response.data.experience;
//             });
//             document.body.scrollTop = 0;
//             document.documentElement.scrollTop = 0;
//         },
//         clear: function() {
//             this.name = "";
//             this.alamat = "";
//             this.nomor_ktp = "";
//             this.pendidikan = [];
//             this.experience = [];
//             this.modeEdit = false;
//             this.modeDetail = false;
//         },
</script>


