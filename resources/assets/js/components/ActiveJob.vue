<template>
    <div class="card" v-show="job">
        <div class="card-header">
            <span v-html="label"></span> {{job.title}} [{{job.command}}: {{job.id}}]
            <a class="float-right" @click="remove(job.id)" v-show="single !== 1">
                <i class="fa fa-times-circle-o" aria-hidden="true"></i>
            </a>
        </div>
        <div class="card-body">
            <div v-show="job.payload">
                <div class="row">
                    <div class="col-6">
                        <b>Options:</b>
                        <ul>
                            <li v-for="(val, param) in job.payload">
                                <b>{{param}}</b>: {{val}}
                            </li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <p><b>Attempts:</b> {{job.attempts}}</p>
                        <p><b>Created At:</b> {{job.created_at}}</p>
                    </div>
                </div>

            </div>
            <div>
                <div class="progress">
                    <div class="progress-bar" :class="barClass"
                         role="progressbar" :style="barStyle" v-bind:property="job.progress">
                        {{ job.progress }}
                    </div>
                </div>
                <div class="log" v-show="logs.length">
                    <ul class="list-group">
                        <li class="list-group-item" v-for="log in logs">
                            {{ log }}
                        </li>
                    </ul>
                </div>
            </div>
            <div v-show="isJobFinished && job.report">
                <div v-show="job.report">
                    <b>Report:</b>
                    <p>{{job.report}}</p>
                </div>
                <a :href="'/logs/'+job.name+'/'+job.id" class="btn btn-flat btn-info">
                    See Execution Logs
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            job: {},
            isSingle: 1
        },
        data() {
            return {
                logs: [],
                labels: {
                    'success': '<div class="badge badge-success">SUCCESS</div>',
                    'fail': '<div class="badge badge-danger">FAIL</div>',
                    'retry': '<div class="badge badge-dark">RETRY</div>',
                    'pending': '<div class="badge badge-primary">PENDING</div>',
                    'declined': '<div class="badge badge-warning">DECLINED</div>',
                    'processing': '<div class="badge badge-info">PROCESSING</div>'
                },
                bar: {
                    'success': 'bg-success',
                    'fail': 'bg-danger',
                    'retry': 'bg-warning',
                    'pending': 'bg-primary',
                    'declined': 'bg-dark',
                    'processing': 'bg-info'
                }
            }
        },
        computed: {
            barStyle() {
                if (this.job.state === 'success') {
                    this.job.progress = 100;
                }
                return {'width': this.job.progress + '%'};
            },
            barClass() {
                let barClass = this.bar[this.job.state];
                if(this.state==='processing'){
                    barClass += ' progress-bar-stripped progress-bar-animated'
                }
                return barClass;
            },
            label() {
                return this.labels[this.job.state] || this.labels.pending;
            }
        },
        methods: {
            remove(id){
                this.$echo.leave(`Job.${this.job.id}`);
                this.$nextTick(function () {
                    this.$emit('removeItem', id);
                });
            },
            addLog(text) {
                this.logs.push(text);
            },
            isJobFinished() {
                return ['success', 'fail'].includes(this.job.state);
            },
            updateJob(job){
                this.job.state = job.state;
                this.job.report = job.report;
                this.job.progress = job.progress;
                this.job.attempts = job.attempts;
            },

            jobSubscribe() {
                this.$echo.private(`Job.${this.job.id}`).notification((notification) => {
                    if (notification.message_type === 'progress') {
                        this.job.progress = notification.message;
                    } else if (notification.message_type === 'state') {
                        this.updateJob(JSON.parse(notification.job));
                    } else {
                        this.logs.unshift(notification.message);
                    }
                });
            }
        },
        mounted() {
            if(typeof this.job === 'string'){
                this.job = JSON.parse(this.job);
            }
            console.log('Component mounted.');
            if (!this.isJobFinished()) {
                this.jobSubscribe();
            }
        }
    }
</script>

<style>
    .log {
        height: 200px;
        max-height: 200px;
        overflow-y: auto;
    }
</style>