<template>
    <div class="row" id="reviews-component">
        <div class="preloader text-center" v-if="loading">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
        </div>
        <div class="col-12">
            <button type="button"
                    v-on:click="showReview"
                    class="btn btn-primary"
                    data-toggle="modal"
                    data-target="#reviewCreate">
                Добавить отзыв
            </button>
        </div>
        <reviews-form :form-data="formData"
                      v-on:new-review="updateReviews"
                      ref="childForms">
        </reviews-form>
        <div class="col-12">
            <reviews-list :get-url="getUrl"
                          ref="childList"
                          v-on:show-review="showAnswer($event)"
                          v-on:begin-load="loading = true"
                          v-on:end-load="loading = false">
            </reviews-list>
        </div>
    </div>
</template>

<script>
    import ReviewsFormComponent from './ReviewsFormComponent'
    import ReviewsListComponent from './ReviewsListComponent'

    export default {
        components: {
            'reviews-form': ReviewsFormComponent,
            'reviews-list': ReviewsListComponent
        },
        props: ['formAction', 'userAuth', 'getUrl', 'answerAction'],
        data() {
            return {
                formData: {},
                loading: true
            }
        },
        methods: {
            showReview() {
                this.formData.review = false;
            },

            showAnswer(id) {
                this.formData.review = id;
            },

            updateReviews() {
                this.$refs.childList.initReviews();
            }
        },
        created() {
            this.formData = {
                action: this.formAction,
                answer: this.answerAction,
                user: this.userAuth,
                review: false
            };
        }
    }
</script>

<style scoped>
    #reviews-component {
        position: relative;
        min-height: 150px;
    }
    #reviews-component .preloader {
        position: absolute;
        width: 100%;
        height: 100%;
        z-index: 10;
    }
    #reviews-component .preloader i {
        position: fixed;
        top: 45%;
    }
</style>