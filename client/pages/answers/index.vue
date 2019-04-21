<template lang="pug">
  .answers-list
    LengthAwarePaginator(
      :pagination="answers"
    )
    AnswerCardRow(
      v-for="answer in answers.data"
      :key="answer.id"
      :item="answer"
    )
    LengthAwarePaginator(
      v-if="answers.data.length > 2"
      :pagination="answers"
    )
    .empty-page.text-center(v-else-if="answers.data.length === 0") No answers
    br
    br
</template>

<script>
import AnswerCardRow from '~/components/Answer/CardRow'

export default {
  components: {
    AnswerCardRow,
  },

  // these 2 lines are required for query+pagination to apply
  // scrollToTop: true,
  watchQuery: ['page'],
  key: (to) => to.fullPath,

  async asyncData ({ api, route }) {
    return {
      answers: (await api.get('/answers', {
        params: {
          page: route.query.page || 1,
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.answers-list
  .card.card-answer
    margin-bottom: 15px
</style>
