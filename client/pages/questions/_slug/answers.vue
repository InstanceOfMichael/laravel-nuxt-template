<template lang="pug">
  div
    | Question Answers
</template>

<script>
import axios from 'axios'
import QuestionCardRow from '~/components/Question/CardRow'

export default {
  componenets: {
    QuestionCardRow,
  },
  props: {
    question: {
      type: Object,
      required: true,
    },
  },
}
</script>
