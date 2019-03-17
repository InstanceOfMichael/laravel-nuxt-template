<template lang="pug">
  div
    | default question show
</template>

<script>
import axios from 'axios'
import QuestionCardRow from '~/components/Question/CardRow'

export default {
  components: {
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
