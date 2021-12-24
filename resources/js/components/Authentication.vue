<template>
    <div class="row mx-auto container">
        <h4 class="text-white">Account Settings</h4>
        <hr class="bg-white">
        <div class="row col-3">
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
        <div class="card bg-dark col-7 text-white border-secondary mx-auto">
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
    </div>
</template>

<script>
let errorInterval;

export default {
    props : ['user'],
    data() {
        return  {
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
            passError : false,
            withoutError : false,
            showLogoutForm : false,
            logoutStatus : {
                status : false,
                type : '',
                message : '',
            }
        }
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
                        /**
                         * Not much use for the reply
                         */
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
            let steve = window.open(href, 'steve', 'width=1px,height=1px,scrollbars=no'); 
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
        }
    }
}
</script>