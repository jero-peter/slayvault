<template>
    <div class="col-12 row-fluid">
        <div class="container row mx-auto">
            <div class="col-6 mx-auto my-3">
                <h4 class="text-white my-3 text-center">App Manager</h4>
                <ul class="list-unstyled text-white">
                    <li v-for="subscription in subscriptions" :key="subscription.id" class="text-white">{{subscription.name}} : <span class="float-end text-success">Active</span></li>
                    <li v-for="application in applications" :key="application.id" class="text-white">{{application.name}} : <span class="float-end text-danger">Inactive</span></li>
                </ul>
            </div>
            <div class="col-6 mx-auto my-3" v-if="applications.length >= 1">
                <h4 class="text-white my-3 text-center">Subscription Manager</h4>
                <ul class="list-unstyled text-white">
                    <li v-for="subscription in subscriptions" :key="subscription.id" class="text-white">
                        <div class="form-group">
                            <label class="form-check-label" :for="`${subscription.name}SubSwitch`">{{subscription.name}}</label>
                        </div>
                    </li>
                    <li v-for="application in applications" :key="application.id" class="text-white">
                        <div class="form-group">
                            <label class="form-label" :for="`${application.name}AppSwitch`">{{application.name}}</label>
                            <button class="btn btn-success float-end" type="button" :id="`${application.name}AppSwitch`" @click.prevent="addAppToSubscription(application)">Subscribe</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-6 text-center row mx-auto">
            <h4 class="text-white my-3">Apps</h4>
            <a v-for="subscription in subscriptions" :key="subscription.id" :href="`/home/list/${subscription.subdomain}`" class="text-decoration-none bg-dark card col-4 img-thumbnail">
                <img :style="`height:120px;background: url('/img/${subscription.subdomain}.png');background-position:25% 50%;`">
                <p class="text-white">{{subscription.name}}</p>
            </a>
        </div>
    </div>
</template>

<script>

export default {
    props : ['user', 'apps'],
    data() {
        return  {
            userData  : JSON.parse(this.user),
            subscriptions : [],
            applications : [],
            allApplications : JSON.parse(this.apps),
            appIdArray : ''
        }
    },
    created(){
        this.appIdArray = this.userData.subscription_list;
        this.subscriptions = this.allApplications.filter(app => { if(this.appIdArray.includes(app.id)){ return app; } });
        this.applications = this.allApplications.filter(app => { if(!this.appIdArray.includes(app.id)){ return app; } });
    },
    methods : {
        addAppToSubscription(addedApplication){
            this.appIdArray = [...this.appIdArray, addedApplication.id];
            this.applications = this.applications.filter(application => {application.id != addedApplication.id});
            this.subscriptions = [...this.subscriptions, addedApplication];

            axios.post('/add-subscription',  this.subscriptions)
            .then(returnWrapper => {
                this.userData = returnWrapper.data.user;
            });
        }
    }
}
</script>