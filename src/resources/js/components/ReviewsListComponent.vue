<template>
    <div class="row justify-content-center mt-4" id="review-list">
        <div class="col-12">
            <div v-for="item in reviews" class="review-cover mt-4">
                <div v-html="item.html"></div>
                <div class="row">
                    <div class="col-12 text-right mt-3">
                        <button type="button"
                                v-on:click="showAnswer(item.review)"
                                class="btn btn-outline-primary"
                                data-toggle="modal"
                                data-target="#reviewAnswerCreate">
                            Ответить
                        </button>
                    </div>
                    <div class="col-10 offset-2 mt-3">
                        <template v-for="answer in item.answers">
                            <div v-html="answer"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3" v-if="lastPage > 1">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
                        <button class="page-link" v-on:click="changePage(false)">
                            <span aria-hidden="true">&laquo;</span>
                        </button>
                    </li>
                    <li class="page-item" :class="{ 'disabled': currentPage === lastPage }">
                        <button class="page-link" v-on:click="changePage(true)">
                            <span aria-hidden="true">&raquo;</span>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['getUrl'],
        data() {
            return {
                reviews: [],
                currentPage: null,
                lastPage: null,
            };
        },
        methods: {
            showAnswer(review) {
                this.$emit('show-review', review.id);
            },

            changePage(direction) {
                if (direction) {
                    this.currentPage++;
                }
                else {
                    this.currentPage--;
                }
                this.getPageReviews();
            },

            getPageReviews() {
                this.$emit('begin-load');
                axios.get(this.getUrl + "?page=" + this.currentPage)
                    .then(response => {
                        let result = response.data;
                        this.reviews = result['rendered'];
                        $([document.documentElement, document.body]).animate({
                            scrollTop: $("#review-list").offset().top
                        }, 1000);
                        this.$emit('end-load');
                    })
            },

            initReviews() {
                this.$emit('begin-load');
                axios.get(this.getUrl)
                    .then(response => {
                        let result = response.data;
                        this.reviews = result['rendered'];
                        let pager = result['pagesInfo'];
                        this.currentPage = pager['current_page'];
                        this.lastPage = pager['last_page'];
                        this.$emit('end-load');
                    })
            }
        },
        created() {
            this.initReviews();
        }
    }
</script>

<style scoped>

</style>
