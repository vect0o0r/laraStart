<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Users</h3>

            <div class="card-tools">
                <button type="button"
                        class="btn btn-primary"
                        data-toggle="modal"
                        data-target="#exampleModalCenter">
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
                    <th>Modify</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>#</td>
                    <td>#</td>
                    <td>#</td>
                    <td>#</td>
                    <td class="project-actions ">
                        <a class="btn btn-info btn-sm"
                           href="#">
                            <i class="fas fa-pencil-alt  "></i>
                        </a>
                        <a class="btn btn-danger btn-sm"
                           href="#">
                            <i class="fas fa-trash "></i>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="modal fade"
             id="exampleModalCenter"
             tabindex="-1"
             role="dialog"
             aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"
                 role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="exampleModalLongTitle">Add New User</h5>
                        <button type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="login"
                              @keydown="form.onKeydown($event)">
                            <div class="form-group">
                                <label>Username</label>
                                <input v-model="form.name"
                                       type="text"
                                       name="name"
                                       class="form-control"
                                       :class="{ 'is-invalid' : form.errors.has('name') }">
                                <has-error :form="form"
                                           field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <label>Email Address</label>
                                <input v-model="form.email"
                                       type="text"
                                       name="email"
                                       class="form-control"
                                       :class="{ 'is-invalid' : form.errors.has('email') }">
                                <has-error :form="form"
                                           field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <label>User type</label>
                                <select name="type"
                                        id="type"
                                        v-model="form.type"
                                        class="form-control"
                                        :class="{ 'is-invalid' : form.errors.has('type') }">
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Bio</label>
                                <textarea class="form-control"
                                          id="bio"
                                          v-model="form.bio"
                                          :class="{ 'is-invalid' : form.errors.has('bio') }"
                                          rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input v-model="form.password"
                                       type="password"
                                       name="password"
                                       class="form-control"
                                       :class="{ 'is-invalid' : form.errors.has('password') }">
                                <has-error :form="form"
                                           field="password"></has-error>
                            </div>

                            <button :disabled="form.busy"
                                    type="submit"
                                    class="btn btn-primary">Log In
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-danger"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button"
                                class="btn btn-primary">Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                    remember: false
                })
            };
        },
        mounted() {
            console.log('Component mounted.');
        },
        methods: {
            login() {
                // Submit the form via a POST request
                this.form.post('/login')
                    .then(({data}) => {
                        console.log(data);
                    });
            }
        }
    };
</script>
