<template>
    <div>
        <div class="modal" id="reviewCreate" aria-labelledby="reviewCreateLabel" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewCreateLabel">
                            Добавить <span v-if="formData.review">ответ</span><span v-else="formData.review">отзыв</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="createReviewForm">
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
                                <label for="from">Ваше имя</label>
                                <input type="text"
                                       id="from"
                                       name="from"
                                       v-model="from"
                                       required
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="description">Текст отзыва</label>
                                <textarea class="form-control" v-model="description" name="description" id="description" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">
                            Закрыть
                        </button>
                        <button type="button"
                                class="btn btn-primary"
                                :disabled="loading"
                                v-on:click="submitCreateForm()">
                            Отправить <i class="fas fa-spinner fa-spin" v-if="loading"></i>
                        </button>
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
            submitCreateForm() {
                let form = document.getElementById('createReviewForm');
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