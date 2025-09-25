<template>
    <div class="col-12">
        <form id="createReviewForm">
            <div class="row">
                <div class="col-12">
                    <div class="alert" :class="{'alert-danger': error, 'alert-success': !error}" role="alert" v-if="messages.length">
                        <template v-for="message in messages">
                            {{ message }}
                            <br>
                        </template>
                    </div>
                </div>
                <div class="col-12 col-lg-8">
                    <input type="hidden"
                           name="user_id"
                           v-if="formData.user"
                           :value="formData.user">

                    <div class="my-3" v-if="! formData.user">
                        <label for="from" class="d-none">Ваше имя:</label>
                        <input type="text"
                               id="from"
                               name="from"
                               v-model="from"
                               placeholder="Ваше имя"
                               class="form-control mb-3">
                    </div>
                    <div class="my-3">
                        <label for="description" class="d-none">Ваш отзыв:</label>
                        <textarea type="text"
                                  v-model="description"
                                  name="description"
                                  rows="3"
                                  cols="4"
                                  id="description"
                                  placeholder="Комментарий"
                                  class="form-control mb-3">
                       </textarea>

                    </div>
                  <div class="my-3">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox"
                             class="custom-control-input" id="privacy_policy_reviews" required name="privacy_policy">
                      <label class="custom-control-label" for="privacy_policy_reviews">
                        Я даю <span v-if="company">{{ company }}</span>  свое
                        <a href="#agreementModal" data-bs-toggle="modal" data-bs-target="#agreementModal">Согласие на обработку персональных данных</a> и принимаю условия <a href="/policy" target="_blank">Политики по обработке персональных данных</a>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-lg-4 d-flex">
                    <div class="mb-3 ml-auto ml-lg-0 mt-auto">
                        <button type="button"
                                class="btn btn-primary"
                                :disabled="loading"
                                @click="inlineForm()">
                            Оставить отзыв <i class="fas fa-spinner fa-spin" v-if="loading"></i>
                        </button>
                    </div>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createReviewAnswerForm">
                            <div class="alert" :class="{'alert-danger': error, 'alert-success': !error}" role="alert" v-if="aMessages.length">
                                <template v-for="message in aMessages">
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
                                <label for="fromAnswer" class="sr-only">Имя</label>
                                <input type="text"
                                       id="fromAnswer"
                                       name="from"
                                       v-model="aFrom"
                                       placeholder="Имя"
                                       class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="descriptionAnswer" class="sr-only">Сообщение</label>
                                <textarea type="text"
                                       v-model="aDescription"
                                       name="description"
                                       rows="3"
                                       cols="4"
                                       id="descriptionAnswer"
                                       placeholder="Сообщение"
                                       class="form-control"></textarea>
                            </div>

                          <div class="form-group">
                            <div class="custom-control custom-checkbox">
                              <input type="checkbox"
                                     class="custom-control-input" id="privacy_policy_answer" required name="privacy_policy">
                              <label class="custom-control-label" for="privacy_policy_answer">
                                Я даю <span v-if="company">{{ company }}</span> свое
                                <a href="#agreementModal" data-bs-toggle="modal" data-bs-target="#agreementModal">Согласие на обработку персональных данных</a> и принимаю условия <a href="/policy" target="_blank">Политики по обработке персональных данных</a>
                              </label>
                            </div>
                          </div>


                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group-vertical"
                             role="group">
                            <button type="button"
                                    class="btn btn-primary"
                                    :disabled="loading"
                                    v-on:click="modalForm()">
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
        props: ['formData','company'],
        data() {
            return {
                messages: [],
                aMessages: [],
                description: "",
                from: "",
                aDescription: "",
                aFrom: "",
                loading: false,
                error: false,
                aError: "",
                modal: false,
                form: false,
            };
        },

        methods: {
            inlineForm() {
                this.modal = false;
                this.submitCreateForm('createReviewForm');
            },

            modalForm() {
                this.modal = true;
                this.submitCreateForm('createReviewAnswerForm');
            },

            submitCreateForm(id) {
                let form = document.getElementById(id);
                let formData = new FormData(form);
                this.loading = true;
                this.messages = [];
                this.aMessages = [];
                this.error = false;

                let action = formData.review ? this.formData.answer : this.formData.action;
                axios
                    .post(action, formData, {
                        responseType: 'json'
                    })
                    .then(response => {
                        let result = response.data;
                        this.messages.push(result.message);
                        $("#reviewAnswerCreate").modal('hide');
                    })
                    .catch(error => {
                        this.error = true;
                        let data = error.response.data;
                        for (error in data.errors) {
                            if (data.errors.hasOwnProperty(error)) {
                                if (this.modal) {
                                    this.aMessages.push(data.errors[error][0]);
                                }
                                else {
                                    this.messages.push(data.errors[error][0]);
                                }
                            }
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                        if (! this.error) {
                            this.from = null;
                            this.description = null;
                            this.aFrom = null;
                            this.aDescription = null;
                            this.$emit('new-review');
                        }
                    });
            }
        }
    }
</script>

<style scoped>


</style>
