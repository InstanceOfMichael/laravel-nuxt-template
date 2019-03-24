<template lang="pug">
  .questions-answers-list
    AnswerCardRow(
      v-for="answer in answers.data"
      :key="answer.id"
      :item="answer"
    )
</template>

<script>
import AnswerCardRow from '~/components/Answer/CardRow'

export default {
  components: {
    AnswerCardRow,
  },
  props: {
    question: {
      type: Object,
      required: true,
    },
  },
  async asyncData ({ api, route, deslug }) {
    return {
      answers: (await api.get('/answers', {
        params: {
          question_id: deslug(route.params.slug),
        },
      })).data,
    }
  },
}
</script>

<style lang="sass">
.questions-answers-list
  .card.card-answer
    margin-bottom: 15px
</style>
