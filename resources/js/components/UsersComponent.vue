<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Users</h3>

            <div class="card-tools">
                <button type="button"
                        class="btn btn-primary"
                        @click="createModel"
                        data-toggle="modal"
                        data-target="#createModal">
                    Add New <i class="fas fa-user-plus"></i></button>
                <button type="button"
                        class="btn btn-tool"
                        data-card-widget="collapse"
                        data-toggle="tooltip"
                        title="Collapse">
                    <i class="fas fa-minus"></i></button>
                <!--                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">-->
                <!--                        <i class="fas fa-times"></i></button>-->
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Registed At</th>
                    <th>Modify</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users.data" :key="user.id">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | upperText}}</td>
                    <td>{{user.created_at | onlyDate}}</td>
                    <td class="project-actions ">
                        <a class="btn btn-info btn-sm"
                           data-toggle="modal"
                           data-target="#createModal"
                           @click="editUser(user)"
                           href="javascript:void(0)">
                            <i class="fas fa-pencil-alt  "></i>
                        </a>
                        <a class="btn btn-danger btn-sm"
                           @click="deleteUser(user.id)"
                           href="javascript:void(0)">
                            <i class="fas fa-trash "></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="modal fade"
             id="createModal"
             tabindex="-1"
             role="dialog"
             aria-labelledby="createModal"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"
                 role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editMode">Add New User</h5>
                        <h5 class="modal-title" v-show="editMode">Update User Info</h5>
                        <button type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? updateUser() : createUser()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">

                            <div class="form-group">
                                <label></label>
                                <input v-model="form.name"
                                       type="text"
                                       name="name"
                                       class="form-control"
                                       placeholder="Type Username"
                                       :class="{ 'is-invalid' : form.errors.has('name') }">
                                <has-error :form="form"
                                           field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.email"
                                       type="text"
                                       name="email"
                                       placeholder="Type Email Address"
                                       class="form-control"
                                       :class="{ 'is-invalid' : form.errors.has('email') }">
                                <has-error :form="form"
                                           field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <select name="type"
                                        id="type"
                                        v-model="form.type"
                                        class="form-control"
                                        :class="{ 'is-invalid' : form.errors.has('type') }">
                                    <option selected value="">Select User Role</option>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="author">Author</option>
                                </select>

                            </div>
                            <div class="form-group">
                                <textarea class="form-control"
                                          id="bio"
                                          placeholder="Type User Bio"
                                          v-model="form.bio"
                                          :class="{ 'is-invalid' : form.errors.has('bio') }"
                                          rows="3"></textarea>
                                <has-error :form="form"
                                           field="bio"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.password"
                                       type="password"
                                       name="password"
                                       placeholder="Type User Password"
                                       class="form-control"
                                       :class="{ 'is-invalid' : form.errors.has('password') }">
                                <has-error :form="form"
                                           field="password"></has-error>
                            </div>


                        </div>
                        <div class="modal-footer">

                            <button :disabled="form.busy"
                                    v-show="!editMode"
                                    type="submit"
                                    class="btn btn-primary">Create
                            </button>
                            <button :disabled="form.busy"
                                    v-show="editMode"
                                    type="submit"
                                    class="btn btn-success">Update
                            </button>
                            <button type="button"
                                    class="btn btn-danger"
                                    data-dismiss="modal">Close
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <pagination :data="users" @pagination-change-page="getResults"></pagination>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                users: {},
                editMode: true,
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                })
            };
        },
        methods: {
            getResults(page = 1) {
                axios.get('api/user?page=' + page)
                    .then(response => {
                        this.users = response.data;
                    });
            },
            loadUsers() {
                axios.get("api/user").then(({data}) => (this.users = data));
            },
            createUser() {
                // Submit the form via a POST request
                this.$Progress.start();
                this.form.post('/api/user')
                    .then(() => {
                        Toast.fire({
                            icon: 'success',
                            title: 'User Created  successfully'
                        });
                        Fire.$emit('AfterCreated');
                        $('#createModal').modal('hide');
                        this.$Progress.finish;
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });

            },
            deleteUser(id) {
                // Submit the form via a POST request
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        this.form.delete('/api/user/' + id)
                            .then(() => {
                                Fire.$emit('AfterCreated');
                                Swal.fire(
                                    'Deleted!',
                                    'User has been deleted.',
                                    'success'
                                );
                                this.$Progress.finish;

                            })
                            .catch(() => {
                                this.$Progress.fail();
                            });

                    }
                })


            },
            createModel() {
                this.editMode = false;
                this.form.reset();
            },
            editUser(user) {
                this.editMode = true;
                this.form.reset();
                this.form.fill(user)
            }, updateUser() {
                this.$Progress.start();
                this.form.put('/api/user/' + this.form.id)
                    .then(() => {

                        Toast.fire({
                            icon: 'success',
                            title: 'User Updated successfully'
                        });
                        Fire.$emit('AfterCreated');
                        $('#createModal').modal('hide');
                        this.$Progress.finish;
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
            }
        },
        created() {
            this.loadUsers();
            Fire.$on('AfterCreated', () => {
                this.loadUsers();
            });
            Fire.$on('searching', () => {
                let query = this.$parent.search;
                axios.get('api/findUser?q=' + query)
                    .then((data) => {
                        this.users = data.data;
                        this.$Progress.finish;
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    })
            });
            // setInterval(() =>  this.loadUsers(), 5000)
        },
    };
</script>
