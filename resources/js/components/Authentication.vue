<template>
    <div class="row mx-auto container">
        <h4 class="text-white">Account Settings <a class="float-end btn text-white" @click.prevent="showNewUserForm = !showNewUserForm;">Add User &plus;</a></h4>
        <hr class="bg-white">
        <div class="row col-4">
            <div class="card bg-dark text-white border-secondary mx-auto mb-3">
                <h5 class="card-header border-secondary py-2">Active User Data</h5>
                <div class="card-body">
                    <p class="text-white">Name : <span class="float-end">{{activeUser.name}}</span></p>
                    <p class="text-white">Email : <span class="float-end">{{activeUser.email}}</span></p>
                    <p class="text-white">User Type : <span class="float-end">{{activeUser.user_type}}</span></p>
                </div>
            </div>
            <div class="card bg-dark text-white border-secondary mx-auto">
                <h5 class="card-header border-secondary py-2">Manage SSO</h5>
                <div class="card-body">
                    <button @click.prevent="unAuthorizeApps" class="btn btn-success text-white mx-auto mb-2 form-control" :disabled="actionDone==false">&#128274; unAuthorize Apps</button>
                    <button @click.prevent="showLogoutEverywhereForm" class="btn btn-success text-white mx-auto mb-2 form-control" :disabled="actionDone==false">&#128679; Logout Everywhere</button>
                    <div :class="[showLogoutForm !== true ? 'd-none' : '']">
                    <form @submit.prevent="logoutEverywhere" class="row row-fluid">
                        <div class="col-9">
                            <input type="password" v-model="logoutForm.currentPass" class="form-control" placeholder="Current Password">
                        </div>
                        <div class="col-2">
                            <button class="btn btn-success">&#x25BA;</button>
                        </div>
                        <div class="col-12" :class="[logoutStatus.status == false ? 'd-none' : '']">
                            <small :class="[logoutStatus.type == 'failed' ? 'text-danger' : 'text-success']">{{logoutStatus.message}}</small>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card bg-dark col-8 text-white border-secondary ms-auto">
            <h5 class="card-header border-secondary py-2">Reset Password</h5>
            <form @submit.prevent="requestPasswordChange">
                <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-auto">
                                <label class="col-form-label">Current Password</label>
                            </div>
                            <div class="col-auto w-100">
                                <input class="form-control" type="password" v-model="passwordChangeForm.currentPass" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-auto">
                                <label class="col-form-label">New Password</label>
                            </div>
                            <div class="col-auto w-100">
                                <input class="form-control" type="password" v-model="passwordChangeForm.newPass" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-auto">
                                <label class="col-form-label">Confirm Password</label>
                            </div>
                            <div class="col-auto w-100">
                                <input class="form-control" type="password" v-model="confirmPass" required>
                            </div>
                        </div>
                </div>
                <div class="card-footer border-secondary">
                    <button type="submit" :disabled="actionDone==false" class="my-2 btn btn-success text-white w-25">{{withoutError == true ? '&#10004;' : 'Confirm'}}</button>
                      <p :class="[ passError == true ? 'text-danger float-end my-2' : 'd-none']"><small class="text-danger">{{error.text}}</small></p>
                </div>
            </form>
        </div>
        <div class="my-3 card bg-dark col-12 text-white border-secondary mx-auto" v-if="showNewUserForm == true">
            <h5 class="card-header border-secondary d-inline py-2">Add More Users <a class="float-end d-inline text-white" style="text-decoration:none;cursor:pointer" @click.prevent="showNewUserForm = false;">&times;</a> </h5>
            <form @submit.prevent="addNewAdministrator">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-auto">
                            <label class="col-form-label">Current Password</label>
                        </div>
                        <div class="col-auto w-100">
                            <input class="form-control" type="password" v-model="newUserForm.currentPass" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-auto">
                            <label class="col-form-label">Name</label>
                        </div>
                        <div class="col-auto w-100">
                            <input class="form-control" type="text" v-model="newUserForm.name" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-auto">
                            <label class="col-form-label">Email</label>
                        </div>
                        <div class="col-auto w-100">
                            <input class="form-control" type="text" v-model="newUserForm.email" required>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-auto">
                            <label class="col-form-label">Password</label>
                        </div>
                        <div class="col-auto w-100">
                            <input class="form-control" type="password" v-model="newUserForm.password" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-secondary">
                    <button type="submit" :disabled="actionDone==false" class="my-2 btn btn-success text-white w-25">{{withoutError == true ? '&#10004;' : 'Confirm'}}</button>
                      <p :class="[ userPassError == true ? 'text-danger float-end my-2' : 'd-none']"><small class="text-danger">{{error.text}}</small></p>
                </div>
            </form>
        </div>
        <div class="my-3 card bg-dark col-12 text-white border-secondary mx-auto" v-if="admins.length > 0">
            <h5 class="card-header text-white py-2 border-secondary">Admin List</h5>
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Type</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(admin,index) in admins" :key="admin.id">
                        <th>{{index + 1}}</th>
                        <td>{{admin.name}}</td>
                        <td>{{admin.email}}</td>
                        <td>@{{admin.user_type}}</td>
                        <td><a class="btn btn-danger text-white" @click.prevent="removeSelectedAdministrator(admin)">Remove</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="my-3 card bg-dark col-12 text-white border-secondary mx-auto" v-if="clients.length > 0">
            <h5 class="card-header text-white py-2 border-secondary">Client List</h5>
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">UUID</th>
                    <th scope="col">Type</th>
                    <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(client,index) in clients" :key="client.id">
                        <th>{{index + 1}}</th>
                        <td>{{client.name}}</td>
                        <td>{{client.uuid}}</td>
                        <td>@{{client.user_type}}</td>
                        <td><a class="btn btn-danger text-white" @click.prevent="removeSelectedAdministrator(client)">Remove</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
