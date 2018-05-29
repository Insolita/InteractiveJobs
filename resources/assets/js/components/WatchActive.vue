<template>
    <div>
        <active-job v-for="job in jobs" :key="job.id" :job="job" :single="0" @removeItem="remove"/>
    </div>
</template>

<script>
    import ActiveJob from './ActiveJob';
    export default {
        components:{ActiveJob},
        props:['list'],
        data(){
            return {
                jobs:[]
            }
        },
        mounted(){
            this.jobs = JSON.parse(this.list);
            this.$nextTick(function () {
                if(this.$user){
                    this.monitorSubscribe();
                }
            });
        },
        methods:{
            addJob(job){
                this.jobs.unshift(job);
            },
            remove(jobid){
                let index = this.jobs.findIndex(el => el.id == jobid);
                if(index >=0){
                    this.jobs.splice(index, 1);
                }
            },
            monitorSubscribe() {
                console.log('start watch!');
                this.$echo.private(`JobsMonitor.${this.$user.id}`).notification((notification) => {
                    if(notification.job){
                        this.addJob(JSON.parse(notification.job))
                    }
                });
            }
        }
    }
</script>

