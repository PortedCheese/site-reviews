<template>
    <div class="col-12">
        <form id="createReviewForm">
            <div class="alert" :class="{'alert-danger': error, 'alert-success': !error}" role="alert" v-if="messages.length">
                <template v-for="message in messages">
                    {{ message }}
                    <br>
                </template>
            </div>

            <input type="hidden"
                   name="user_id"
                   v-if="formData.user"
                   :value="formData.user">

            <div class="input-group input-group-lg">
                <input type="text"
                       id="from"
                       name="from"
                       v-model="from"
                       v-if="! formData.user"
                       placeholder="Имя"
                       class="form-control">

                <input type="text"
                       v-model="description"
                       name="description"
                       id="description"
                       placeholder="Сообщение"
                       class="form-control">

                <div class="input-group-append">
                    <button type="button"
                            class="btn btn-primary"
                            :disabled="loading"
                            v-on:click="submitCreateForm('createReviewForm')">
                        Оставить отзыв <i class="fas fa-spinner fa-spin" v-if="loading"></i>
                    </button>
                </div>
            </div>
        </form>

        <div class="modal fade-scale" id="reviewAnswerCreate" aria-labelledby="reviewAnswerCreateLabel" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewAnswerCreateLabel">
                            Добавить <span v-if="formData.review">ответ</span><span v-else="formData.review">отзыв</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="createReviewAnswerForm">
                            <div class="alert" :class="{'alert-danger': error, 'alert-success': !error}" role="alert" v-if="messages.length">
                                <template v-for="message in messages">
                                    {{ message }}
                                    <br>
                                </template>
                            </div>

                            <input type="hidden" name="review_id"
                                   :value="formData.review"
                                   v-if="formData.review">

                            <input type="hidden"
                                   name="user_id"
                                   v-if="formData.user"
                                   :value="formData.user">
                            <div class="form-group" v-else>
                                <input type="text"
                                       id="fromAnswer"
                                       name="from"
                                       v-model="from"
                                       placeholder="Имя"
                                       class="form-control form-control-lg">
                            </div>

                            <div class="form-group">
                                <input type="text"
                                       v-model="description"
                                       name="description"
                                       id="descriptionAnswer"
                                       placeholder="Сообщение"
                                       class="form-control form-control-lg">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group-vertical btn-block"
                             role="group">
                            <button type="button"
                                    class="btn btn-primary btn-lg"
                                    :disabled="loading"
                                    v-on:click="submitCreateForm('createReviewAnswerForm')">
                                Оставить отзыв <i class="fas fa-spinner fa-spin" v-if="loading"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['formData'],
        data() {
            return {
                messages: [],
                description: null,
                from: null,
                loading: false,
                error: false
            };
        },
        methods: {
            submitCreateForm(id) {
                let form = document.getElementById(id);
                let formData = new FormData(form);
                this.loading = true;
                this.messages = [];
                this.error = false;

                let action = formData.review ? this.formData.answer : this.formData.action;
                axios
                    .post(action, formData, {
                        responseType: 'json'
                    })
                    .then(response => {
                        let result = response.data;
                        this.messages.push(result.message);
                    })
                    .catch(error => {
                        this.error = true;
                        let data = error.response.data;
                        for (error in data.errors) {
                            this.messages.push(data.errors[error][0]);
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                        if (!this.error) {
                            this.from = null;
                            this.description = null;
                            this.$emit('new-review');
                        }
                    });
            }
        }
    }
</script>

<style scoped>

</style>