let errorInterval;
let requestEncrypt;

export default {
    props : ['user', 'secondary_users', 'guest_users'],
    data() {
        return  {
            admins : JSON.parse(this.secondary_users),
            clients : JSON.parse(this.guest_users),
            error: {
                text : ''
            },
            actionDone  : true,
            activeUser : JSON.parse(this.user),
            passwordChangeForm : {
                currentPass : '',
                newPass : '',
            },
            logoutForm : {
                currentPass : ''
            },
            confirmPass : '',
            userPassError : false,
            passError : false,
            withoutError : false,
            showLogoutForm : false,
            showNewUserForm : false,
            logoutStatus : {
                status : false,
                type : '',
                message : '',
            },
            newUserForm : {
                name : '',
                email : '',
                password : '',
                currentPass : ''
            }
        }
    },
    created() {
        requestEncrypt = btoa(this.activeUser.company);
    },
    methods : {
        requestPasswordChange() {
            clearTimeout(errorInterval);

            if(this.confirmPass === this.passwordChangeForm.newPass){
                if(this.passwordChangeForm.newPass !== this.passwordChangeForm.currentPass){
                    this.passError = false;
                    this.actionDone = false;
                    axios.post('/request-password-change', this.passwordChangeForm)
                    .then(returnWrapper => {
                        if(returnWrapper){
                            if(returnWrapper.data.error){
                                this.error.text = returnWrapper.data.error;
                                this.passError = true;
                                errorInterval = setTimeout(()=>{
                                    this.passError = false;
                                    this.actionDone = true;
                                },1000)
                            }
                            else if(returnWrapper.data.success){
                                this.withoutError = true;
                                setTimeout(()=>{
                                    this.actionDone = true;
                                    this.withoutError = false;
                                    this.passwordChangeForm = {
                                        currentPass : '',
                                        newPass : '',
                                    };
                                    this.confirmPass = '';
                                },1000);
                            }
                        }
                    });
                }else{
                    this.error.text = 'Can\'t have matching current and new passwords';
                    this.passError = true;
                    errorInterval = setTimeout(()=>{
                        this.passError = false;
                    },1000)
                }
            }else{
                this.error.text = 'Passwords do not match!';
                this.passError = true;
                errorInterval = setTimeout(()=>{
                    this.passError = false;
                },1000)
            }
        },
        unAuthorizeApps() { 
            let mylink = 'saml/logout'
            this.actionDone = false;
            if (!window.focus)return true;
            var href;
            if (typeof(mylink) == 'string') href=mylink;
            else href=mylink.href; 
            let steve = window.open(href, 'steve', 'width=1px,height=1px,scrollbars=no,left=999999'); 
            steve.blur();
            setTimeout(()=>{
                steve.close();
                this.actionDone = true;
                return false; 
            },1000);

        },
        showLogoutEverywhereForm(){
            this.showLogoutForm = !this.showLogoutForm;
        },
        logoutEverywhere(){
            this.actionDone = false;
            axios.post('/request-logout-everywhere', this.logoutForm)
            .then(returnWrapper => {
                /**
                 * Not much use for the reply
                 */
                if(returnWrapper){
                    if(returnWrapper.data.error){
                        this.logoutStatus.status = true;
                        this.logoutStatus.message = returnWrapper.data.error;
                        this.logoutStatus.type = 'failed';
                        setTimeout(()=>{
                            this.logoutForm.currentPass = '';
                            this.logoutStatus = {
                                status : false,
                                type : '',
                                message : '',
                            };
                            this.actionDone = true;
                        },1000);
                    }
                    else if(returnWrapper.data.success){
                        this.logoutStatus.status = true;
                        this.logoutStatus.message = returnWrapper.data.success;
                        this.logoutStatus.type = 'success';
                        setTimeout(()=>{
                            this.logoutForm.currentPass = '';
                            this.logoutStatus = {
                                status : false,
                                type : '',
                                message : '',
                            };
                            this.actionDone = true;
                            this.showLogoutForm = false;
                        },1000);
                    }
                }
            });
        },
        addNewAdministrator(){
            this.actionDone = false;
            axios.post('/add-secondary-user', this.newUserForm)
            .then(returnWrapper => {
                if(returnWrapper){
                            if(returnWrapper.data.error){
                                this.error.text = returnWrapper.data.error;
                                this.userPassError = true;
                                errorInterval = setTimeout(()=>{
                                    this.userPassError = false;
                                    this.actionDone = true;
                                },1000)
                            }
                            else if(returnWrapper.data.success){
                                this.withoutError = true;
                                this.admins = returnWrapper.data.updatedUserList;
                                setTimeout(()=>{
                                    this.actionDone = true;
                                    this.withoutError = false;
                                    this.newUserForm = {
                                        name : '',
                                        email : '',
                                        password : '',
                                        currentPass : ''
                                    };
                                },1000);
                            }
                        }
                
            });
        },
        removeSelectedAdministrator(selectedAdmin){
            this.actionDone = false;
            axios.delete(`/remove-secondary-user/${selectedAdmin.id}/${requestEncrypt}`)
            .then(returnWrapper => {
                if(returnWrapper.data.success){
                    this.actionDone = true;
                    this.admins = this.admins.filter(admin => { return admin.id !== selectedAdmin.id; });
                }
            });
        }
    }
}
</script>