<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Todo List Laravel and Vue Js</title>
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- Vue JS -->
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- Axios -->
    <!-- Axios merupakan library opensource yang digunakan untuk request data melalui http.  -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- CSS -->
    <style>
        body {
            margin: 30px;
        }

        .todolist-wrapper {
            border: 1px solid #cccccc;
            min-height: 100px;
        }

        i {
            font-size: 13px;
            color: orange;
        }
        .search-input {
        box-shadow: none !important;
        /* border-color: #6c757d !important; */
        }
    </style>
</head>

<body>
    <h2 class="text-center">To Do List</h2>
    <!-- container bootstrap -->
    <div class="container">
        <!-- container vue js -->
        <div id="app">
            <!-- modal form tambah -->
            <div class="modal fade" id="modal-form">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">To Do List Form</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Content</label>
                                <textarea v-model="content" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- action ketika save todo -->
                            <button type="button" @click="saveTodoList" class="btn btn-primary">Save Todo</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <!-- button tambah -->
                    <div class="text-right mb-3 btn-sm">
                        <a href="javascript: ;" @click="openForm" class="btn btn-success">Tambah To Do List</a>
                    </div>
                    <div class="text-center mb-3">
                        <div class="input-group">
                            <!-- Onchange merupakan suatu event yang akan dijalankan ketika suatu field kita ubah -->
                            <input type="text" v-model="search" placeholder="Cari disini" @change="findData"
                                class="form-control search-input">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" @click="findData">Cari</button>
                            </div>
                        </div>
                    </div>
                    <!-- content -->
                    <div class="todolist-wrapper">
                        <table class="table table-striped table-bordered">
                            <tbody>
                                <!-- looping data_list -->
                                <tr v-for="item in data_list">
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>@{{ item.content }}</span>
                                            <div>
                                                <a href="javascript:;" @click="editData(item.id)"
                                                    class="btn btn-info btn-sm">Edit</a>
                                                <a href="javascript:;" @click="deleteData(item.id)"
                                                    class="btn btn-danger btn-sm">Hapus</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- menghitung panjang data_list dengan length -->
                                <!-- jika length data_list kosong atau nol -->
                                <!-- simbol (!) tanda seru yang artinya tidak false berarti sekarang nilainya true -->
                                <tr v-if="!data_list.length">
                                    <td>Data masih kosong</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <i>Created By: M. Faisal</i>
                </div>
                <div class="col-sm-3"></div>
            </div>
        </div>
    </div>
    <!-- Deklasikan Vue Js -->
    <script>
        var vue = new Vue({
            el: '#app',
            // parameter yang pertama di load
            mounted() {
                this.getDataList();
            },
            data: {
                data_list: [],
                content: "",
                id: "",
                search: ""
            },
            // method create
            methods: {
                openForm: function() {
                    this.content = "";
                    $('#modal-form').modal('show');
                },
                //search data button
                findData: function() {
                    this.getDataList();
                },
                // menampilkan data todolist di form edit
                editData: function(id) {
                    this.id = id;
                    axios.get(" {{ url('api/todolist/show') }}/" + this.id)
                        .then(response => {
                            var item = response.data;
                            this.content = item.content;
                            $('#modal-form').modal('show');
                        })
                        .catch(error => {
                            alert('Terjadi kesalahan pada sistem!');
                        })
                },
                deleteData: function(id) {
                    if (confirm('Apakah data ini akan dihapus?')) {
                        axios.get(" {{ url('api/todolist/delete') }}/" + id)
                            .then(response => {
                                alert(response.data.message);
                                this.getDataList();
                            })
                            .catch(error => {
                                alert('Terjadi kesalahan pada sistem!' + error);
                            })
                    }
                },
                // menambahkan data todolist
                // dan mengupdate data todolist
                saveTodoList: function() {
                    var form_data = new FormData();
                    form_data.append('content', this.content);
                    if (this.id) {
                        // update data saat tombol edit save diklik
                        axios.post(" {{ url('api/todolist/update') }}/" + this.id, form_data)
                            .then(response => {
                                var item = response.data;
                                alert(item.message);
                                this.getDataList();
                            })
                            .catch(error => {
                                alert('Terjadi kesalahan pada sistem');
                            })
                            // method yang terakhir dijalankan pada axios
                            .finally(() => {
                                $('#modal-form').modal('hide');
                            })
                    } else {
                        // create data
                        axios.post(" {{ url('api/todolist/create') }}", form_data)
                            .then(response => {
                                var item = response.data;
                                alert(item.message);
                                this.getDataList();
                            })
                            .catch(error => {
                                alert('Terjadi kesalahan pada sistem');
                            })
                            // method yang terakhir dijalankan pada axios
                            .finally(() => {
                                $('#modal-form').modal('hide');
                            })
                    }
                },
                // menampilkan data todolist
                getDataList: function() {
                    axios.get(" {{ url('api/todolist/list') }}?search=" + this.search)
                        .then(response => {
                            this.data_list = response.data;
                        })
                        .catch(error => {
                            alert("Terjadi kesalahan pada sistem");
                        })
                }
            }
        });
    </script>
</body>

</html>
