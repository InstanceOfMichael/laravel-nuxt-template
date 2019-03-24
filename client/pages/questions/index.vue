<template lang="pug">
  .questions-list
    LengthAwarePaginator(
      :pagination="questions"
    )
    QuestionCardRow(
      v-for="question in questions.data"
      :key="question.id"
      :item="question"
    )
    LengthAwarePaginator(
      v-if="questions.data.length > 2"
      :pagination="questions"
    )
    br
    br
</template>

<script>
import QuestionCardRow from '~/components/Question/CardRow'

export default {
  components: {
    QuestionCardRow,
  },

  // these 2 lines are required for query+pagination to apply
  // scrollToTop: true,
  watchQuery: ['page'],
  key: (to) => to.fullPath,

  async asyncData ({ api, route }) {
    console.log('page', route.query.page || 1)
    return {
      questions: (await api.get('/questions', {
        params: {
          page: route.query.page || 1,
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.questions-list
  .card.card-question
    margin-bottom: 15px
</style>
